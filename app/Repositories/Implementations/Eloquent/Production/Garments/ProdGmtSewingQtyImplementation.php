<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingQtyRepository;
use App\Model\Production\Garments\ProdGmtSewingQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtSewingQtyImplementation implements ProdGmtSewingQtyRepository
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
	public function __construct(ProdGmtSewingQty $model)
	{
		$this->model = $model;
	}
}
