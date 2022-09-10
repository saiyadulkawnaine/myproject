<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRtnItemRepository;
use App\Model\Inventory\Yarn\InvYarnIsuRtnItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnIsuRtnItemImplementation implements InvYarnIsuRtnItemRepository
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
	public function __construct(InvYarnIsuRtnItem $model)
	{
		$this->model = $model;
	}
	
	
}