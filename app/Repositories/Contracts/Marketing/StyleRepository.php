<?php

namespace App\Repositories\Contracts\Marketing;
use App\Repositories\Contracts\MsRepository;

interface StyleRepository extends  MsRepository
{
	public function getAll();
}
