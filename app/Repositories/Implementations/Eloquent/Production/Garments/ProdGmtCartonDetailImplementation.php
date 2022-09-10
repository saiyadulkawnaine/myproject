<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailRepository;
use App\Model\Production\Garments\ProdGmtCartonDetail;
use App\Traits\Eloquent\MsTraits;

class ProdGmtCartonDetailImplementation implements ProdGmtCartonDetailRepository
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
	public function __construct(ProdGmtCartonDetail $model)
	{
		$this->model = $model;
	}
}
