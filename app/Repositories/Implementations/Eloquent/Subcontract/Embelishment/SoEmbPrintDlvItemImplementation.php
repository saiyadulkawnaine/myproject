<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvItemRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintDlvItem;
use App\Traits\Eloquent\MsTraits;
class SoEmbPrintDlvItemImplementation implements SoEmbPrintDlvItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitProductImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoEmbPrintDlvItem $model)
	{
		$this->model = $model;
	}
}
