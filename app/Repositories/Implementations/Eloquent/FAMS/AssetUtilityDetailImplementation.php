<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetUtilityDetailRepository;
use App\Model\FAMS\AssetUtilityDetail;
use App\Traits\Eloquent\MsTraits; 
class AssetUtilityDetailImplementation implements AssetUtilityDetailRepository
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
	public function __construct(AssetUtilityDetail $model)
	{
		$this->model = $model;
	}
	
	
}