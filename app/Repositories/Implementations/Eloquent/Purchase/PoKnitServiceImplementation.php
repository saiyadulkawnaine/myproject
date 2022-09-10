<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Model\Purchase\PoKnitService;
use App\Traits\Eloquent\MsTraits;
class PoKnitServiceImplementation implements PoKnitServiceRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoKnitServiceImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoKnitService $model
	 */
	public function __construct(PoKnitService $model)
	{
		$this->model = $model;
	}
}
