<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineQtyRepository;
use App\Model\Production\Garments\ProdGmtSewingLineQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtSewingLineQtyImplementation implements ProdGmtSewingLineQtyRepository
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
	public function __construct(ProdGmtSewingLineQty $model)
	{
		$this->model = $model;
	}
}
