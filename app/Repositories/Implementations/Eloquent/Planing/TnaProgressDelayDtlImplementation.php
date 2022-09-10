<?php
namespace App\Repositories\Implementations\Eloquent\Planing;

use App\Repositories\Contracts\Planing\TnaProgressDelayDtlRepository;
use App\Model\Planing\TnaProgressDelayDtl;
use App\Traits\Eloquent\MsTraits;

class TnaProgressDelayDtlImplementation implements TnaProgressDelayDtlRepository
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
	public function __construct(TnaProgressDelayDtl $model)
	{
		$this->model = $model;
	}
}
