<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintRepository;
use App\Model\Production\Garments\ProdGmtDlvPrint;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvPrintImplementation implements ProdGmtDlvPrintRepository
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
	public function __construct(ProdGmtDlvPrint $model)
	{
		$this->model = $model;
	}
}
