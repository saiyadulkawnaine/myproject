<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeTransferRepository;
use App\Model\HRM\EmployeeTransfer;
use App\Traits\Eloquent\MsTraits; 
class EmployeeTransferImplementation implements EmployeeTransferRepository
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
	public function __construct(EmployeeTransfer $model)
	{
		$this->model = $model;
	}
	
	
}