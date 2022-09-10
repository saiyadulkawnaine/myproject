<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Model\Purchase\PoFabric;
use App\Traits\Eloquent\MsTraits;
class PoFabricImplementation implements PoFabricRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoFabric $model)
	{
		$this->model = $model;
	}
}
