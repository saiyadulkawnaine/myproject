<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\OperationRepository;
use App\Model\Util\Operation;
use App\Traits\Eloquent\MsTraits; 
class OperationImplementation implements OperationRepository
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
	public function __construct(Operation $model)
	{
		$this->model = $model;
	}
}