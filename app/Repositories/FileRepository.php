<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    public function __construct(File $model)
    {
        parent::__construct($model);
    }

    public function getAvailableFiles()
    {
        return $this->model->available()->get();
    }

    public function getBorrowedFiles()
    {
        return $this->model->borrowed()->get();
    }

    public function getOverdueFiles()
    {
        return $this->model->overdue()->get();
    }

    public function getFilesByDepartment($departmentId)
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    /**
     * Search files with optional filtering.
     */
    public function searchFiles($search = null, $departmentId = null, $status = null)
    {
        $query = $this->model->with('department');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate(10);
    }
}
