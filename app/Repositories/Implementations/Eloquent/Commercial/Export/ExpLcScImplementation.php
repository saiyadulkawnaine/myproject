<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Model\Commercial\Export\ExpLcSc;
use App\Traits\Eloquent\MsTraits; 
class ExpLcScImplementation implements ExpLcScRepository
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
	public function __construct(ExpLcSc $model)
	{
		$this->model = $model;
	}
	
	
}