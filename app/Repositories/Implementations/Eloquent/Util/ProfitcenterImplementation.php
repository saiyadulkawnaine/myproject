<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Model\Util\Profitcenter;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class ProfitcenterImplementation implements ProfitcenterRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(Profitcenter $model)
	{
		$this->model = $model;
	}
}