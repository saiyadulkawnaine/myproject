<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbOrderRepository;
use App\Model\Production\Garments\ProdGmtDlvToEmbOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvToEmbOrderImplementation implements ProdGmtDlvToEmbOrderRepository
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
	public function __construct(ProdGmtDlvToEmbOrder $model)
	{
		$this->model = $model;
	}
}
