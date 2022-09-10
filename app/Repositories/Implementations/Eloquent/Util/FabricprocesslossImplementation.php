<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\FabricprocesslossRepository;
use App\Model\Util\Fabricprocessloss;
use App\Traits\Eloquent\MsTraits; 
class FabricprocesslossImplementation implements FabricprocesslossRepository
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
	public function __construct(Fabricprocessloss $model)
	{
		$this->model = $model;
	}
}