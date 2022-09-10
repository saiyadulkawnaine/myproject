<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemRepository;
use App\Model\Subcontract\Kniting\SoKnitDlvItem;
use App\Traits\Eloquent\MsTraits;
class SoKnitDlvItemImplementation implements SoKnitDlvItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitDlvItem $model)
	{
		$this->model = $model;
	}
}
