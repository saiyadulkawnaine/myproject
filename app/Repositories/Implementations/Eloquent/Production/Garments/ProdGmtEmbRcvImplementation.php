<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvRepository;
use App\Model\Production\Garments\ProdGmtEmbRcv;
use App\Traits\Eloquent\MsTraits;

class ProdGmtEmbRcvImplementation implements ProdGmtEmbRcvRepository
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
	public function __construct(ProdGmtEmbRcv $model)
	{
		$this->model = $model;
	}
}
