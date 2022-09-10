<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Model\FAMS\AssetQuantityCost;
use App\Traits\Eloquent\MsTraits; 
class AssetQuantityCostImplementation implements AssetQuantityCostRepository
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
	public function __construct(AssetQuantityCost $model)
	{
		$this->model = $model;
	}
	
	
}