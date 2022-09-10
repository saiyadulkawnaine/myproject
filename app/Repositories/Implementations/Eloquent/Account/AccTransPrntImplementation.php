<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransPrntRepository;
use App\Model\Account\AccTransPrnt;
use App\Traits\Eloquent\MsTraits; 
class AccTransPrntImplementation implements AccTransPrntRepository
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
	public function __construct(AccTransPrnt $model)
	{
		$this->model = $model;
	}
	
	
}
