<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Model\Util\Gmtspart;
use App\Traits\Eloquent\MsTraits; 
class GmtspartImplementation implements GmtspartRepository
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
	public function __construct(Gmtspart $model)
	{
		$this->model = $model;
	}
}