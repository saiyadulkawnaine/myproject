<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ResourceRepository;
use App\Model\Util\Resource;
use App\Traits\Eloquent\MsTraits; 
class ResourceImplementation implements ResourceRepository
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
	public function __construct(Resource $model)
	{
		$this->model = $model;
	}
}