<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerNominatedsupplierRepository;
use App\Model\Util\BuyerNominatedsupplier;
use App\Traits\Eloquent\MsTraits; 
class BuyerNominatedsupplierImplementation implements BuyerNominatedsupplierRepository
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
	public function __construct(BuyerNominatedsupplier $model)
	{
		$this->model = $model;
	}
}