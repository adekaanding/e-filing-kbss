<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use App\Models\File;

class BorrowingFileSelector extends Component
{
    public $selectedDepartment = '';
    public $selectedFiles = [];
    public $availableFiles = [];

    public function updatedSelectedDepartment($value)
    {
        if (empty($value)) {
            $this->availableFiles = [];
            $this->selectedFiles = [];
            return;
        }

        $this->availableFiles = File::where('department_id', $value)
            ->where('status', File::STATUS_AVAILABLE)
            ->get();

        $this->selectedFiles = [];
    }

    public function render()
    {
        $departments = Department::all();

        return view('livewire.borrowing-file-selector', [
            'departments' => $departments
        ]);
    }
}
