<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineRepository;
use App\Model\Production\Garments\ProdGmtSewingLine;
use App\Traits\Eloquent\MsTraits;

class ProdGmtSewingLineImplementation implements ProdGmtSewingLineRepository
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
	public function __construct(ProdGmtSewingLine $model)
	{
		$this->model = $model;
	}
}
