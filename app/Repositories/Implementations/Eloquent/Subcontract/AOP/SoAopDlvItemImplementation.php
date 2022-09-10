<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvItemRepository;
use App\Model\Subcontract\AOP\SoAopDlvItem;
use App\Traits\Eloquent\MsTraits;
class SoAopDlvItemImplementation implements SoAopDlvItemRepository
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
	public function __construct(SoAopDlvItem $model)
	{
		$this->model = $model;
	}
}
