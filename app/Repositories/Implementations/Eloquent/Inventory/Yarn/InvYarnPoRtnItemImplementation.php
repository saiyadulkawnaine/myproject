<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnPoRtnItemRepository;
use App\Model\Inventory\Yarn\InvYarnPoRtnItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnPoRtnItemImplementation implements InvYarnPoRtnItemRepository
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
	public function __construct(InvYarnPoRtnItem $model)
	{
		$this->model = $model;
	}
}