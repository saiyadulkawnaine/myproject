<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleFabricationStripeRepository;
use App\Model\Marketing\StyleFabricationStripe;
use App\Traits\Eloquent\MsTraits;
class StyleFabricationStripeImplementation implements StyleFabricationStripeRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(StyleFabricationStripe $model)
	{
		$this->model = $model;
	}
}
