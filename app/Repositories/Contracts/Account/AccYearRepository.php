<?php

namespace App\Repositories\Contracts\Account;
use App\Repositories\Contracts\MsRepository;

interface AccYearRepository extends  MsRepository
{
    public function getBycompany($company_id);
}
