@extends('layouts.app')

@section('title', 'File Details')
@section('header', 'File Details')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">File Information</h2>
        <div class="flex space-x-2">
            <a href="{{ route('files.edit', $file->id) }}" class="btn btn-secondary">Edit</a>
            <form action="{{ route('files.destroy', $file->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">Reference No</p>
            <p class="text-base">{{ $file->reference_no }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Department</p>
            <p class="text-base">{{ $file->department->name }}</p>
        </div>
        <div class="md:col-span-2">
            <p class="text-sm font-medium text-gray-500">Title</p>
            <p class="text-base">{{ $file->title }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Status</p>
            <p class="text-base">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                @if($file->status === 'Available') bg-green-100 text-green-800 
                @elseif($file->status === 'Dalam Pinjaman') bg-yellow-100 text-yellow-800 
                @else bg-red-100 text-red-800 @endif">
                    {{ $file->status }}
                </span>
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Created At</p>
            <p class="text-base">{{ $file->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-medium">Borrowing History</h3>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($file->borrowings as $borrowing)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $borrowing->borrower_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $borrowing->borrow_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $borrowing->return_date ?? 'Not returned yet' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($borrowing->status === 'Dikembalikan') bg-green-100 text-green-800 
                            @elseif($borrowing->status === 'Dalam Pinjaman') bg-yellow-100 text-yellow-800 
                            @else bg-red-100 text-red-800 @endif">
                                {{ $borrowing->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="4">No borrowing history for this file</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection