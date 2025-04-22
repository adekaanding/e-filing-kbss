<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Livewire\WithPagination;

class FileManagementTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $department = '';
    public $status = '';

    protected $fileRepository;

    // Replace the outdated property with the correct Livewire property name
    protected $queryString = [
        'search' => ['except' => ''],
        'department' => ['except' => ''],
        'status' => ['except' => '']
    ];

    // Ensure we reset pagination when search parameters change
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDepartment()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->department = '';
        $this->status = '';
        $this->resetPage();
    }

    // Inject repository using Livewire's boot method
    public function boot(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function render()
    {
        $departments = Department::all();

        // Use repository instead of direct model query
        $files = $this->fileRepository->searchFiles(
            $this->search,
            $this->department,
            $this->status
        );

        return view('livewire.file-management-table', [
            'files' => $files,
            'departments' => $departments
        ]);
    }
}
