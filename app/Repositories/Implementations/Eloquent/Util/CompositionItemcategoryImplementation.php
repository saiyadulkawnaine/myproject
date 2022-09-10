<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompositionItemcategoryRepository;
use App\Model\Util\CompositionItemcategory;
use App\Traits\Eloquent\MsTraits; 
class CompositionItemcategoryImplementation implements CompositionItemcategoryRepository
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
	public function __construct(CompositionItemcategory $model)
	{
		$this->model = $model;
	}
}