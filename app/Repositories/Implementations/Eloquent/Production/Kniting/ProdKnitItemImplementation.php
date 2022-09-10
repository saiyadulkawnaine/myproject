<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Model\Production\Kniting\ProdKnitItem;
use App\Traits\Eloquent\MsTraits;

class ProdKnitItemImplementation implements ProdKnitItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdKnitItem $model)
	{
		$this->model = $model;
	}
}
