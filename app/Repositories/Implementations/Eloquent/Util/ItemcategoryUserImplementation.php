<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemcategoryUserRepository;
use App\Model\Util\ItemcategoryUser;
use App\Traits\Eloquent\MsTraits; 
class ItemcategoryUserImplementation implements ItemcategoryUserRepository
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
	public function __construct(ItemcategoryUser $model)
	{
		$this->model = $model;
	}
	
	
}