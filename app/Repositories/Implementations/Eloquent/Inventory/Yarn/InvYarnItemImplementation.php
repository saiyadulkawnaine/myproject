<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Model\Inventory\Yarn\InvYarnItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnItemImplementation implements InvYarnItemRepository
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
	public function __construct(InvYarnItem $model)
	{
		$this->model = $model;
	}
}