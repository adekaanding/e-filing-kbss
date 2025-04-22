<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BorrowingRepositoryInterface;

class HistoryController extends Controller
{
    protected $borrowingRepository;

    public function __construct(BorrowingRepositoryInterface $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $borrowings = $this->borrowingRepository->getFilteredBorrowings($search, $status);

        return view('history.index', compact('borrowings', 'status'));
    }
}
