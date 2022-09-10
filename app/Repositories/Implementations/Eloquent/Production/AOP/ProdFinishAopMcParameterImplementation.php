<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;
use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcParameterRepository;
use App\Model\Production\AOP\ProdFinishAopMcParameter;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishAopMcParameterImplementation implements ProdFinishAopMcParameterRepository
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
	public function __construct(ProdFinishAopMcParameter $model)
	{
		$this->model = $model;
	}
	
}
