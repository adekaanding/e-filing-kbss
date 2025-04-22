@extends('layouts.app')

@section('title', 'Department Details')
@section('header', 'Department Details')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Department Information</h2>
        <div class="flex space-x-2">
            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-secondary">Edit</a>
            <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">ID</p>
            <p class="text-base">{{ $department->id }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Name</p>
            <p class="text-base">{{ $department->name }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Created At</p>
            <p class="text-base">{{ $department->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Updated At</p>
            <p class="text-base">{{ $department->updated_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-medium">Files in this Department</h3>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($department->files as $file)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $file->reference_no }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $file->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($file->status === 'Available') bg-green-100 text-green-800 
                            @elseif($file->status === 'Dalam Pinjaman') bg-yellow-100 text-yellow-800 
                            @else bg-red-100 text-red-800 @endif">
                                {{ $file->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="3">No files in this department</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection