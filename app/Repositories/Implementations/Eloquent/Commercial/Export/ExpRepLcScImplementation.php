<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpRepLcScRepository;
use App\Model\Commercial\Export\ExpRepLcSc;
use App\Traits\Eloquent\MsTraits; 
class ExpRepLcScImplementation implements ExpRepLcScRepository
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
	public function __construct(ExpRepLcSc $model)
	{
		$this->model = $model;
	}
	
	
}