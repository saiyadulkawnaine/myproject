<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;
use App\Model\Inventory\Yarn\InvYarnIsuItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnIsuItemImplementation implements InvYarnIsuItemRepository
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
	public function __construct(InvYarnIsuItem $model)
	{
		$this->model = $model;
	}
	
	
}