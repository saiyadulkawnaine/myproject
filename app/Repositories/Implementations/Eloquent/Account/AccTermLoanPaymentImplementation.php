<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTermLoanPaymentRepository;
use App\Model\Account\AccTermLoanPayment;
use App\Traits\Eloquent\MsTraits; 
class AccTermLoanPaymentImplementation implements AccTermLoanPaymentRepository
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
	public function __construct(AccTermLoanPayment $model)
	{
		$this->model = $model;
	}
	
}
