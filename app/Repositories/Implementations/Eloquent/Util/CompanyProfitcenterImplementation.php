<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompanyProfitcenterRepository;
use App\Model\Util\CompanyProfitcenter;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class CompanyProfitcenterImplementation implements CompanyProfitcenterRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(CompanyProfitcenter $model)
	{
		$this->model = $model;
	}
}