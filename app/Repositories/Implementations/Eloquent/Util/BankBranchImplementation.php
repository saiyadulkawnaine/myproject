<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Model\Util\BankBranch;
use App\Traits\Eloquent\MsTraits; 
class BankBranchImplementation implements BankBranchRepository
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
	public function __construct(BankBranch $model)
	{
		$this->model = $model;
	}
	
	
}