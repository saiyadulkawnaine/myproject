<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\TrimcosttempleteRepository;
use App\Model\Util\Trimcosttemplete;
use App\Traits\Eloquent\MsTraits; 
class TrimcosttempleteImplementation implements TrimcosttempleteRepository
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
	public function __construct(Trimcosttemplete $model)
	{
		$this->model = $model;
	}
}