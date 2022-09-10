<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\GmtspartMenuRepository;
use App\Model\Util\GmtspartMenu;
use App\Traits\Eloquent\MsTraits; 
class GmtspartMenuImplementation implements GmtspartMenuRepository
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
	public function __construct(GmtspartMenu $model)
	{
		$this->model = $model;
	}
}