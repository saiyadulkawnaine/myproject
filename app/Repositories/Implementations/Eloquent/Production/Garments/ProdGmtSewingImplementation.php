<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Model\Production\Garments\ProdGmtSewing;
use App\Traits\Eloquent\MsTraits;

class ProdGmtSewingImplementation implements ProdGmtSewingRepository
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
	public function __construct(ProdGmtSewing $model)
	{
		$this->model = $model;
	}
}
