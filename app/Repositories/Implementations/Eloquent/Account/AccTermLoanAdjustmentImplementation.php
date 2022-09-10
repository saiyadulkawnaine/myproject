<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTermLoanAdjustmentRepository;
use App\Model\Account\AccTermLoanAdjustment;
use App\Traits\Eloquent\MsTraits; 
class AccTermLoanAdjustmentImplementation implements AccTermLoanAdjustmentRepository
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
	public function __construct(AccTermLoanAdjustment $model)
	{
		$this->model = $model;
	}
	
}
