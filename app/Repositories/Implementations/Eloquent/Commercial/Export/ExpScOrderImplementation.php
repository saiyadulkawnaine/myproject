<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpScOrderRepository;
use App\Model\Commercial\Export\ExpScOrder;
use App\Traits\Eloquent\MsTraits; 
class ExpScOrderImplementation implements ExpScOrderRepository
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
	public function __construct(ExpScOrder $model)
	{
		$this->model = $model;
	}
	
	
}