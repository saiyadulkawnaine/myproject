<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransOtherRefRepository;
use App\Model\Account\AccTransOtherRef;
use App\Traits\Eloquent\MsTraits; 
class AccTransOtherRefImplementation implements AccTransOtherRefRepository
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
	public function __construct(AccTransOtherRef $model)
	{
		$this->model = $model;
	}
	
	
}
