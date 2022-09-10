<?php

namespace App\Repositories\Contracts\Sales;
use App\Repositories\Contracts\MsRepository;

interface SalesOrderCountryRepository extends  MsRepository
{
  function getAll();
	  
}
