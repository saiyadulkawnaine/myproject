<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFileRepository;
use App\Model\Subcontract\Dyeing\SoDyeingFile;
use App\Traits\Eloquent\MsTraits;
class SoDyeingFileImplementation implements SoDyeingFileRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingFileImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingFile $model)
	{
		$this->model = $model;
	}
}
