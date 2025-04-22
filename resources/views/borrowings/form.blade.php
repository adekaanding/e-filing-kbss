<!-- File: resources/views/borrowings/form.blade.php -->

@extends('layouts.app')

@section('title', 'Borrowing Form')
@section('header', 'Pinjaman File Form')

@section('content')
<div class="card">
    <h2 class="text-xl font-semibold mb-6">Register New File Borrowing</h2>

    <form action="{{ route('borrowings.store') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="borrower_name" class="form-label">Borrower Name</label>
            <input type="text" id="borrower_name" name="borrower_name" class="form-input" required value="{{ old('borrower_name') }}">
            @error('borrower_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        @livewire('borrowing-file-selector')

        @error('file_ids')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        <div class="flex justify-end mt-6">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary mr-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Register Borrowing</button>
        </div>
    </form>
</div>
@endsection