<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInItemRepository;
use App\Model\Inventory\Yarn\InvYarnTransInItem;
use App\Traits\Eloquent\MsTraits; 
class InvYarnTransInItemImplementation implements InvYarnTransInItemRepository
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
	public function __construct(InvYarnTransInItem $model)
	{
		$this->model = $model;
	}
}