<?php

namespace App\Repositories\Contracts\Bom;
use App\Repositories\Contracts\MsRepository;

interface BudgetRepository extends MsRepository
{
     public function getAll();
}
