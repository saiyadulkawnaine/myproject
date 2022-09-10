<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\MailSetupRepository;
use App\Model\Util\MailSetup;
use App\Traits\Eloquent\MsTraits;
class MailSetupImplementation implements MailSetupRepository
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
	public function __construct(MailSetup $model)
	{
		$this->model = $model;
	}
}