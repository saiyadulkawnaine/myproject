<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnPoRtnRepository;
use App\Model\Inventory\Yarn\InvYarnPoRtn;
use App\Traits\Eloquent\MsTraits; 
class InvYarnPoRtnImplementation implements InvYarnPoRtnRepository
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
	public function __construct(InvYarnPoRtn $model)
	{
		$this->model = $model;
	}
}