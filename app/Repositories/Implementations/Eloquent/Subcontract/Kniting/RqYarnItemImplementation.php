<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnItemRepository;
use App\Model\Subcontract\Kniting\RqYarnItem;
use App\Traits\Eloquent\MsTraits;
class RqYarnItemImplementation implements RqYarnItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(RqYarnItem $model)
	{
		$this->model = $model;
	}
}
