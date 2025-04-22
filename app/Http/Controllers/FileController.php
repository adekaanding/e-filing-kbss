<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use App\Models\File;

class FileController extends Controller
{
    protected $fileRepository;
    protected $departmentRepository;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        DepartmentRepositoryInterface $departmentRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Display a listing of the files.
     */
    public function index(Request $request)
    {
        return view('files.index');
    }

    /**
     * Show the form for creating a new file.
     */
    public function create()
    {
        $departments = $this->departmentRepository->all();
        $statuses = [
            File::STATUS_AVAILABLE => File::STATUS_AVAILABLE,
            File::STATUS_BORROWED => File::STATUS_BORROWED,
            File::STATUS_OVERDUE => File::STATUS_OVERDUE
        ];

        return view('files.create', compact('departments', 'statuses'));
    }

    /**
     * Store a newly created file in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_no' => 'required|string|max:50|unique:files,reference_no',
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|in:' . File::STATUS_AVAILABLE . ',' . File::STATUS_BORROWED . ',' . File::STATUS_OVERDUE,
        ]);

        $file = $this->fileRepository->create($validated);

        return redirect()->route('files.show', $file->id)
            ->with('success', 'File created successfully.');
    }

    /**
     * Display the specified file.
     */
    public function show($id)
    {
        $file = $this->fileRepository->find($id);
        return view('files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified file.
     */
    public function edit($id)
    {
        $file = $this->fileRepository->find($id);
        $departments = $this->departmentRepository->all();
        $statuses = [
            File::STATUS_AVAILABLE => File::STATUS_AVAILABLE,
            File::STATUS_BORROWED => File::STATUS_BORROWED,
            File::STATUS_OVERDUE => File::STATUS_OVERDUE
        ];

        return view('files.edit', compact('file', 'departments', 'statuses'));
    }

    /**
     * Update the specified file in storage.
     */
    public function update(Request $request, $id)
    {
        $file = $this->fileRepository->find($id);

        $validated = $request->validate([
            'reference_no' => 'required|string|max:50|unique:files,reference_no,' . $id,
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|in:' . File::STATUS_AVAILABLE . ',' . File::STATUS_BORROWED . ',' . File::STATUS_OVERDUE,
        ]);

        $this->fileRepository->update($id, $validated);

        return redirect()->route('files.show', $id)
            ->with('success', 'File updated successfully.');
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy($id)
    {
        $this->fileRepository->delete($id);
        return redirect()->route('files.index')
            ->with('success', 'File deleted successfully.');
    }

    /**
     * Show the file status history.
     */
    public function statusHistory($id)
    {
        $file = $this->fileRepository->find($id);
        $statusLogs = $this->fileRepository->getStatusHistory($id);

        return view('files.status_history', compact('file', 'statusLogs'));
    }

    /**
     * Show form to change file status.
     */
    public function changeStatus($id)
    {
        $file = $this->fileRepository->find($id);
        $statuses = [
            File::STATUS_AVAILABLE => File::STATUS_AVAILABLE,
            File::STATUS_BORROWED => File::STATUS_BORROWED,
            File::STATUS_OVERDUE => File::STATUS_OVERDUE
        ];

        return view('files.change_status', compact('file', 'statuses'));
    }

    /**
     * Update the file status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . File::STATUS_AVAILABLE . ',' . File::STATUS_BORROWED . ',' . File::STATUS_OVERDUE,
            'notes' => 'nullable|string|max:255',
        ]);

        $this->fileRepository->changeStatus(
            $id,
            $validated['status'],
            auth()->id(),
            $validated['notes'] ?? null
        );

        return redirect()->route('files.show', $id)
            ->with('success', 'File status updated successfully.');
    }
}
