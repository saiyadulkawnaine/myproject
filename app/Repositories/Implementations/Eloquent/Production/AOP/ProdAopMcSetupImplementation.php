<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;
use App\Repositories\Contracts\Production\AOP\ProdAopMcSetupRepository;
use App\Model\Production\AOP\ProdAopMcSetup;
use App\Traits\Eloquent\MsTraits; 
class ProdAopMcSetupImplementation implements ProdAopMcSetupRepository
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
	public function __construct(ProdAopMcSetup $model)
	{
		$this->model = $model;
	}
	
}