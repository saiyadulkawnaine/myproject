<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\FabricprocesslossPercentRepository;
use App\Model\Util\FabricprocesslossPercent;
use App\Traits\Eloquent\MsTraits; 
class FabricprocesslossPercentImplementation implements FabricprocesslossPercentRepository
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
	public function __construct(FabricprocesslossPercent $model)
	{
		$this->model = $model;
	}
}