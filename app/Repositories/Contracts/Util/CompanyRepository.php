<?php
 
namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;
 
interface CompanyRepository extends  MsRepository
{
	public function get();
}