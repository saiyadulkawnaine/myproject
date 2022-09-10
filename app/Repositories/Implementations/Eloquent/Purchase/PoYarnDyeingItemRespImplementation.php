<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;

use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRespRepository;
use App\Model\Purchase\PoYarnDyeingItemResp;
use App\Traits\Eloquent\MsTraits;

class PoYarnDyeingItemRespImplementation implements PoYarnDyeingItemRespRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnDyeingItemRespImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarnDyeingItemResp $model)
	{
		$this->model = $model;
	}
}
