<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpRepLcRepository;
use App\Model\Commercial\Export\ExpRepLc;
use App\Traits\Eloquent\MsTraits; 
class ExpRepLcImplementation implements ExpRepLcRepository
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
	public function __construct(ExpRepLc $model)
	{
		$this->model = $model;
	}
	
	
}