@extends('layouts.app')

@section('title', 'Borrowing Form')
@section('header', 'Pinjaman File Form')

@section('content')
<div class="card">
    <h2 class="text-xl font-semibold mb-6">Register New File Borrowing</h2>

    <form action="{{ route('borrowings.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="borrower_name" class="form-label">Borrower Name</label>
                <input type="text" id="borrower_name" name="borrower_name" class="form-input" required>
            </div>

            <div>
                <label for="department_id" class="form-label">Department</label>
                <select id="department_id" name="department_id" class="form-input" required>
                    <option value="">Select Department</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="form-label">Select File(s)</label>
            <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                <p class="text-gray-500">Please select a department first to view available files.</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" class="btn btn-secondary mr-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Register Borrowing</button>
        </div>
    </form>
</div>
@endsection