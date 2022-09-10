<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;
use App\Model\Inventory\Yarn\InvYarnRcvItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnRcvItemImplementation implements InvYarnRcvItemRepository
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
	public function __construct(InvYarnRcvItem $model)
	{
		$this->model = $model;
	}
}