<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustChldRepository;
use App\Model\Commercial\Import\ImpLiabilityAdjustChld;
use App\Traits\Eloquent\MsTraits; 

class ImpLiabilityAdjustChldImplementation implements ImpLiabilityAdjustChldRepository
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
	public function __construct(ImpLiabilityAdjustChld $model)
	{
		$this->model = $model;
	}
}