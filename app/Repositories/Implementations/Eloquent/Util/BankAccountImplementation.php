<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Model\Util\BankAccount;
use App\Traits\Eloquent\MsTraits; 
class BankAccountImplementation implements BankAccountRepository
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
	public function __construct(BankAccount $model)
	{
		$this->model = $model;
	}
	
	
}