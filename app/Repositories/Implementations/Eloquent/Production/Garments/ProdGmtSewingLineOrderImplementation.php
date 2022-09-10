<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineOrderRepository;
use App\Model\Production\Garments\ProdGmtSewingLineOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtSewingLineOrderImplementation implements ProdGmtSewingLineOrderRepository
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
	public function __construct(ProdGmtSewingLineOrder $model)
	{
		$this->model = $model;
	}
}
