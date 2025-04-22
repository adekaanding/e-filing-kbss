@extends('layouts.app')

@section('title', 'File Management')
@section('header', 'File Management')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Files List</h2>
        <a href="{{ route('files.create') }}" class="btn btn-primary">Add New File</a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @livewire('file-management-table')
</div>
@endsection