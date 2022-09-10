<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Model\FAMS\AssetTechnicalFeature;
use App\Traits\Eloquent\MsTraits; 
class AssetTechnicalFeatureImplementation implements AssetTechnicalFeatureRepository
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
	public function __construct(AssetTechnicalFeature $model)
	{
		$this->model = $model;
	}
	
	
}