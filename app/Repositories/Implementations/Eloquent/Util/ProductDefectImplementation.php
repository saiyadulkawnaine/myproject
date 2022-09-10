<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ProductDefectRepository;
use App\Model\Util\ProductDefect;
use App\Traits\Eloquent\MsTraits; 
class ProductDefectImplementation implements ProductDefectRepository
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
	public function __construct(ProductDefect $model)
	{
		$this->model = $model;
	}
}