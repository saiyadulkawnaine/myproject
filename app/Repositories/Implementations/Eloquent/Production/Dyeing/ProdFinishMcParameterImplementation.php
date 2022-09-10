<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcParameterRepository;
use App\Model\Production\Dyeing\ProdFinishMcParameter;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishMcParameterImplementation implements ProdFinishMcParameterRepository
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
	public function __construct(ProdFinishMcParameter $model)
	{
		$this->model = $model;
	}
	
}
