<?php

namespace Ktr\BusinessCentral\Models\Builder;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Ktr\BusinessCentral\Client\HttpClient;
use Ktr\BusinessCentral\Models\Model;

/**
 * @mixin Model|Builder
 */
class Builder
{
    use HasFilters;
    use HasSelects;
    use HasSorting;
    use HasExpands;
    use HasPagination;

    protected array $query = [];

    public function __construct(protected Model $model)
    {
    }

    public function get(): Collection
    {
        $items = HttpClient::get($this->model->getResource(), $this->model->getApiVersion(), $this->query);
        return $items->map(fn($item) => new $this->model($item));
    }

    function first(): Model
    {
        $items = HttpClient::get($this->model->getResource(), $this->model->getApiVersion(), $this->query);
        return new $this->model($items->first());
    }

    public function find($id): Model
    {
        $this->where($this->model->getPrimaryKey(), $id)->first();
        return $this->first();
    }

    public function create($data): Model
    {
        $item = HttpClient::post($this->model->getResource(), $this->model->getApiVersion(), $data);
        return new $this->model($item);
    }

    public function count(): int
    {
        return $this->get()->count();
    }

    public function all(): Collection
    {
        return $this->lazy(20000)->collect();
    }

    public function lazy(?int $pageSize = 1000): LazyCollection
    {
        return LazyCollection::make(function () use ($pageSize) {

            $page = 0;

            $hasNext = true;

            while ($hasNext) {
                if ($page > 0) {
                    $this->skip($page * $pageSize);
                }

                $this->take($pageSize);

                $records = $this->get();

                $hasNext = $records->count() === $pageSize;

                foreach ($records as $record) {
                    yield $record;
                }
                $page++;
            }


        });
    }

    public function dd(): never
    {
        dd($this->model->getResource(), $this->model->getApiVersion(), $this->query);
    }
}
