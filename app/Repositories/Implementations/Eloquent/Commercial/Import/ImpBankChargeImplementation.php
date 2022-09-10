<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpBankChargeRepository;
use App\Model\Commercial\Import\ImpBankCharge;
use App\Traits\Eloquent\MsTraits; 

class ImpBankChargeImplementation implements ImpBankChargeRepository
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
	public function __construct(ImpBankCharge $model)
	{
		$this->model = $model;
	}
}