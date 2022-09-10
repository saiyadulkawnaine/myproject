<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcScPiRepository;
use App\Model\Commercial\Export\ExpLcScPi;
use App\Traits\Eloquent\MsTraits; 
class ExpLcScPiImplementation implements ExpLcScPiRepository
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
	public function __construct(ExpLcScPi $model)
	{
		$this->model = $model;
	}
	
	
}