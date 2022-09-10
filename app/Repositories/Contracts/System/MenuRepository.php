<?php
 
namespace App\Repositories\Contracts\System;
use App\Repositories\Contracts\MsRepository;
 
interface MenuRepository extends  MsRepository
{
	function getTree($root_title,$mode='html',$check=false);
	
}