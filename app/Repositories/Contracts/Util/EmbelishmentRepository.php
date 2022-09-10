<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface EmbelishmentRepository extends  MsRepository
{
  public  function getEmbelishments();
}
