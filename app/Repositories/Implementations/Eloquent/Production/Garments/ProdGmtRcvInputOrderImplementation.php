<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputOrderRepository;
use App\Model\Production\Garments\ProdGmtRcvInputOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtRcvInputOrderImplementation implements ProdGmtRcvInputOrderRepository
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
	public function __construct(ProdGmtRcvInputOrder $model)
	{
		$this->model = $model;
	}
}
