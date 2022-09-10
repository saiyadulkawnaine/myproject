<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;
use App\Model\FAMS\AssetServiceRepair;
use App\Traits\Eloquent\MsTraits; 
class AssetServiceRepairImplementation implements AssetServiceRepairRepository
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
	public function __construct(AssetServiceRepair $model)
	{
		$this->model = $model;
	}
	
	
}