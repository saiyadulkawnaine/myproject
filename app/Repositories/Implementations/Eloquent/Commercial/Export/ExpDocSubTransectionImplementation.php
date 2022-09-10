<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;

use App\Repositories\Contracts\Commercial\Export\ExpDocSubTransectionRepository;
use App\Model\Commercial\Export\ExpDocSubTransection;
use App\Traits\Eloquent\MsTraits; 
class ExpDocSubTransectionImplementation implements ExpDocSubTransectionRepository
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
	public function __construct(ExpDocSubTransection $model)
	{
		$this->model = $model;
	}
	
	
}