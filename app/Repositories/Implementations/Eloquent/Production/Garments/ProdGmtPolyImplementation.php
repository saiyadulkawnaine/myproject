<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtPolyRepository;
use App\Model\Production\Garments\ProdGmtPoly;
use App\Traits\Eloquent\MsTraits;

class ProdGmtPolyImplementation implements ProdGmtPolyRepository
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
	public function __construct(ProdGmtPoly $model)
	{
		$this->model = $model;
	}
}
