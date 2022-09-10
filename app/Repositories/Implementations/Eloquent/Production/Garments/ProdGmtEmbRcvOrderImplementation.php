<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvOrderRepository;
use App\Model\Production\Garments\ProdGmtEmbRcvOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtEmbRcvOrderImplementation implements ProdGmtEmbRcvOrderRepository
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
	public function __construct(ProdGmtEmbRcvOrder $model)
	{
		$this->model = $model;
	}
}
