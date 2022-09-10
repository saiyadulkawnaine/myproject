<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Model\Subcontract\Embelishment\SoEmbCutpanelRcv;
use App\Traits\Eloquent\MsTraits;

class SoEmbCutpanelRcvImplementation implements SoEmbCutpanelRcvRepository
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
	public function __construct(SoEmbCutpanelRcv $model)
	{
		$this->model = $model;
	}
}
