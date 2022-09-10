<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvRepository;
use App\Model\Subcontract\AOP\SoAopDlv;
use App\Traits\Eloquent\MsTraits;
class SoAopDlvImplementation implements SoAopDlvRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopDlv $model)
	{
		$this->model = $model;
	}
}
