<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;

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
        return view('borrowings.form');
    }
}
