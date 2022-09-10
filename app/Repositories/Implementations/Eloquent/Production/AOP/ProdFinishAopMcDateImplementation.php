<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;
use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcDateRepository;
use App\Model\Production\AOP\ProdFinishAopMcDate;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishAopMcDateImplementation implements ProdFinishAopMcDateRepository
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
	public function __construct(ProdFinishAopMcDate $model)
	{
		$this->model = $model;
	}
	
}
