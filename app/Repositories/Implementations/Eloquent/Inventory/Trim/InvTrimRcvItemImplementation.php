<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvItemRepository;
use App\Model\Inventory\Trim\InvTrimRcvItem;
use App\Traits\Eloquent\MsTraits; 
class InvTrimRcvItemImplementation implements InvTrimRcvItemRepository
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
	public function __construct(InvTrimRcvItem $model)
	{
		$this->model = $model;
	}
	
	
}