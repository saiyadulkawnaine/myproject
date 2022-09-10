<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpOtherShipDetailRepository;
use App\Model\Commercial\Export\ExpOtherShipDetail;
use App\Traits\Eloquent\MsTraits; 
class ExpOtherShipDetailImplementation implements ExpOtherShipDetailRepository
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
	public function __construct(ExpOtherShipDetail $model)
	{
		$this->model = $model;
	}
	
	
}