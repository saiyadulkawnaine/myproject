<?php
namespace App\Repositories\Implementations\Eloquent\Planing;

use App\Repositories\Contracts\Planing\TnaProgressDelayRepository;
use App\Model\Planing\TnaProgressDelay;
use App\Traits\Eloquent\MsTraits;

class TnaProgressDelayImplementation implements TnaProgressDelayRepository
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
	public function __construct(TnaProgressDelay $model)
	{
		$this->model = $model;
	}
}
