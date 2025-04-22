<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use Carbon\Carbon;

class HistoryController extends Controller
{
    protected $borrowingRepository;

    public function __construct(BorrowingRepositoryInterface $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $borrowings = $this->borrowingRepository->getFilteredBorrowings($search, $status);

        // Calculate days overdue for each borrowing
        foreach ($borrowings as $borrowing) {
            if ($borrowing->status === 'Belum Dikembalikan') {
                // Calculate business days overdue
                $borrowing->days_overdue = $this->calculateBusinessDaysOverdue($borrowing->borrow_date);
            } elseif ($borrowing->status === 'Dikembalikan' && $borrowing->return_date) {
                // Calculate if it was returned late
                $borrowing->days_late = $this->calculateReturnDelay($borrowing->borrow_date, $borrowing->return_date);
            }
        }

        return view('history.index', compact('borrowings', 'status'));
    }

    /**
     * Calculate business days overdue (days exceeding 7-day threshold).
     *
     * @param Carbon $borrowDate
     * @return int
     */
    private function calculateBusinessDaysOverdue($borrowDate)
    {
        $businessDays = $this->calculateBusinessDaysBetween($borrowDate, now());
        return max(0, $businessDays - 7); // Subtract 7-day threshold
    }

    /**
     * Calculate if a file was returned late (after 7 business days).
     *
     * @param Carbon $borrowDate
     * @param Carbon $returnDate
     * @return int
     */
    private function calculateReturnDelay($borrowDate, $returnDate)
    {
        $businessDays = $this->calculateBusinessDaysBetween($borrowDate, $returnDate);
        return max(0, $businessDays - 7); // Subtract 7-day threshold
    }

    /**
     * Calculate business days between two dates.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    private function calculateBusinessDaysBetween($startDate, $endDate)
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
