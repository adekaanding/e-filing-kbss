@extends('layouts.app')

@section('title', 'Borrowing History')
@section('header', 'Sejarah Peminjaman File')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Borrowing History</h2>

        <div class="flex items-center space-x-4">
            <div class="flex">
                <select id="status-filter" class="form-select text-sm" onchange="window.location = this.value">
                    <option value="{{ route('history.index') }}" {{ !request('status') ? 'selected' : '' }}>All Status</option>
                    <option value="{{ route('history.index', ['status' => 'Dalam Pinjaman']) }}" {{ request('status') == 'Dalam Pinjaman' ? 'selected' : '' }}>Dalam Pinjaman</option>
                    <option value="{{ route('history.index', ['status' => 'Dikembalikan']) }}" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="{{ route('history.index', ['status' => 'Belum Dikembalikan']) }}" {{ request('status') == 'Belum Dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                </select>
            </div>

            <form action="{{ route('history.index') }}" method="GET" class="flex">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="text" name="search" placeholder="Search by borrower or file..." class="form-input w-64" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ml-2">Search</button>
            </form>
        </div>
    </div>

    <!-- Overdue Stats Summary (only shown when filtering by overdue) -->
    @if(request('status') == 'Belum Dikembalikan' && $borrowings->total() > 0)
    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Overdue Files Summary</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>There {{ $borrowings->total() == 1 ? 'is' : 'are' }} currently <strong>{{ $borrowings->total() }}</strong> overdue {{ Str::plural('file', $borrowings->total()) }}.</p>
                    <p class="mt-1">Please follow up with the borrowers to ensure these files are returned promptly.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($borrowings as $borrowing)
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
                            Overdue by: {{ $borrowing->days_overdue }} {{ Str::plural('business day', $borrowing->days_overdue) }}
                        </div>
                        @elseif(isset($borrowing->days_late) && $borrowing->days_late > 0)
                        <div class="text-xs text-orange-600 mt-1">
                            Returned {{ $borrowing->days_late }} {{ Str::plural('day', $borrowing->days_late) }} late
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($borrowing->return_date)
                        <div class="text-sm text-gray-900">{{ $borrowing->return_date->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $borrowing->return_date->format('h:i A') }}</div>
                        @else
                        <span class="text-sm text-gray-400">Not returned</span>
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
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="6">No borrowing history found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $borrowings->withQueryString()->links() }}
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