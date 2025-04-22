@extends('layouts.app')

@section('title', 'Edit Department')
@section('header', 'Edit Department')

@section('content')
<div class="card">
    <div class="mb-6">
        <h2 class="text-xl font-semibold">Edit Department</h2>
    </div>

    <form action="{{ route('departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Department Name</label>
            <input type="text" name="name" id="name" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('name', $department->name) }}" required>
            @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Department</button>
        </div>
    </form>
</div>
@endsection