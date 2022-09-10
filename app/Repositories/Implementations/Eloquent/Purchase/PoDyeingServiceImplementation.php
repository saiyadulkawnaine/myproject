<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Model\Purchase\PoDyeingService;
use App\Traits\Eloquent\MsTraits;
class PoDyeingServiceImplementation implements PoDyeingServiceRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoDyeingServiceImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoDyeingService $model
	 */
	public function __construct(PoDyeingService $model)
	{
		$this->model = $model;
	}
}
