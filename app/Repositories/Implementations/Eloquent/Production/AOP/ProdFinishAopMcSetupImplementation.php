<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;
use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcSetupRepository;
use App\Model\Production\AOP\ProdFinishAopMcSetup;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishAopMcSetupImplementation implements ProdFinishAopMcSetupRepository
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
	public function __construct(ProdFinishAopMcSetup $model)
	{
		$this->model = $model;
	}
	
}