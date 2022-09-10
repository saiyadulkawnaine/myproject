<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRtnRepository;
use App\Model\Inventory\Yarn\InvYarnIsuRtn;
use App\Traits\Eloquent\MsTraits; 
class InvYarnIsuRtnImplementation implements InvYarnIsuRtnRepository
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
	public function __construct(InvYarnIsuRtn $model)
	{
		$this->model = $model;
	}
	
	
}