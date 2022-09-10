<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemStripeRepository;
use App\Model\Subcontract\Kniting\PlKnitItemStripe;
use App\Traits\Eloquent\MsTraits;
class PlKnitItemStripeImplementation implements PlKnitItemStripeRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitItemStripeImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PlKnitItemStripe $model)
	{
		$this->model = $model;
	}
}
