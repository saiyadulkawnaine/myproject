<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Model\Production\Garments\ProdGmtCartonEntry;
use App\Traits\Eloquent\MsTraits;

class ProdGmtCartonEntryImplementation implements ProdGmtCartonEntryRepository
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
	public function __construct(ProdGmtCartonEntry $model)
	{
		$this->model = $model;
	}
}
