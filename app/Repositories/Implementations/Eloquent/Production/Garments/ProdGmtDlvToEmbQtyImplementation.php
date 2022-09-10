<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbQtyRepository;
use App\Model\Production\Garments\ProdGmtDlvToEmbQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvToEmbQtyImplementation implements ProdGmtDlvToEmbQtyRepository
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
	public function __construct(ProdGmtDlvToEmbQty $model)
	{
		$this->model = $model;
	}
}
