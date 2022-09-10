<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransEmployeeRepository;
use App\Model\Account\AccTransEmployee;
use App\Traits\Eloquent\MsTraits; 
class AccTransEmployeeImplementation implements AccTransEmployeeRepository
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
	public function __construct(AccTransEmployee $model)
	{
		$this->model = $model;
	}
	
	
}
