<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Model\Account\AccTermLoan;
use App\Traits\Eloquent\MsTraits; 
class AccTermLoanImplementation implements AccTermLoanRepository
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
	public function __construct(AccTermLoan $model)
	{
		$this->model = $model;
	}
	
}
