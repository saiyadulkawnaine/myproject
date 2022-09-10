<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpPiRepository;
use App\Model\Commercial\Export\ExpPi;
use App\Traits\Eloquent\MsTraits; 
class ExpPiImplementation implements ExpPiRepository
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
	public function __construct(ExpPi $model)
	{
		$this->model = $model;
	}
	
	
}