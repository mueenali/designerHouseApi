<?php


namespace App\Repositories\Eloquent;


use App\Exceptions\ModelNotDefined;
use App\Repositories\Criteria\ICriteria;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Arr;


abstract class BaseRepository implements IBaseRepository, ICriteria
{

    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all()
    {
        return $this->model->get();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWhere(string $column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    public function findWhereFirst(string $column, $value)
    {
       return $this->model->where($column, $value)->firstOrFail();
    }

    public function paginate(int $perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete(int $id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);
        foreach ($criteria as $criterion)
        {
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }

     private function getModelClass()
    {
        if(!method_exists($this, 'model'))
        {
            throw new ModelNotDefined('model not defined');
        }

        return app()->make($this->model());
    }
}
