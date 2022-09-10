<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzRepository;
use App\Model\Commercial\Export\ExpProRlz;
use App\Traits\Eloquent\MsTraits; 
class ExpProRlzImplementation implements ExpProRlzRepository
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
	public function __construct(ExpProRlz $model)
	{
		$this->model = $model;
	}
	
	
}