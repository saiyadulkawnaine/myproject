<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutRepository;
use App\Model\Inventory\Yarn\InvYarnTransOut;
use App\Traits\Eloquent\MsTraits; 
class InvYarnTransOutImplementation implements InvYarnTransOutRepository
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
	public function __construct(InvYarnTransOut $model)
	{
		$this->model = $model;
	}
	
	
}