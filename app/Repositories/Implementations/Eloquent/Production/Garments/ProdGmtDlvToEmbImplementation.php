<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;
use App\Model\Production\Garments\ProdGmtDlvToEmb;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvToEmbImplementation implements ProdGmtDlvToEmbRepository
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
	public function __construct(ProdGmtDlvToEmb $model)
	{
		$this->model = $model;
	}
}
