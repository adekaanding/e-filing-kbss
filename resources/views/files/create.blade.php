@extends('layouts.app')

@section('title', 'Create File')
@section('header', 'Create File')

@section('content')
<div class="card">
    <div class="mb-6">
        <h2 class="text-xl font-semibold">Add New File</h2>
    </div>

    <form action="{{ route('files.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="reference_no" class="block text-sm font-medium text-gray-700">Reference Number</label>
                <input type="text" name="reference_no" id="reference_no" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('reference_no') }}" required>
                @error('reference_no')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" id="department_id" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">File Title</label>
                <input type="text" name="title" id="title" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('title') }}" required>
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @foreach($statuses as $key => $value)
                    <option value="{{ $key }}" {{ old('status', 'Available') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('files.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create File</button>
        </div>
    </form>
</div>
@endsection