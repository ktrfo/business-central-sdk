<?php

namespace Ktr\BusinessCentral\Models;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonSerializable;
use Ktr\BusinessCentral\Client\HttpClient;
use Ktr\BusinessCentral\Models\Builder\Builder;

/**
 * @mixin Builder
 */
abstract class Model implements ArrayAccess, JsonSerializable
{
    /**
     * The api version associated with the model.
     *
     * @var string
     */
    protected $apiVersion = 'api/v2.0';

    /**
     * The resource associated with the model.
     *
     * @var string
     */
    protected $resource;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'number';

    /**
     * @var array The original attributes.
     */
    protected $original = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(public array $attributes = [])
    {
        $this->setResource($this->getResource());
        $this->original = $attributes;
    }


    /**
     * @return Builder
     */
    public function newQuery(): Builder
    {
        return new Builder($this);
    }


    /**
     * Create a new Collection instance.
     *
     * @param array $models
     * @return Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }


    /**
     * Convert the object into something JSON serializable.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }


    /**
     * Get the resource associated with the model.
     */
    public function getResource(): string
    {
        return $this->resource ?? lcfirst(class_basename($this));
    }

    /**
     * Get the primary key.
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Get the api version.
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Set the resource associated with the model.
     *
     * @param string $resource
     * @return $this
     */
    public function setResource(string $resource): Model
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Set the primary key for the model.
     *
     * @param string $key
     * @return $this
     */
    public function setKeyName($key)
    {
        $this->primaryKey = $key;

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function getAttribute($key)
    {
        if (!$key) {
            return '';
        }
        return $this->attributes[$key] = $this->attributes[$key] ?? null;

    }

    public function setAttribute($key, $value)
    {
        return $this->attributes[$key] = $value;
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        try {
            return !is_null($this->getAttribute($offset));
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset], $this->relations[$offset]);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['increment', 'decrement', 'incrementQuietly', 'decrementQuietly'])) {
            return $this->$method(...$parameters);
        }

        if ($resolver = $this->relationResolver(static::class, $method)) {
            return $resolver($this);
        }

        if (Str::startsWith($method, 'through') &&
            method_exists($this, $relationMethod = Str::of($method)->after('through')->lcfirst()->toString())) {
            return $this->through($relationMethod);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }


    /**
     * Handle dynamic static method calls into the model.
     *
     * @return Builder
     */
    public static function query(): Builder
    {
        return (new static)->newQuery();
    }

    /**
     * Handle dynamic static method calls into the model.
     *
     * @param string $method
     * @param array $parameters
     * @return Builder|Model
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->newQuery()->$method(...$parameters);
    }

    public function save(): static
    {
        $this->update(array_diff($this->attributes, $this->original));
        return $this;
    }

    public function update($data): static
    {
        $resource = $this->getResource() . "($this->id)";
        $etag = $this->{'@odata.etag'};
        $item = HttpClient::patch($etag, $resource, $this->getApiVersion(), $data);
        $this->attributes = $item;
        return $this;
    }

    public function delete(): bool
    {
        $resource = $this->getResource() . "($this->id)";
        $etag = $this->{'@odata.etag'};
        return HttpClient::delete($etag, $resource, $this->getApiVersion());
    }

}
