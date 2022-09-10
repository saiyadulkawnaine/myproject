<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzAmountRepository;
use App\Model\Commercial\Export\ExpProRlzAmount;
use App\Traits\Eloquent\MsTraits; 
class ExpProRlzAmountImplementation implements ExpProRlzAmountRepository
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
	public function __construct(ExpProRlzAmount $model)
	{
		$this->model = $model;
	}
	
	
}