<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Model\Subcontract\AOP\SoAop;
use App\Traits\Eloquent\MsTraits;

class SoAopImplementation implements SoAopRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoAopImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAop $model)
	{
		$this->model = $model;
	}
}
