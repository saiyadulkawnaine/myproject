<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransChldRepository;
use App\Model\Account\AccTransChld;
use App\Traits\Eloquent\MsTraits; 
class AccTransChldImplementation implements AccTransChldRepository
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
	public function __construct(AccTransChld $model)
	{
		$this->model = $model;
	}
	
	
}
