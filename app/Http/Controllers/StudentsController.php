<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student\StudentServiceInterface;
use App\Models\Classess as ClassDivision;
use App\Models\Students;

use App\Http\Requests\StoreStudentRequest;
use Illuminate\Contracts\Cache\Store;

class StudentsController extends Controller
{
    protected $studentService;

    public function __construct(StudentServiceInterface $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $students = $this->studentService->getAll($perPage);

        return view('student.list', compact('students'));
    }

    public function create()
    {
        $classDivisions = ClassDivision::getNameAndDivision();

        return view('student.create', compact('classDivisions'));
    }

    public function insert(StoreStudentRequest $request)
    {

        $this->studentService->create($request->validated());

        return redirect()->route('students')->withSuccess('Student added successfully.');
    }

    public function edit(Request $request)
    {
        $student = $this->studentService->getById($request->id);

        $classDivisions = ClassDivision::getNameAndDivision();

        return view('student.edit', compact('student', 'classDivisions'));
    }

    public function update(StoreStudentRequest $request)
    {
        $this->studentService->update($request->validated(), $request->id);

        return redirect()->route('students')->withSuccess('Student updated successfully.');
    }

    public function delete(Request $request)
    {
        $this->studentService->delete($request->id);

        return redirect()->route('students')->withDanger('Student deleted successfully.');
    }
}
