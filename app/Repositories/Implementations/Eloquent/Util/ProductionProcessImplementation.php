<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Model\Util\ProductionProcess;
use App\Traits\Eloquent\MsTraits; 
class ProductionProcessImplementation implements ProductionProcessRepository
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
	public function __construct(ProductionProcess $model)
	{
		$this->model = $model;
	}
}