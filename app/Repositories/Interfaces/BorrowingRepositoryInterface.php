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
}
