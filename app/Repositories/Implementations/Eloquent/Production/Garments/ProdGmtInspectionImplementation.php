<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionRepository;
use App\Model\Production\Garments\ProdGmtInspection;
use App\Traits\Eloquent\MsTraits;

class ProdGmtInspectionImplementation implements ProdGmtInspectionRepository
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
	public function __construct(ProdGmtInspection $model)
	{
		$this->model = $model;
	}
}
