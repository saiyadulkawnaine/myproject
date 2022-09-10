<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditLcScRepository;
use App\Model\Commercial\Export\ExpPreCreditLcSc;
use App\Traits\Eloquent\MsTraits; 
class ExpPreCreditLcScImplementation implements ExpPreCreditLcScRepository
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
	public function __construct(ExpPreCreditLcSc $model)
	{
		$this->model = $model;
	}
	
	
}