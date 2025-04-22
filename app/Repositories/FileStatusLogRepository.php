<?php

namespace App\Repositories;

use App\Models\FileStatusLog;
use App\Repositories\Interfaces\FileStatusLogRepositoryInterface;

class FileStatusLogRepository extends BaseRepository implements FileStatusLogRepositoryInterface
{
    public function __construct(FileStatusLog $model)
    {
        parent::__construct($model);
    }

    public function getLogsByFile($fileId)
    {
        return $this->model->where('file_id', $fileId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
