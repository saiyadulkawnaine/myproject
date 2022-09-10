<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransPurchaseRepository;
use App\Model\Account\AccTransPurchase;
use App\Traits\Eloquent\MsTraits; 
class AccTransPurchaseImplementation implements AccTransPurchaseRepository
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
	public function __construct(AccTransPurchase $model)
	{
		$this->model = $model;
	}
	
	
}
