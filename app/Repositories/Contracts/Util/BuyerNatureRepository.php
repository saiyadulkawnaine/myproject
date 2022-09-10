<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface BuyerNatureRepository extends  MsRepository
{
   public function getBuyingHouses();
}
