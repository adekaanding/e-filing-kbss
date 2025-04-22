<div>
    <div class="grid grid-cols-1 gap-6 mb-6">
        <div>
            <label for="department_id" class="form-label">Department</label>
            <select id="department_id" wire:model.live="selectedDepartment" class="form-input" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-6">
        <label class="form-label">Select File(s)</label>
        <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
            @if(empty($selectedDepartment))
            <p class="text-gray-500">Please select a department first to view available files.</p>
            @elseif(count($availableFiles) === 0)
            <p class="text-gray-500">No available files found for the selected department.</p>
            @else
            @foreach($availableFiles as $file)
            <div class="flex items-center p-2 hover:bg-gray-50 rounded">
                <input
                    type="checkbox"
                    id="file_{{ $file->id }}"
                    value="{{ $file->id }}"
                    wire:model.live="selectedFiles"
                    name="file_ids[]"
                    class="mr-2 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="file_{{ $file->id }}" class="block w-full cursor-pointer">
                    <div class="text-sm font-medium">{{ $file->reference_no }}</div>
                    <div class="text-xs text-gray-500">{{ $file->title }}</div>
                </label>
            </div>
            @endforeach
            @endif
        </div>
        @if(count($selectedFiles) > 0)
        <div class="mt-2 text-sm text-gray-600">
            {{ count($selectedFiles) }} file(s) selected
        </div>
        @endif
    </div>
</div>