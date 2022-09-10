<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcScReviseRepository;
use App\Model\Commercial\Export\ExpLcScRevise;
use App\Traits\Eloquent\MsTraits; 
class ExpLcScReviseImplementation implements ExpLcScReviseRepository
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
	public function __construct(ExpLcScRevise $model)
	{
		$this->model = $model;
	}	

}