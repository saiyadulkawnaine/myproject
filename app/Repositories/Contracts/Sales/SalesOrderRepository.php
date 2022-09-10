<?php

namespace App\Repositories\Contracts\Sales;
use App\Repositories\Contracts\MsRepository;

interface SalesOrderRepository extends  MsRepository
{
  function getAll();
}
