<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Model\Subcontract\Embelishment\SoEmbCutpanelRcvQty;
use App\Traits\Eloquent\MsTraits;

class SoEmbCutpanelRcvQtyImplementation implements SoEmbCutpanelRcvQtyRepository
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
	public function __construct(SoEmbCutpanelRcvQty $model)
	{
		$this->model = $model;
	}
}
