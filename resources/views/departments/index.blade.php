@extends('layouts.app')

@section('title', 'Department Management')
@section('header', 'Department Management')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Departments List</h2>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">Add New Department</a>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($departments as $department)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $department->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $department->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <a href="{{ route('departments.show', $department->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('departments.edit', $department->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="3">No departments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection