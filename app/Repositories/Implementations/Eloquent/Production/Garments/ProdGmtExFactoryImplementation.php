<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryRepository;
use App\Model\Production\Garments\ProdGmtExFactory;
use App\Traits\Eloquent\MsTraits;

class ProdGmtExFactoryImplementation implements ProdGmtExFactoryRepository
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
	public function __construct(ProdGmtExFactory $model)
	{
		$this->model = $model;
	}
}
