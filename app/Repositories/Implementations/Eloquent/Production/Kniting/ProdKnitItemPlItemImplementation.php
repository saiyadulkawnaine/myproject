<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemPlItemRepository;
use App\Model\Production\Kniting\ProdKnitItemPlItem;
use App\Traits\Eloquent\MsTraits;

class ProdKnitItemPlItemImplementation implements ProdKnitItemPlItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitRefPlItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdKnitItemPlItem $model)
	{
		$this->model = $model;
	}
}
