<?php

namespace App\Student;

interface StudentServiceInterface
{
    public function create(array $data);

    public function getAll(int $perPage = 15);

    public function getById(int $id);

    public function update(array $data, int $id);

    public function delete(int $id);
}
