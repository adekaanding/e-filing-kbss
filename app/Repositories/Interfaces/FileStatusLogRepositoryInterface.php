<?php

namespace App\Repositories\Interfaces;

interface FileStatusLogRepositoryInterface extends RepositoryInterface
{
    public function getLogsByFile($fileId);
}
