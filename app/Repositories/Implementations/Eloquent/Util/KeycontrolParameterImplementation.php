<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\KeycontrolParameterRepository;
use App\Model\Util\KeycontrolParameter;
use App\Traits\Eloquent\MsTraits; 
class KeycontrolParameterImplementation implements KeycontrolParameterRepository
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
	public function __construct(KeycontrolParameter $model)
	{
		$this->model = $model;
	}
}