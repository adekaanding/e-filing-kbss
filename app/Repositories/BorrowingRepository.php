<?php

namespace App\Repositories;

use App\Models\Borrowing;
use App\Models\File;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use Carbon\Carbon;

class BorrowingRepository extends BaseRepository implements BorrowingRepositoryInterface
{
    public function __construct(Borrowing $model)
    {
        parent::__construct($model);
    }

    public function getActiveBorrowings()
    {
        return $this->model->where('status', 'Dalam Pinjaman')->get();
    }

    public function getReturnedBorrowings()
    {
        return $this->model->where('status', 'Dikembalikan')->get();
    }

    public function getOverdueBorrowings()
    {
        return $this->model->where('status', 'Belum Dikembalikan')->get();
    }

    public function getByBorrower($borrowerName)
    {
        return $this->model->where('borrower_name', 'like', "%{$borrowerName}%")->get();
    }

    public function registerBorrowing($fileId, $borrowerName, $officerId)
    {
        $borrowing = $this->model->create([
            'file_id' => $fileId,
            'borrower_name' => $borrowerName,
            'borrow_date' => Carbon::now(),
            'officer_id' => $officerId,
            'status' => 'Dalam Pinjaman'
        ]);

        // Update file status
        File::where('id', $fileId)->update(['status' => 'Dalam Pinjaman']);

        return $borrowing;
    }

    public function registerReturn($id)
    {
        $borrowing = $this->find($id);
        $borrowing->update([
            'return_date' => Carbon::now(),
            'status' => 'Dikembalikan'
        ]);

        // Update file status
        File::where('id', $borrowing->file_id)->update(['status' => 'Available']);

        return $borrowing;
    }

    public function getFilteredBorrowings($search = null, $status = null)
    {
        $query = $this->model->with(['file.department', 'officer']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                    ->orWhereHas('file', function ($fileQuery) use ($search) {
                        $fileQuery->where('reference_no', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('borrow_date', 'desc')->paginate(10);
    }

    /**
     * Mark borrowings as overdue based on specified threshold.
     *
     * @param int $daysThreshold
     * @return array
     */
    public function markOverdueBorrowings($daysThreshold = 7)
    {
        $results = ['marked' => 0, 'total' => 0];

        // Get active borrowings
        $activeBorrowings = $this->getActiveBorrowings();
        $results['total'] = $activeBorrowings->count();

        foreach ($activeBorrowings as $borrowing) {
            if ($borrowing->status === 'Dalam Pinjaman') {
                // Calculate business days since borrowing
                $businessDays = $this->calculateBusinessDays($borrowing->borrow_date);

                if ($businessDays > $daysThreshold) {
                    // Update borrowing status
                    $borrowing->status = 'Belum Dikembalikan';
                    $borrowing->save();

                    $results['marked']++;
                }
            }
        }

        return $results;
    }

    /**
     * Calculate business days (Monday-Friday) between a date and now.
     *
     * @param \Carbon\Carbon $fromDate
     * @return int
     */
    private function calculateBusinessDays($fromDate)
    {
        $now = now();
        $days = 0;
        $current = $fromDate->copy();

        while ($current->lt($now)) {
            $current->addDay();
            // Only count weekdays (1 = Monday, 5 = Friday)
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                $days++;
            }
        }

        return $days;
    }
    /**
     * Get borrowings with advanced filtering and sorting options.
     *
     * @param string|null $search
     * @param string|null $status
     * @param int|null $departmentId
     * @param \Carbon\Carbon|null $startDate
     * @param \Carbon\Carbon|null $endDate
     * @param string $sortField
     * @param string $sortDirection
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdvancedFilteredBorrowings(
        $search = null,
        $status = null,
        $departmentId = null,
        $startDate = null,
        $endDate = null,
        $sortField = 'borrow_date',
        $sortDirection = 'desc'
    ) {
        $query = $this->model->with(['file.department', 'officer']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                    ->orWhereHas('file', function ($fileQuery) use ($search) {
                        $fileQuery->where('reference_no', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Department filter
        if ($departmentId) {
            $query->whereHas('file', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        // Date range filters
        if ($startDate) {
            $query->where('borrow_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('borrow_date', '<=', $endDate);
        }

        // Handle sorting
        // Validate sortField to prevent SQL injection
        $allowedSortFields = ['borrower_name', 'borrow_date', 'return_date', 'status'];

        if ($sortField === 'file_id') {
            $query->join('files', 'borrowings.file_id', '=', 'files.id')
                ->orderBy('files.reference_no', $sortDirection)
                ->select('borrowings.*'); // Ensure we only select borrowings columns
        } else {
            $sortField = in_array($sortField, $allowedSortFields) ? $sortField : 'borrow_date';
            $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortField, $sortDirection);
        }

        return $query->paginate(10);
    }
}
