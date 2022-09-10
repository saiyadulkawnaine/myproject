<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTermPaymentRepository;
use App\Model\Account\AccTermPayment;
use App\Traits\Eloquent\MsTraits; 
class AccTermPaymentImplementation implements AccTermPaymentRepository
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
	public function __construct(AccTermPayment $model)
	{
		$this->model = $model;
	}
	
}
