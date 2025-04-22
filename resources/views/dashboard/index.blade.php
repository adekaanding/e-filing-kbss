@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Available Files Card -->
    <div class="card">
        <div class="flex items-center">
            <div class="rounded-full p-3 bg-green-100">
                <div class="h-8 w-8 text-available">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-700">Available Files</h2>
                <div class="text-2xl font-bold text-available">0</div>
            </div>
        </div>
    </div>

    <!-- Borrowed Files Card -->
    <div class="card">
        <div class="flex items-center">
            <div class="rounded-full p-3 bg-yellow-100">
                <div class="h-8 w-8 text-dalam-pinjaman">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-700">Dalam Pinjaman</h2>
                <div class="text-2xl font-bold text-dalam-pinjaman">0</div>
            </div>
        </div>
    </div>

    <!-- Overdue Files Card -->
    <div class="card">
        <div class="flex items-center">
            <div class="rounded-full p-3 bg-red-100">
                <div class="h-8 w-8 text-belum-dikembalikan">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-700">Belum Dikembalikan</h2>
                <div class="text-2xl font-bold text-belum-dikembalikan">0</div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Recent Borrowings</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="5">No recent borrowings</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection