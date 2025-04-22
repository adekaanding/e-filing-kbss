<?php

namespace App\Repositories\Interfaces;

interface BorrowingRepositoryInterface extends RepositoryInterface
{
    public function getActiveBorrowings();
    public function getReturnedBorrowings();
    public function getOverdueBorrowings();
    public function getByBorrower($borrowerName);
    public function registerBorrowing($fileId, $borrowerName, $officerId);
    public function registerReturn($id);
    public function getFilteredBorrowings($search = null, $status = null);
    public function markOverdueBorrowings($daysThreshold = 7);
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
    );
}
