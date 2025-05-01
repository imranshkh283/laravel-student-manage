<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student\StudentServiceInterface;
use App\Models\Classess as ClassDivision;

use App\Http\Requests\StoreStudentRequest;
use App\Jobs\ImportStudentsFromCsv;
use App\Models\Classess;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $classDivisions = Cache::get('classes') ?? Classess::getNameAndDivision();

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

        $classDivisions = Cache::get('classes');

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

    public function import()
    {
        return view('student.import');
    }

    public function csvImport(Request $request)
    {
        $file = $request->file('student_import');

        $filePath = $file->storeAs('uploads', $file->getClientOriginalName());

        ImportStudentsFromCsv::dispatch($filePath);

        return redirect()->route('students')->withSuccess('Students imported successfully.');
    }

    public function studentCSVSampleDownload()
    {
        return response()->download(storage_path('app/student.csv'), 'student.csv');
    }
}
