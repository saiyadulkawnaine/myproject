<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface WashChargeRepository extends  MsRepository
{
	public function getCharges();

}
