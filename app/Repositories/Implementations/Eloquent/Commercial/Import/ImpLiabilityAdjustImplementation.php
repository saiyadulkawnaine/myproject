<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustRepository;
use App\Model\Commercial\Import\ImpLiabilityAdjust;
use App\Traits\Eloquent\MsTraits; 

class ImpLiabilityAdjustImplementation implements ImpLiabilityAdjustRepository
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
	public function __construct(ImpLiabilityAdjust $model)
	{
		$this->model = $model;
	}
}