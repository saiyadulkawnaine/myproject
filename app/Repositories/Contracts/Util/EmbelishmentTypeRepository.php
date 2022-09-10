<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface EmbelishmentTypeRepository extends  MsRepository
{
   public function getAopTypes();
   public function getEmbelishmentTypes();
}
