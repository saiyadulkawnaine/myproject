<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccTermInstallmentRepository;
use App\Model\Account\AccTermInstallment;
use App\Traits\Eloquent\MsTraits; 
class AccTermInstallmentImplementation implements AccTermInstallmentRepository
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
	public function __construct(AccTermInstallment $model)
	{
		$this->model = $model;
	}
	
}
