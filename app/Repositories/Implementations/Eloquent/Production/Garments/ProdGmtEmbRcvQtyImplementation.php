<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvQtyRepository;
use App\Model\Production\Garments\ProdGmtEmbRcvQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtEmbRcvQtyImplementation implements ProdGmtEmbRcvQtyRepository
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
	public function __construct(ProdGmtEmbRcvQty $model)
	{
		$this->model = $model;
	}
}
