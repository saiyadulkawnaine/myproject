<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetMaintenanceRepository;
use App\Model\FAMS\AssetMaintenance;
use App\Traits\Eloquent\MsTraits; 
class AssetMaintenanceImplementation implements AssetMaintenanceRepository
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
	public function __construct(AssetMaintenance $model)
	{
		$this->model = $model;
	}
	
	
}