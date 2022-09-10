<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntOrderRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintEntOrder;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintEntOrderImplementation implements SoEmbPrintEntOrderRepository
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
	public function __construct(SoEmbPrintEntOrder $model)
	{
		$this->model = $model;
	}
}
