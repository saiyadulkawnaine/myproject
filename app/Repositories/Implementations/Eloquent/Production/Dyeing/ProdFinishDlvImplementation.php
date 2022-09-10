<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Model\Production\Dyeing\ProdFinishDlv;
use App\Traits\Eloquent\MsTraits;

class ProdFinishDlvImplementation implements ProdFinishDlvRepository
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
	public function __construct(ProdFinishDlv $model)
	{
		$this->model = $model;
	}
}
