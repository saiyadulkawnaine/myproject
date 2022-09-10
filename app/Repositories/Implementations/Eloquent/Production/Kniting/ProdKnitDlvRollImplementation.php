<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRollRepository;
use App\Model\Production\Kniting\ProdKnitDlvRoll;
use App\Traits\Eloquent\MsTraits;

class ProdKnitDlvRollImplementation implements ProdKnitDlvRollRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdKnitDlvRoll $model)
	{
		$this->model = $model;
	}
}
