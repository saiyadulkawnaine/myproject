<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Model\Subcontract\Embelishment\SoEmbCutpanelRcvOrder;
use App\Traits\Eloquent\MsTraits;

class SoEmbCutpanelRcvOrderImplementation implements SoEmbCutpanelRcvOrderRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoEmbCutpanelRcvOrder $model)
	{
		$this->model = $model;
	}
}
