<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcSetupRepository;
use App\Model\Production\Dyeing\ProdFinishMcSetup;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishMcSetupImplementation implements ProdFinishMcSetupRepository
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
	public function __construct(ProdFinishMcSetup $model)
	{
		$this->model = $model;
	}
	
}
