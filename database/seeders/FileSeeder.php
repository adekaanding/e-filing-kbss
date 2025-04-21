<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\File;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all departments
        $departments = Department::all();

        // Department abbreviations mapping
        $abbrevMap = [
            'Bahagian Belia' => 'BB',
            'Bahagian Sukan' => 'BS',
            'Bahagian Digital' => 'BD',
            'Bahagian Sumber Manusia' => 'BSM',
            'Bahagian Integriti' => 'BI',
            'Bahagian Pembangunan' => 'BP',
            'Individu' => 'IND'
        ];

        foreach ($departments as $department) {
            $abbrev = $abbrevMap[$department->name] ?? ('D' . $department->id);

            for ($i = 1; $i <= 5; $i++) {
                File::create([
                    'reference_no' => $abbrev . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'title' => 'File ' . $i . ' ' . $department->name,
                    'department_id' => $department->id,
                    'status' => File::STATUS_AVAILABLE,
                ]);
            }
        }
    }
}
