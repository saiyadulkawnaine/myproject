<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimIsuItemRepository;
use App\Model\Inventory\Trim\InvTrimIsuItem;
use App\Traits\Eloquent\MsTraits; 
class InvTrimIsuItemImplementation implements InvTrimIsuItemRepository
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
	public function __construct(InvTrimIsuItem $model)
	{
		$this->model = $model;
	}
}