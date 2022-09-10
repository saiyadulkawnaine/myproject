<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemRepository;
use App\Model\Purchase\PoDyeingServiceItem;
use App\Traits\Eloquent\MsTraits;
class PoDyeingServiceItemImplementation implements PoDyeingServiceItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoDyeingServiceItemImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoDyeingServiceItem $model
	 */
	public function __construct(PoDyeingServiceItem $model)
	{
		$this->model = $model;
	}
}
