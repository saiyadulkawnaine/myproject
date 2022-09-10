<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputOrderRepository;
use App\Model\Production\Garments\ProdGmtDlvInputOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvInputOrderImplementation implements ProdGmtDlvInputOrderRepository
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
	public function __construct(ProdGmtDlvInputOrder $model)
	{
		$this->model = $model;
	}
}
