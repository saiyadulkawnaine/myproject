<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtPolyOrderRepository;
use App\Model\Production\Garments\ProdGmtPolyOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtPolyOrderImplementation implements ProdGmtPolyOrderRepository
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
	public function __construct(ProdGmtPolyOrder $model)
	{
		$this->model = $model;
	}
}
