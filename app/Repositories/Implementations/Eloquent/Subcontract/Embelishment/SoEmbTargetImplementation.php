<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbTargetRepository;
use App\Model\Subcontract\Embelishment\SoEmbTarget;
use App\Traits\Eloquent\MsTraits;

class SoEmbTargetImplementation implements SoEmbTargetRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoEmbTargetImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoEmbTarget $model)
	{
		$this->model = $model;
	}
}
