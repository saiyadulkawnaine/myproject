<?php

namespace App\Repositories\Contracts\Sales;
use App\Repositories\Contracts\MsRepository;

interface SalesOrderItemRepository extends  MsRepository
{
  function getAll();
  function getById($id);
}
