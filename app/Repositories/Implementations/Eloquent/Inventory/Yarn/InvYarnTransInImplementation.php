<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInRepository;
use App\Model\Inventory\Yarn\InvYarnTransIn;
use App\Traits\Eloquent\MsTraits; 
class InvYarnTransInImplementation implements InvYarnTransInRepository
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
	public function __construct(InvYarnTransIn $model)
	{
		$this->model = $model;
	}
	
	
}