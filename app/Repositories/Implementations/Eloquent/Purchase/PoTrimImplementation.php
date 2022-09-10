<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Model\Purchase\PoTrim;
use App\Traits\Eloquent\MsTraits;
class PoTrimImplementation implements PoTrimRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoTrimsImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoTrim $model)
	{
		$this->model = $model;
	}
}
