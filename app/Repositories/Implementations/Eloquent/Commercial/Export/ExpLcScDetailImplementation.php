<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcScDetailRepository;
use App\Model\Commercial\Export\ExpLcScDetail;
use App\Traits\Eloquent\MsTraits; 
class ExpLcScDetailImplementation implements ExpLcScDetailRepository
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
	public function __construct(ExpLcScDetail $model)
	{
		$this->model = $model;
	}
	
	
}