<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Model\Purchase\PoDyeChem;
use App\Traits\Eloquent\MsTraits;
class PoDyeChemImplementation implements PoDyeChemRepository
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
	public function __construct(PoDyeChem $model)
	{
		$this->model = $model;
	}
}
