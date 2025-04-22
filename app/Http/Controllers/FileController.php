<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;

class FileController extends Controller
{
    protected $fileRepository;
    protected $departmentRepository;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        DepartmentRepositoryInterface $departmentRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function index()
    {
        return view('files.index');
    }
}
