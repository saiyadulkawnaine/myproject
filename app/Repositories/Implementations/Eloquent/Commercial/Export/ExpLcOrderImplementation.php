<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcOrderRepository;
use App\Model\Commercial\Export\ExpLcOrder;
use App\Traits\Eloquent\MsTraits; 
class ExpLcOrderImplementation implements ExpLcOrderRepository
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
	public function __construct(ExpLcOrder $model)
	{
		$this->model = $model;
	}
	
	
}