<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Advanced Search</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Search Term -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="File no, title or borrower name..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select
                    wire:model.live="status"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    <option value="{{ App\Models\Borrowing::STATUS_BORROWED }}">{{ App\Models\Borrowing::STATUS_BORROWED }}</option>
                    <option value="{{ App\Models\Borrowing::STATUS_RETURNED }}">{{ App\Models\Borrowing::STATUS_RETURNED }}</option>
                    <option value="{{ App\Models\Borrowing::STATUS_OVERDUE }}">{{ App\Models\Borrowing::STATUS_OVERDUE }}</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select
                    wire:model.live="department"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Date Range - Start -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input
                    wire:model.live="startDate"
                    type="date"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            </div>

            <!-- Date Range - End -->
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input
                    wire:model.live="endDate"
                    type="date"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            </div>
        </div>

        <div class="flex justify-end">
            <button
                wire:click="clearFilters"
                class="btn btn-secondary mr-2">
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('file_id')">
                            File
                            @if ($sortField === 'file_id')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('borrower_name')">
                            Borrower
                            @if ($sortField === 'borrower_name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('borrow_date')">
                            Borrow Date
                            @if ($sortField === 'borrow_date')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('return_date')">
                            Return Date
                            @if ($sortField === 'return_date')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                            Status
                            @if ($sortField === 'status')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($borrowings as $borrowing)
                    <tr class="{{ $borrowing->status == App\Models\Borrowing::STATUS_OVERDUE ? 'bg-red-50' : '' }}">
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
                            @if(isset($borrowing->days_late) && $borrowing->days_late > 0)
                            <div class="text-xs text-orange-600 font-semibold mt-1">
                                Returned {{ $borrowing->days_late }} {{ Str::plural('day', $borrowing->days_late) }} late
                            </div>
                            @endif
                            @else
                            <div class="text-sm text-gray-400">-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($borrowing->status == App\Models\Borrowing::STATUS_BORROWED)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $borrowing->status }}
                            </span>
                            @elseif ($borrowing->status == App\Models\Borrowing::STATUS_RETURNED)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $borrowing->status }}
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                {{ $borrowing->status }}
                            </span>
                            @if(isset($borrowing->days_overdue) && $borrowing->days_overdue > 0)
                            <div class="text-xs text-red-600 font-semibold mt-1">
                                {{ $borrowing->days_overdue }} {{ Str::plural('day', $borrowing->days_overdue) }} overdue
                            </div>
                            @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if ($borrowing->status != App\Models\Borrowing::STATUS_RETURNED)
                            <button
                                onclick="openReturnModal({{ $borrowing->id }}, '{{ $borrowing->file->reference_no }}')"
                                class="{{ $borrowing->status == App\Models\Borrowing::STATUS_OVERDUE ? 'text-red-600 hover:text-red-900 font-medium' : 'text-indigo-600 hover:text-indigo-900' }}">
                                {{ $borrowing->status == App\Models\Borrowing::STATUS_OVERDUE ? 'Process Overdue Return' : 'Process Return' }}
                            </button>
                            @else
                            <span class="text-gray-400">Already Returned</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center" colspan="6">
                            No borrowing records found matching your criteria
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $borrowings->links() }}
        </div>
    </div>
</div>