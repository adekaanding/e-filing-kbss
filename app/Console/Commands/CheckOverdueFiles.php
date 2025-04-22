<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Models\Borrowing;
use App\Models\File;
use Carbon\Carbon;

class CheckOverdueFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for files that are overdue (borrowed more than 7 business days ago)';

    /**
     * The borrowing repository implementation.
     * 
     * @var BorrowingRepositoryInterface
     */
    protected $borrowingRepository;

    /**
     * The file repository implementation.
     * 
     * @var FileRepositoryInterface
     */
    protected $fileRepository;

    /**
     * Create a new command instance.
     *
     * @param BorrowingRepositoryInterface $borrowingRepository
     * @param FileRepositoryInterface $fileRepository
     * @return void
     */
    public function __construct(
        BorrowingRepositoryInterface $borrowingRepository,
        FileRepositoryInterface $fileRepository
    ) {
        parent::__construct();
        $this->borrowingRepository = $borrowingRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for overdue files...');

        // Get active borrowings
        $activeBorrowings = $this->borrowingRepository->getActiveBorrowings();

        // Filter for borrowings that are in STATUS_BORROWED (not already overdue) 
        // and are more than 7 business days old
        $overdueBorrowings = $activeBorrowings->filter(function ($borrowing) {
            if ($borrowing->status !== Borrowing::STATUS_BORROWED) {
                return false;
            }

            // Calculate if borrowing is more than 7 business days old
            $businessDays = $this->calculateBusinessDays($borrowing->borrow_date, now());
            return $businessDays > 7;
        });

        if ($overdueBorrowings->isEmpty()) {
            $this->info('No new overdue files found.');
            return 0;
        }

        $this->info('Found ' . $overdueBorrowings->count() . ' overdue files.');

        // Update the status of each overdue borrowing and its associated file
        foreach ($overdueBorrowings as $borrowing) {
            $this->info("Marking borrowing #{$borrowing->id} (File: {$borrowing->file->reference_no}) as overdue");

            // Update borrowing status
            $borrowing->status = Borrowing::STATUS_OVERDUE;
            $borrowing->save();

            // Update file status using repository to ensure status log is created
            $this->fileRepository->markAsOverdue(
                $borrowing->file_id,
                1, // System user ID (assuming admin has ID 1)
                'Automatically marked as overdue by system'
            );
        }

        $this->info('Overdue check completed successfully.');
        return 0;
    }

    /**
     * Calculate business days between two dates (Monday to Friday).
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    protected function calculateBusinessDays(Carbon $startDate, Carbon $endDate): int
    {
        $days = 0;
        $current = $startDate->copy();

        while ($current->lt($endDate)) {
            $current->addDay();
            // Only count weekdays (1 = Monday, 5 = Friday)
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                $days++;
            }
        }

        return $days;
    }
}
