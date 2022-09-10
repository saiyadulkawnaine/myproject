<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface ItemAccountRepository extends  MsRepository
{
	function getAccessories();

}
