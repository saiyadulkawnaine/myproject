<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryQtyRepository;
use App\Model\Production\Garments\ProdGmtExFactoryQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtExFactoryQtyImplementation implements ProdGmtExFactoryQtyRepository
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
	public function __construct(ProdGmtExFactoryQty $model)
	{
		$this->model = $model;
	}
}
