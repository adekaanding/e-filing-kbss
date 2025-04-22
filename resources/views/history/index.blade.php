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
                <tr>
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
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $borrowing->status }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if ($borrowing->status != 'Dikembalikan')
                        <button
                            onclick="openReturnModal({{ $borrowing->id }}, '{{ $borrowing->file->reference_no }}')"
                            class="text-indigo-600 hover:text-indigo-900">
                            Process Return
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