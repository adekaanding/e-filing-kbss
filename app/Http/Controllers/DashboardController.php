<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;

class DashboardController extends Controller
{
    protected $fileRepository;
    protected $borrowingRepository;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        BorrowingRepositoryInterface $borrowingRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->borrowingRepository = $borrowingRepository;
    }

    public function index()
    {
        return view('dashboard.index');
    }
}
