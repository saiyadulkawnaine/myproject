<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\StoreItemcategoryRepository;
use App\Model\Util\StoreItemcategory;
use App\Traits\Eloquent\MsTraits; 
class StoreItemcategoryImplementation implements StoreItemcategoryRepository
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
	public function __construct(StoreItemcategory $model)
	{
		$this->model = $model;
	}
	
	
}