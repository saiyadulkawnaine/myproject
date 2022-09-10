<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Model\Util\Keycontrol;
use App\Traits\Eloquent\MsTraits; 
class KeycontrolImplementation implements KeycontrolRepository
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
	public function __construct(Keycontrol $model)
	{
		$this->model = $model;
	}
}