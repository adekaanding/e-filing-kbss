<?php

namespace App\Repositories\Interfaces;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function getAvailableFiles();
    public function getBorrowedFiles();
    public function getOverdueFiles();
    public function getFilesByDepartment($departmentId);
}
