<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTransLoanRefRepository;
use App\Model\Account\AccTransLoanRef;
use App\Traits\Eloquent\MsTraits; 
class AccTransLoanRefImplementation implements AccTransLoanRefRepository
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
	public function __construct(AccTransLoanRef $model)
	{
		$this->model = $model;
	}
	
	
}
