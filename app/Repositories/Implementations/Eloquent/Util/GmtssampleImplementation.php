<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Model\Util\Gmtssample;
use App\Traits\Eloquent\MsTraits; 
class GmtssampleImplementation implements GmtssampleRepository
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
	public function __construct(Gmtssample $model)
	{
		$this->model = $model;
	}
}