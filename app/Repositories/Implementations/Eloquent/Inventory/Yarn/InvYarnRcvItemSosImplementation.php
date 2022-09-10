<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemSosRepository;
use App\Model\Inventory\Yarn\InvYarnRcvItemSos;
use App\Traits\Eloquent\MsTraits; 
class InvYarnRcvItemSosImplementation implements InvYarnRcvItemSosRepository
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
	public function __construct(InvYarnRcvItemSos $model)
	{
		$this->model = $model;
	}
}