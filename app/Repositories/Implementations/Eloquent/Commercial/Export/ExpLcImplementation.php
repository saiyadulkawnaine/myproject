<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcRepository;
use App\Model\Commercial\Export\ExpLc;
use App\Traits\Eloquent\MsTraits; 
class ExpLcImplementation implements ExpLcRepository
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
	public function __construct(ExpLc $model)
	{
		$this->model = $model;
	}
	
	
}