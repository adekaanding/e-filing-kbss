<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    protected $borrowingRepository;
    protected $fileRepository;

    public function __construct(
        BorrowingRepositoryInterface $borrowingRepository,
        FileRepositoryInterface $fileRepository
    ) {
        $this->borrowingRepository = $borrowingRepository;
        $this->fileRepository = $fileRepository;
    }

    public function create()
    {
        // Load departments for the dropdown
        $departments = Department::all();
        return view('borrowings.form', compact('departments'));
    }

    /**
     * Store a newly created borrowing record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:files,id'
        ]);

        $borrowerName = $request->borrower_name;
        $fileIds = $request->file_ids;
        $officerId = Auth::id();

        DB::beginTransaction();

        try {
            foreach ($fileIds as $fileId) {
                // Check if file is available
                $file = $this->fileRepository->find($fileId);

                if (!$file->isAvailable()) {
                    throw new \Exception("File with ID {$fileId} is not available for borrowing.");
                }

                // Register borrowing
                $this->borrowingRepository->registerBorrowing($fileId, $borrowerName, $officerId);
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Files borrowed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
