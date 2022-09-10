<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRepository;
use App\Model\Production\Kniting\ProdKnitDlv;
use App\Traits\Eloquent\MsTraits;

class ProdKnitDlvImplementation implements ProdKnitDlvRepository
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
	public function __construct(ProdKnitDlv $model)
	{
		$this->model = $model;
	}
}
