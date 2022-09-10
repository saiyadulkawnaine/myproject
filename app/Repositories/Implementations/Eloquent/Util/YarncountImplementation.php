<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Model\Util\Yarncount;
use App\Traits\Eloquent\MsTraits; 
class YarncountImplementation implements YarncountRepository
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
	public function __construct(Yarncount $model)
	{
		$this->model = $model;
	}
	public function getForCombo(){
		   $yarncount=array();
			$rows=$this->get();
			foreach ($rows as $row) {
  				$yarncount[$row->id]=$row->count."/".$row->symbol;
  				//$yarncount['count']=$row->count." ".$row->symbol;
  				//$yarncount['symbol']=$row->symbol;
  				//array_push($yarncounts,$yarncount);
  			}
			return $yarncount;
	}
}