<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemPoItemRepository;
use App\Model\Production\Kniting\ProdKnitItemPoItem;
use App\Traits\Eloquent\MsTraits;

class ProdKnitItemPoItemImplementation implements ProdKnitItemPoItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitRefPoItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdKnitItemPoItem $model)
	{
		$this->model = $model;
	}
}
