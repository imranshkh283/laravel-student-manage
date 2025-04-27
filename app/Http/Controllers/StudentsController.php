<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student\StudentServiceInterface;
use App\Models\Classess as ClassDivision;

use App\Http\Requests\StoreStudentRequest;
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
        $classDivisions = Cache::get('classes');

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

        $csvData = array_map('str_getcsv', file(storage_path('app/' . $filePath)));

        $data = [];
        $data1 = [];

        foreach (ClassDivision::getNameAndDivision() as $class) {
            $clsDiv[$class->id] = $class->class_name . '-' . $class->division;
        }

        $classLookup = array_flip($clsDiv);

        foreach ($csvData as $index => $csvRow) {
            if ($index == 0) continue; // skip header

            $name = $csvRow[0];
            $className = $csvRow[1];
            $division = $csvRow[2];
            $rollNumber = $csvRow[3];

            $classDivision = $className . '-' . $division;

            if (isset($classLookup[$classDivision])) {
                $data[] = [
                    'name' => $name,
                    'class_id' => $classLookup[$classDivision],
                    'roll_number' => $rollNumber,
                ];
            } else {
                $data1[$classDivision][$rollNumber] = $csvRow;
            }
        }

        DB::transaction(function () use ($data, $data1) {
            if (!empty($data)) {
                DB::table('students')->insert($data);
            }

            if (!empty($data1)) {
                $this->insertStudentDataWithClass($data1);
            }
        });

        return redirect()->route('students')->withSuccess('Students imported successfully.');
    }

    public function insertStudentDataWithClass(array $data)
    {
        $student = collect($data);
        DB::transaction(function () use ($student) {
            $data = [];
            foreach ($student as $classDivision => $studentData) {
                $splt = explode('-', $classDivision);
                $className = $splt[0];
                $division = $splt[1];

                $class = ClassDivision::firstOrCreate([
                    'class_name' => $className,
                    'division' => $division,
                ]);

                foreach ($studentData as $key => $value) {
                    $data[] = [
                        'name' => $value[0],
                        'class_id' => $class->id,
                        'roll_number' => $value[3],
                    ];
                }
            }
            if (!empty($data)) {
                DB::table('students')->insert($data);
            }
        });
    }

    public function studentCSVSampleDownload()
    {
        return response()->download(storage_path('app/student.csv'), 'student.csv');
    }
}
