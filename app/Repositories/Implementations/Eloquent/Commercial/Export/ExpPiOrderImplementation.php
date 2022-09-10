<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpPiOrderRepository;
use App\Model\Commercial\Export\ExpPiOrder;
use App\Traits\Eloquent\MsTraits; 
class ExpPiOrderImplementation implements ExpPiOrderRepository
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
	public function __construct(ExpPiOrder $model)
	{
		$this->model = $model;
	}
	
	
}