@extends('layouts.app')

@section('title', 'Borrowing History')
@section('header', 'Sejarah Peminjaman File')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Borrowing History</h1>
    <p class="text-gray-600">View and manage all file borrowing records</p>
</div>

<!-- Livewire Component -->
@livewire('borrowing-history-table', ['status' => $status ?? null])

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