<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Model\Util\Colorrange;
use App\Traits\Eloquent\MsTraits; 
class ColorrangeImplementation implements ColorrangeRepository
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
	public function __construct(Colorrange $model)
	{
		$this->model = $model;
	}
}