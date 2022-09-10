<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemNarrowfabricRepository;
use App\Model\Subcontract\Kniting\PlKnitItemNarrowfabric;
use App\Traits\Eloquent\MsTraits;
class PlKnitItemNarrowfabricImplementation implements PlKnitItemNarrowfabricRepository
     
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitItemNarrowfabricImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PlKnitItemNarrowfabric $model)
	{
		$this->model = $model;
	}
}
