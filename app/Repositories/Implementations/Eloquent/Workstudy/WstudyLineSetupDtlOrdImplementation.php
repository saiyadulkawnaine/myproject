<?php
namespace App\Repositories\Implementations\Eloquent\Workstudy;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlOrdRepository;
use App\Model\Workstudy\WstudyLineSetupDtlOrd;
use App\Traits\Eloquent\MsTraits;

class WstudyLineSetupDtlOrdImplementation implements WstudyLineSetupDtlOrdRepository
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
	public function __construct(WstudyLineSetupDtlOrd $model)
	{
		$this->model = $model;
	}
}
