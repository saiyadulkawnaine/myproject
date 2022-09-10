<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AutoyarnratioRepository;
use App\Model\Util\Autoyarnratio;
use App\Traits\Eloquent\MsTraits; 
class AutoyarnratioImplementation implements AutoyarnratioRepository
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
	public function __construct(Autoyarnratio $model)
	{
		$this->model = $model;
	}
	
	public function getConstructinComposition(){
		$autoyarn=$this->join('autoyarnratios', function($join) {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join)  {
		$join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->get([
		'autoyarns.*',
		'constructions.name',
		'compositions.name as composition_name',
		'autoyarnratios.ratio'
		]);

		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($autoyarn as $row){
		$fabricDescriptionArr[$row->id]=$row->name;
		$fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
		$desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
		}
		return $desDropdown;
	}
	
	

}