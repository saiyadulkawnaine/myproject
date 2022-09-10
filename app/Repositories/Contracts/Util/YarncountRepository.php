<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface YarncountRepository extends  MsRepository
{
  function getForCombo();
}
