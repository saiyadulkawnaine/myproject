<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransSalesRepository;
use App\Model\Account\AccTransSales;
use App\Traits\Eloquent\MsTraits; 
class AccTransSalesImplementation implements AccTransSalesRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(AccTransSales $model)
	{
		$this->model = $model;
	}
	
	
}
