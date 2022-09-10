<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;
use App\Repositories\Contracts\Production\AOP\ProdAopMcParameterRepository;
use App\Model\Production\AOP\ProdAopMcParameter;
use App\Traits\Eloquent\MsTraits; 
class ProdAopMcParameterImplementation implements ProdAopMcParameterRepository
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
	public function __construct(ProdAopMcParameter $model)
	{
		$this->model = $model;
	}
	
}
