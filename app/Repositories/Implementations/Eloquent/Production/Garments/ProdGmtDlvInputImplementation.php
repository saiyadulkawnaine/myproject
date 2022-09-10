<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputRepository;
use App\Model\Production\Garments\ProdGmtDlvInput;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvInputImplementation implements ProdGmtDlvInputRepository
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
	public function __construct(ProdGmtDlvInput $model)
	{
		$this->model = $model;
	}
}
