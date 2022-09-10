<?php
namespace App\Repositories\Implementations\Eloquent\Planing;

use App\Repositories\Contracts\Planing\TnaTemplateDtlRepository;
use App\Model\Planing\TnaTemplateDtl;
use App\Traits\Eloquent\MsTraits;

class TnaTemplateDtlImplementation implements TnaTemplateDtlRepository
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
	public function __construct(TnaTemplateDtl $model)
	{
		$this->model = $model;
	}
}
