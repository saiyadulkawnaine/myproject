<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtIronRepository;
use App\Model\Production\Garments\ProdGmtIron;
use App\Traits\Eloquent\MsTraits;

class ProdGmtIronImplementation implements ProdGmtIronRepository
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
	public function __construct(ProdGmtIron $model)
	{
		$this->model = $model;
	}
}
