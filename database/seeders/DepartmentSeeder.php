<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Bahagian Belia'],
            ['name' => 'Bahagian Sukan'],
            ['name' => 'Bahagian Digital'],
            ['name' => 'Bahagian Sumber Manusia'],
            ['name' => 'Bahagian Integriti'],
            ['name' => 'Bahagian Pembangunan'],
            ['name' => 'Individu'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
