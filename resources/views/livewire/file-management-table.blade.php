<div>
    <!-- Search and Filter -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" wire:model.debounce.300ms="search" id="search" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Reference No or Title">
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <select wire:model="department" id="department" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="status" id="status" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="">All Statuses</option>
                    <option value="{{ App\Models\File::STATUS_AVAILABLE }}">Available</option>
                    <option value="{{ App\Models\File::STATUS_BORROWED }}">Dalam Pinjaman</option>
                    <option value="{{ App\Models\File::STATUS_OVERDUE }}">Belum Dikembalikan</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading indicator -->
    <div wire:loading class="mb-4">
        <div class="flex justify-center">
            <div class="inline-flex items-center px-4 py-2 text-sm text-gray-700">
                Loading...
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($files as $file)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->reference_no }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->department->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($file->status === 'Available') bg-green-100 text-green-800 
                        @elseif($file->status === 'Dalam Pinjaman') bg-yellow-100 text-yellow-800 
                        @else bg-red-100 text-red-800 @endif">
                            {{ $file->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('files.show', $file->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('files.edit', $file->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                            <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="5">No files found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $files->links() }}
    </div>
</div>