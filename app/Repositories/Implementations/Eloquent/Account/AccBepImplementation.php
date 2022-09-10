<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Model\Account\AccBep;
use App\Traits\Eloquent\MsTraits; 
class AccBepImplementation implements AccBepRepository
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
	public function __construct(AccBep $model)
	{
		$this->model = $model;
	}
	
}
