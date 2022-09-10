<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Model\Util\Designation;
use App\Traits\Eloquent\MsTraits;
use App\Traits\Eloquent\MsUpdater;
class DesignationImplementation implements DesignationRepository
{
	 use MsTraits;

	/**
	 * @var $model
	 */
	private $model;


	public function __construct(Designation $model)
	{
		$this->model = $model;
	}
}
