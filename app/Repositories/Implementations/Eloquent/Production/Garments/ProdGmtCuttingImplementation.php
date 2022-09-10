<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingRepository;
use App\Model\Production\Garments\ProdGmtCutting;
use App\Traits\Eloquent\MsTraits;

class ProdGmtCuttingImplementation implements ProdGmtCuttingRepository
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
	public function __construct(ProdGmtCutting $model)
	{
		$this->model = $model;
	}
}
