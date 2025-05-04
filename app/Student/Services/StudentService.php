<?php

namespace App\Student\Services;

use App\Models\Students;
use App\Student\StudentServiceInterface;

class StudentService implements StudentServiceInterface
{
    public function create(array $data)
    {
        return Students::create($data);
    }

    public function getById(int $id)
    {
        return Students::findOrFail($id);
    }

    public function getAll(int $perPage = 15)
    {
        return Students::with('classModel')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function update(array $data, int $id)
    {
        $student = Students::findOrFail($id);

        $student->update($data);
        return $student;
    }

    public function delete(int $id)
    {
        $student = Students::findOrFail($id);

        return $student ? $student->delete() : false;
    }
}
