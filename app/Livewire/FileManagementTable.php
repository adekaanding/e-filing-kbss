<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use App\Models\File;
use Livewire\WithPagination;

class FileManagementTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $department = '';
    public $status = '';

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartment()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $departments = Department::all();

        $files = File::with('department')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_no', 'like', "%{$this->search}%")
                        ->orWhere('title', 'like', "%{$this->search}%");
                });
            })
            ->when($this->department, function ($query) {
                $query->where('department_id', $this->department);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.file-management-table', [
            'files' => $files,
            'departments' => $departments
        ]);
    }
}
