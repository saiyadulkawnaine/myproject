<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Model\Util\Autoyarn;
use App\Traits\Eloquent\MsTraits; 
class AutoyarnImplementation implements AutoyarnRepository
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
	public function __construct(Autoyarn $model)
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
	
	public function getConstruction(){
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
		foreach($autoyarn as $row){
		$fabricDescriptionArr[$row->id]=$row->name;
		}
		return $fabricDescriptionArr;
	}
	
	public function getComposition(){
		$autoyarn=$this->leftJoin('autoyarnratios', function($join) {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->leftJoin('constructions', function($join)  {
		$join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->leftJoin('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->orderBy('autoyarnratios.id','asc')
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
		$desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
		}
		return $desDropdown;
	}
	
}