<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Model\FAMS\AssetBreakdown;
use App\Traits\Eloquent\MsTraits; 
class AssetBreakdownImplementation implements AssetBreakdownRepository
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
	public function __construct(AssetBreakdown $model)
	{
		$this->model = $model;
	}
	
	
}