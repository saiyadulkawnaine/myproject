<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputRepository;
use App\Model\Production\Garments\ProdGmtRcvInput;
use App\Traits\Eloquent\MsTraits;

class ProdGmtRcvInputImplementation implements ProdGmtRcvInputRepository
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
	public function __construct(ProdGmtRcvInput $model)
	{
		$this->model = $model;
	}
}
