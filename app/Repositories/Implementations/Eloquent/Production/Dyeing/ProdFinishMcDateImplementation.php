<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcDateRepository;
use App\Model\Production\Dyeing\ProdFinishMcDate;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishMcDateImplementation implements ProdFinishMcDateRepository
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
	public function __construct(ProdFinishMcDate $model)
	{
		$this->model = $model;
	}
	
}
