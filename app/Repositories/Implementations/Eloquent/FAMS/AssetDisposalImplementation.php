<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Model\FAMS\AssetDisposal;
use App\Traits\Eloquent\MsTraits; 
class AssetDisposalImplementation implements AssetDisposalRepository
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
	public function __construct(AssetDisposal $model)
	{
		$this->model = $model;
	}
	
	
}