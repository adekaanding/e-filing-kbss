<?php

namespace App\Repositories\Interfaces;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function getAvailableFiles();
    public function getBorrowedFiles();
    public function getOverdueFiles();
    public function getFilesByDepartment($departmentId);
    public function searchFiles($search, $departmentId, $status);

    public function changeStatus($id, $newStatus, $userId, $notes = null);
    public function markAsAvailable($id, $userId, $notes = null);
    public function markAsBorrowed($id, $userId, $notes = null);
    public function markAsOverdue($id, $userId, $notes = null);
    public function getStatusHistory($id);
}
