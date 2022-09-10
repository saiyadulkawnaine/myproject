<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Model\Account\AccTermLoanInstallment;
use App\Traits\Eloquent\MsTraits; 
class AccTermLoanInstallmentImplementation implements AccTermLoanInstallmentRepository
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
	public function __construct(AccTermLoanInstallment $model)
	{
		$this->model = $model;
	}
	
}
