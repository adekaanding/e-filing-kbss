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
}
