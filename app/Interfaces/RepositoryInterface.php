<?php
// Контракт який повинен виконувати кожен репозиторій
interface RepositoryInterface
{
    public function findById($id);
    public function findAll();
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}