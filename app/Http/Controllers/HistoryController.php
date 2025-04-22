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

    public function index()
    {
        return view('history.index');
    }
}
