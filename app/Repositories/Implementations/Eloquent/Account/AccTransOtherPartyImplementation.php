<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransOtherPartyRepository;
use App\Model\Account\AccTransOtherParty;
use App\Traits\Eloquent\MsTraits; 
class AccTransOtherPartyImplementation implements AccTransOtherPartyRepository
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
	public function __construct(AccTransOtherParty $model)
	{
		$this->model = $model;
	}
	
	
}
