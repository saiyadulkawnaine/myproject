<?php

namespace App\Repositories\Contracts\Sales;
use App\Repositories\Contracts\MsRepository;

interface ProjectionCountryRepository extends  MsRepository
{
  function getAll();
	  
}
