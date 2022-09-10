<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderRepository;
use App\Model\Subcontract\Inbound\SubInbOrder;
use App\Traits\Eloquent\MsTraits;
class SubInbOrderImplementation implements SubInbOrderRepository
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
	public function __construct(SubInbOrder $model)
	{
		$this->model = $model;
	}
}
