<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionOrderRepository;
use App\Model\Production\Garments\ProdGmtInspectionOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtInspectionOrderImplementation implements ProdGmtInspectionOrderRepository
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
	public function __construct(ProdGmtInspectionOrder $model)
	{
		$this->model = $model;
	}
}
