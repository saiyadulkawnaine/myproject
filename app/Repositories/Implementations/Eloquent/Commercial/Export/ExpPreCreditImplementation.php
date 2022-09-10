<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditRepository;
use App\Model\Commercial\Export\ExpPreCredit;
use App\Traits\Eloquent\MsTraits; 
class ExpPreCreditImplementation implements ExpPreCreditRepository
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
	public function __construct(ExpPreCredit $model)
	{
		$this->model = $model;
	}
	
	
}