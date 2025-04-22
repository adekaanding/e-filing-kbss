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
                <div class="text-2xl font-bold text-available">{{ $availableCount }}</div>
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
                <div class="text-2xl font-bold text-dalam-pinjaman">{{ $borrowedCount }}</div>
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
                <div class="text-2xl font-bold text-belum-dikembalikan">{{ $overdueCount }}</div>
                @if($overdueCount > 0)
                <a href="{{ route('history.index', ['status' => 'Belum Dikembalikan']) }}" class="text-xs text-red-600 hover:underline">View All</a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Overdue Alert Section (only shows if there are overdue files) -->
@if($overdueCount > 0)
<div class="mt-6">
    <div class="bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    There {{ $overdueCount == 1 ? 'is' : 'are' }} <strong>{{ $overdueCount }}</strong> overdue {{ Str::plural('file', $overdueCount) }}. Please process them as soon as possible.
                </p>
                <div class="mt-2">
                    <a href="{{ route('history.index', ['status' => 'Belum Dikembalikan']) }}" class="text-sm font-medium text-red-700 hover:text-red-600">
                        View overdue files <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
                    @forelse ($recentBorrowings as $borrowing)
                    <tr class="{{ $borrowing->status == 'Belum Dikembalikan' ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $borrowing->file->reference_no }}</div>
                            <div class="text-sm text-gray-500">{{ $borrowing->file->title }}</div>
                            <div class="text-xs text-gray-400">{{ $borrowing->file->department->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $borrowing->borrower_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $borrowing->borrow_date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $borrowing->borrow_date->format('h:i A') }}</div>
                            @if(isset($borrowing->days_overdue) && $borrowing->days_overdue > 0)
                            <div class="text-xs text-red-600 font-semibold mt-1">
                                Overdue: {{ $borrowing->days_overdue }} {{ Str::plural('day', $borrowing->days_overdue) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($borrowing->status == 'Dalam Pinjaman')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $borrowing->status }}
                            </span>
                            @elseif ($borrowing->status == 'Dikembalikan')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $borrowing->status }}
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                {{ $borrowing->status }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if ($borrowing->status != 'Dikembalikan')
                            <button
                                onclick="openReturnModal({{ $borrowing->id }}, '{{ $borrowing->file->reference_no }}')"
                                class="{{ $borrowing->status == 'Belum Dikembalikan' ? 'text-red-600 hover:text-red-900 font-medium' : 'text-indigo-600 hover:text-indigo-900' }}">
                                {{ $borrowing->status == 'Belum Dikembalikan' ? 'Process Overdue Return' : 'Process Return' }}
                            </button>
                            @else
                            <span class="text-gray-400">Already Returned</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="5">No recent borrowings</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- Return Confirmation Modal -->
<div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Confirm File Return</h3>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="mb-4">Are you sure you want to process the return for file: <span id="fileRef" class="font-semibold"></span>?</p>

        <form id="returnForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="confirmation" value="confirm">

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeReturnModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Return</button>
            </div>
        </form>
    </div>
</div>
<div class="mt-8">
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Department File Distribution</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Files</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In Loan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overdue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($departmentDistribution as $dept)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $dept['department'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $dept['total'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-green-500">{{ $dept['available'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-yellow-500">{{ $dept['borrowed'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-500">{{ $dept['overdue'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @if($dept['total'] > 0)
                                <div class="flex h-2.5 rounded-full">
                                    <div class="bg-green-500 h-2.5 rounded-l-full" style="width: {{ ($dept['available']/$dept['total'])*100 }}%"></div>
                                    <div class="bg-yellow-500 h-2.5" style="width: {{ ($dept['borrowed']/$dept['total'])*100 }}%"></div>
                                    <div class="bg-red-500 h-2.5 rounded-r-full" style="width: {{ ($dept['overdue']/$dept['total'])*100 }}%"></div>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="6">No departments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function openReturnModal(borrowingId, fileReference) {
        document.getElementById('fileRef').textContent = fileReference;
        document.getElementById('returnForm').action = `/borrow/${borrowingId}/return`;
        document.getElementById('returnModal').classList.remove('hidden');
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
    }
</script>
@endsection