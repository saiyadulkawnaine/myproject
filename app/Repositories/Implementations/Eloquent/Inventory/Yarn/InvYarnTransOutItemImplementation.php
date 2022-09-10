<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutItemRepository;
use App\Model\Inventory\Yarn\InvYarnTransOutItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnTransOutItemImplementation implements InvYarnTransOutItemRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * InvYarnIsuRtnImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(InvYarnTransOutItem $model)
	{
		$this->model = $model;
	}
}