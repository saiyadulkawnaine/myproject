<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemQtyRepository;
use App\Model\Purchase\PoDyeingServiceItemQty;
use App\Traits\Eloquent\MsTraits;
class PoDyeingServiceItemQtyImplementation implements PoDyeingServiceItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoDyeingServiceItemQtyImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoDyeingServiceItemQty $model
	 */
	public function __construct(PoDyeingServiceItemQty $model)
	{
		$this->model = $model;
	}
}
