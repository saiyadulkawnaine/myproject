<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Model\Util\WashCharge;
use App\Traits\Eloquent\MsTraits;
class WashChargeImplementation implements WashChargeRepository
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
	public function __construct(WashCharge $model)
	{
		$this->model = $model;
	}
	public function getCharges(){
		$charges=array();
		$rows=$this->get();
		foreach($rows as $row){
			$charges[$row->embelishment_id][$row->embelishment_type_id]=$row->rate;
		}
		return $charges;
	}
}
