<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetTechFileUploadRepository;
use App\Model\FAMS\AssetTechFileUpload;
use App\Traits\Eloquent\MsTraits; 
class AssetTechFileUploadImplementation implements AssetTechFileUploadRepository
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
	public function __construct(AssetTechFileUpload $model)
	{
		$this->model = $model;
	}
	
	
}