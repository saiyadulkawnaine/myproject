<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Model\Purchase\PoEmbService;
use App\Traits\Eloquent\MsTraits;
class PoEmbServiceImplementation implements PoEmbServiceRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoEmbServiceImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoEmbService $model
	 */
	public function __construct(PoEmbService $model)
	{
		$this->model = $model;
	}
}
