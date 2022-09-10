<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtIronOrderRepository;
use App\Model\Production\Garments\ProdGmtIronOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtIronOrderImplementation implements ProdGmtIronOrderRepository
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
	public function __construct(ProdGmtIronOrder $model)
	{
		$this->model = $model;
	}
}
