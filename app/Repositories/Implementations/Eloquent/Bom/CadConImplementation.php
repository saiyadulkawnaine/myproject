<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\CadConRepository;
use App\Model\Bom\CadCon;
use App\Traits\Eloquent\MsTraits;
class CadConImplementation implements CadConRepository
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
	public function __construct(CadCon $model)
	{
		$this->model = $model;
	}
}
