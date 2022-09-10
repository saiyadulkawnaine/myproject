<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbEventRepository;
use App\Model\Subcontract\Inbound\SubInbEvent;
use App\Traits\Eloquent\MsTraits;
class SubInbEventImplementation implements SubInbEventRepository
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
	public function __construct(SubInbEvent $model)
	{
		$this->model = $model;
	}
}
