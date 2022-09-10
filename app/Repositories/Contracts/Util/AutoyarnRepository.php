<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface AutoyarnRepository extends  MsRepository
{
     public function getConstructinComposition();
	 public function getConstruction();
	 public function getComposition();
}
