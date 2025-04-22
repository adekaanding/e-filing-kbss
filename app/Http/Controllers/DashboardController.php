<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use App\Models\Borrowing;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $fileRepository;
    protected $borrowingRepository;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        BorrowingRepositoryInterface $borrowingRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->borrowingRepository = $borrowingRepository;
    }

    public function index()
    {
        // Get file counts by status
        $availableCount = $this->fileRepository->getAvailableFiles()->count();
        $borrowedCount = $this->fileRepository->getBorrowedFiles()->count();
        $overdueCount = $this->fileRepository->getOverdueFiles()->count();

        // Get recent borrowings with pagination
        $recentBorrowings = Borrowing::with(['file.department', 'officer'])
            ->orderBy('borrow_date', 'desc')
            ->limit(10)
            ->get();

        // Calculate days overdue for each active borrowing
        foreach ($recentBorrowings as $borrowing) {
            if ($borrowing->status === Borrowing::STATUS_OVERDUE) {
                $borrowing->days_overdue = $this->calculateBusinessDays($borrowing->borrow_date, now());
            }
        }

        return view('dashboard.index', compact(
            'availableCount',
            'borrowedCount',
            'overdueCount',
            'recentBorrowings'
        ));
    }

    /**
     * Calculate business days (Monday-Friday) between two dates.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    private function calculateBusinessDays($startDate, $endDate)
    {
        $days = 0;
        $current = $startDate->copy();
        $threshold = 7; // 7 business days threshold

        while ($current->lt($endDate)) {
            $current->addDay();
            // Only count weekdays (1 = Monday, 5 = Friday)
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                $days++;
            }
        }

        return max(0, $days - $threshold);
    }
}
