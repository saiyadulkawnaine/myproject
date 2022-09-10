<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BankRepository;
use App\Model\Util\Bank;
use App\Traits\Eloquent\MsTraits; 
class BankImplementation implements BankRepository
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
	public function __construct(Bank $model)
	{
		$this->model = $model;
	}
	
	
}