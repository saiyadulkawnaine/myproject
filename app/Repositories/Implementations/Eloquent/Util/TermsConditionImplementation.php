<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Model\Util\TermsCondition;
use App\Traits\Eloquent\MsTraits;
class TermsConditionImplementation implements TermsConditionRepository
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
	public function __construct(TermsCondition $model)
	{
		$this->model = $model;
	}
}
