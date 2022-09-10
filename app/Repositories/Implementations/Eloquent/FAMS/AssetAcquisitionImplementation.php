<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Model\FAMS\AssetAcquisition;
use App\Traits\Eloquent\MsTraits; 
class AssetAcquisitionImplementation implements AssetAcquisitionRepository
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
	public function __construct(AssetAcquisition $model)
	{
		$this->model = $model;
	}
	
	
}