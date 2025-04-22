@extends('layouts.app')

@section('title', 'Change File Status')
@section('header', 'Change File Status')

@section('content')
<div class="card">
    <div class="mb-6">
        <h2 class="text-xl font-semibold">File: {{ $file->reference_no }} - {{ $file->title }}</h2>
        <p class="text-gray-600">Current status:
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
            @if($file->status === 'Available') bg-green-100 text-green-800 
            @elseif($file->status === 'Dalam Pinjaman') bg-yellow-100 text-yellow-800 
            @else bg-red-100 text-red-800 @endif">
                {{ $file->status }}
            </span>
        </p>
    </div>

    <form action="{{ route('files.update-status', $file->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="status" class="form-label">New Status</label>
            <select name="status" id="status" class="form-input" required>
                @foreach($statuses as $status)
                <option value="{{ $status }}" {{ $file->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="notes" class="form-label">Notes (optional)</label>
            <textarea name="notes" id="notes" class="form-input" rows="3"></textarea>
            @error('notes')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <a href="{{ route('files.show', $file->id) }}" class="btn btn-secondary mr-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
    </form>
</div>
@endsection