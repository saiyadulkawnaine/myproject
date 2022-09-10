<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetServiceRepairPartRepository;
use App\Model\FAMS\AssetServiceRepairPart;
use App\Traits\Eloquent\MsTraits; 
class AssetServiceRepairPartImplementation implements AssetServiceRepairPartRepository
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
	public function __construct(AssetServiceRepairPart $model)
	{
		$this->model = $model;
	}
	
	
}