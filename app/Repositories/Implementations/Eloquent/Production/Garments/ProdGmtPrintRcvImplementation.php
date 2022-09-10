<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvRepository;
use App\Model\Production\Garments\ProdGmtPrintRcv;
use App\Traits\Eloquent\MsTraits;

class ProdGmtPrintRcvImplementation implements ProdGmtPrintRcvRepository
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
	public function __construct(ProdGmtPrintRcv $model)
	{
		$this->model = $model;
	}
}
