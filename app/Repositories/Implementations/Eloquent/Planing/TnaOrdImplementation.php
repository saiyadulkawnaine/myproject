<?php
namespace App\Repositories\Implementations\Eloquent\Planing;

use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Model\Planing\TnaOrd;
use App\Traits\Eloquent\MsTraits;

class TnaOrdImplementation implements TnaOrdRepository
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
	public function __construct(TnaOrd $model)
	{
		$this->model = $model;
	}
}
