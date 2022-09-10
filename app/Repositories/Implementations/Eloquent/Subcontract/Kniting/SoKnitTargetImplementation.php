<?php
 
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitTargetRepository;
use App\Model\Subcontract\Kniting\SoKnitTarget;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class SoKnitTargetImplementation implements SoKnitTargetRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(SoKnitTarget $model)
	{
		$this->model = $model;
	}
}