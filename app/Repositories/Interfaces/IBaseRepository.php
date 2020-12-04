<?php


namespace App\Repositories\Interfaces;


interface IBaseRepository
{
    public function all();
    public function find(int $id);
    public function findWhere(string $column, $value);
    public function findWhereFirst(string $column, $value);
    public function paginate(int $perPage = 10);
    public function create(array $data);
    public function update(int $id ,array $data);
    public function delete(int $id);
}
