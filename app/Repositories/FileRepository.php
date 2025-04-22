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
}
