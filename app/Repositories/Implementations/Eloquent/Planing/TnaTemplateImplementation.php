<?php
namespace App\Repositories\Implementations\Eloquent\Planing;

use App\Repositories\Contracts\Planing\TnaTemplateRepository;
use App\Model\Planing\TnaTemplate;
use App\Traits\Eloquent\MsTraits;

class TnaTemplateImplementation implements TnaTemplateRepository
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
	public function __construct(TnaTemplate $model)
	{
		$this->model = $model;
	}
}
