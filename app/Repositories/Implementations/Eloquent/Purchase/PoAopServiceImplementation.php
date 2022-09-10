<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Model\Purchase\PoAopService;
use App\Traits\Eloquent\MsTraits;
class PoAopServiceImplementation implements PoAopServiceRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoAopServiceImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoAopService $model
	 */
	public function __construct(PoAopService $model)
	{
		$this->model = $model;
	}
}
