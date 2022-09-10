<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzDeductRepository;
use App\Model\Commercial\Export\ExpProRlzDeduct;
use App\Traits\Eloquent\MsTraits; 
class ExpProRlzDeductImplementation implements ExpProRlzDeductRepository
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
	public function __construct(ExpProRlzDeduct $model)
	{
		$this->model = $model;
	}
	
	
}