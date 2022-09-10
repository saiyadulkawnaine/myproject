<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Model\Util\BuyerNature;
use App\Traits\Eloquent\MsTraits; 
class BuyerNatureImplementation implements BuyerNatureRepository
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
	public function __construct(BuyerNature $model)
	{
		$this->model = $model;
	}
	
	public function getBuyingHouses(){
		$buyinghouses=$this->selectRaw(
		'buyers.id,
		buyers.name'
		)
		->join('buyers',function($join){
		$join->on('buyers.id','=','buyer_natures.buyer_id');
		})
		->where([['buyer_natures.contact_nature_id','=',14]])
		->get();
		return $buyinghouses;
	}
}