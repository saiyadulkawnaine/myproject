<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;
use App\Repositories\Contracts\Production\AOP\ProdAopMcDateRepository;
use App\Model\Production\AOP\ProdAopMcDate;
use App\Traits\Eloquent\MsTraits; 
class ProdAopMcDateImplementation implements ProdAopMcDateRepository
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
	public function __construct(ProdAopMcDate $model)
	{
		$this->model = $model;
	}
	
}
