<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface ItemcategoryRepository extends  MsRepository
{
	/// @$identity type array.
    public function getForCombo($identity);
}
