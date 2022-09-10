<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRollRepository;
use App\Model\Production\Dyeing\ProdFinishDlvRoll;
use App\Traits\Eloquent\MsTraits;

class ProdFinishDlvRollImplementation implements ProdFinishDlvRollRepository
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
	public function __construct(ProdFinishDlvRoll $model)
	{
		$this->model = $model;
	}
}
