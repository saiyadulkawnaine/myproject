<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetTechImageRepository;
use App\Model\FAMS\AssetTechImage;
use App\Traits\Eloquent\MsTraits; 
class AssetTechImageImplementation implements AssetTechImageRepository
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
	public function __construct(AssetTechImage $model)
	{
		$this->model = $model;
	}
	
	
}