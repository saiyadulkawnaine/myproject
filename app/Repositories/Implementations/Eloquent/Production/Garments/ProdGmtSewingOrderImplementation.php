<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingOrderRepository;
use App\Model\Production\Garments\ProdGmtSewingOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtSewingOrderImplementation implements ProdGmtSewingOrderRepository
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
	public function __construct(ProdGmtSewingOrder $model)
	{
		$this->model = $model;
	}
}
