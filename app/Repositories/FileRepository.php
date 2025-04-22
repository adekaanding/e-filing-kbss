<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\FileStatusLog;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            // Add debug log here
            \Log::debug("Searching for: " . $search);

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

    /**
     * Change the status of a file and log the change.
     */
    public function changeStatus($id, $newStatus, $userId, $notes = null)
    {
        $file = $this->find($id);
        $oldStatus = $file->status;

        // Only proceed if the status is actually changing
        if ($oldStatus !== $newStatus) {
            DB::transaction(function () use ($file, $oldStatus, $newStatus, $userId, $notes) {
                // Update the file status
                $file->status = $newStatus;
                $file->save();

                // Create a log entry
                FileStatusLog::create([
                    'file_id' => $file->id,
                    'user_id' => $userId,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'notes' => $notes
                ]);
            });
        }

        return $file;
    }

    /**
     * Mark a file as available.
     */
    public function markAsAvailable($id, $userId, $notes = null)
    {
        return $this->changeStatus($id, File::STATUS_AVAILABLE, $userId, $notes);
    }

    /**
     * Mark a file as borrowed.
     */
    public function markAsBorrowed($id, $userId, $notes = null)
    {
        return $this->changeStatus($id, File::STATUS_BORROWED, $userId, $notes);
    }

    /**
     * Mark a file as overdue.
     */
    public function markAsOverdue($id, $userId, $notes = null)
    {
        return $this->changeStatus($id, File::STATUS_OVERDUE, $userId, $notes);
    }

    /**
     * Get the status history of a file.
     */
    public function getStatusHistory($id)
    {
        $file = $this->find($id);
        return $file->statusLogs()->with('user')->orderBy('created_at', 'desc')->get();
    }
}
