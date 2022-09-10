<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\TnataskRepository;
use App\Model\Util\Tnatask;
use App\Traits\Eloquent\MsTraits; 
class TnataskImplementation implements TnataskRepository
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
	public function __construct(Tnatask $model)
	{
		$this->model = $model;
	}
}