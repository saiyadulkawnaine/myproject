<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopTargetRepository;
use App\Model\Subcontract\AOP\SoAopTarget;
use App\Traits\Eloquent\MsTraits;

class SoAopTargetImplementation implements SoAopTargetRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoAopTargetImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopTarget $model)
	{
		$this->model = $model;
	}
}
