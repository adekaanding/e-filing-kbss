<div class="mt-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium">Status History</h3>
        <a href="{{ route('files.change-status', $file->id) }}" class="btn btn-primary">Change Status</a>
    </div>

    <div class="space-y-4">
        @forelse ($file->statusLogs as $log)
        <div class="border-l-4 pl-4 py-2 
                @if($log->new_status === 'Available') border-green-500 
                @elseif($log->new_status === 'Dalam Pinjaman') border-yellow-500 
                @else border-red-500 @endif">
            <div class="flex justify-between">
                <div>
                    <p class="font-medium">Changed from <span class="italic">{{ $log->old_status }}</span> to <span class="font-bold">{{ $log->new_status }}</span></p>
                    @if($log->notes)
                    <p class="text-sm text-gray-600 mt-1">{{ $log->notes }}</p>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    <p>{{ $log->created_at->format('d M Y, h:i A') }}</p>
                    <p class="text-right">by {{ $log->user ? $log->user->name : 'System' }}</p>
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-500">No status changes have been recorded for this file.</p>
        @endforelse
    </div>
</div>