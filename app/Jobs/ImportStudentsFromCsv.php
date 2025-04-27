<?php

namespace App\Jobs;

use App\Models\Classess as ClassDivision;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportStudentsFromCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $csvData = array_map('str_getcsv', file(storage_path('app/' . $this->filePath)));

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
    }

    private function insertStudentDataWithClass(array $data)
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
}
