<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        $departments = $this->departmentRepository->all();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);

        $this->departmentRepository->create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department.
     */
    public function show(string $id)
    {
        $department = $this->departmentRepository->find($id);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(string $id)
    {
        $department = $this->departmentRepository->find($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
        ]);

        $this->departmentRepository->update($id, $validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(string $id)
    {
        $this->departmentRepository->delete($id);

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
