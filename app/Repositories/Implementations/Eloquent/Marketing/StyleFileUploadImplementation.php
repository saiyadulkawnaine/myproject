<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleFileUploadRepository;
use App\Model\Marketing\StyleFileUpload;
use App\Traits\Eloquent\MsTraits;
class StyleFileUploadImplementation implements StyleFileUploadRepository
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
	public function __construct(StyleFileUpload $model)
	{
		$this->model = $model;
	}
}
