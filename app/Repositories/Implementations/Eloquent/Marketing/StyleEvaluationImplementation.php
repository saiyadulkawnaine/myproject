<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleEvaluationRepository;
use App\Model\Marketing\StyleEvaluation;
use App\Traits\Eloquent\MsTraits;
class StyleEvaluationImplementation implements StyleEvaluationRepository
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
	public function __construct(StyleEvaluation $model)
	{
		$this->model = $model;
	}
}
