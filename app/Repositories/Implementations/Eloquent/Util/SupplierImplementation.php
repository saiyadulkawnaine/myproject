<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Model\Util\Supplier;
use App\Traits\Eloquent\MsTraits; 
class SupplierImplementation implements SupplierRepository
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
	public function __construct(Supplier $model)
	{
		$this->model = $model;
	}

	public function otherPartise(){
		$otherPartise=$this->selectRaw(
			'suppliers.*'
			)
			->join('supplier_natures', function($join) {
				$join->on('supplier_natures.supplier_id', '=', 'suppliers.id');
			})
			->where([['supplier_natures.contact_nature_id','=',53]])
			->where([['suppliers.status_id','=',1]])
			->get();
			return $otherPartise;
	}
	public function forwardingAgents(){
		$forwardingAgents=$this->selectRaw(
			'suppliers.*'
			)
			->join('supplier_natures', function($join) {
				$join->on('supplier_natures.supplier_id', '=', 'suppliers.id');
			})
			->where([['supplier_natures.contact_nature_id','=',54]])
			->where([['suppliers.status_id','=',1]])
			->get();
			return $forwardingAgents;
	}

	public function shippingLines(){
		$forwardingAgents=$this->selectRaw(
			'suppliers.*'
			)
			->join('supplier_natures', function($join) {
				$join->on('supplier_natures.supplier_id', '=', 'suppliers.id');
			})
			->where([['supplier_natures.contact_nature_id','=',55]])
			->where([['suppliers.status_id','=',1]])
			->get();
			return $forwardingAgents;
	}
	public function yarnSupplier(){
		$yarnsuppliers=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures', function($join) {
			$join->on('supplier_natures.supplier_id', '=', 'suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',19]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $yarnsuppliers;
	}

	public function transportAgents(){
		$transportAgents=$this->selectRaw(
			'suppliers.*'
			)
			->join('supplier_natures', function($join) {
				$join->on('supplier_natures.supplier_id', '=', 'suppliers.id');
			})
			->where([['supplier_natures.contact_nature_id','=',35]])
			->where([['suppliers.status_id','=',1]])
			->get();
			return $transportAgents;
	}
	public function garmentSubcontractors(){
		$garmentSubcontractors=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',29]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $garmentSubcontractors;
	}
	public function embellishmentSubcontractor(){
		$embellishmentSubcontractors=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',30]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $embellishmentSubcontractors;
	}
	public function knitSubcontractor(){
		$knitSubcontractors=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',26]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $knitSubcontractors;
	}
	public function DyeFinSubcontractor(){
		$dyefinSubcontractors=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',27]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $dyefinSubcontractors;
	}
	
	public function AopSubcontractor(){
		$aopsubcontractor=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id', '=', 33]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $aopsubcontractor;
	}
	public function trimsSupplier(){
		$trimsSuppliers=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',21]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $trimsSuppliers;
	}
	
	public function DyesAndChemSupplier(){
		$dyechemsupplier=$this->selectRaw(
			'suppliers.*'
		)
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',22]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $dyechemsupplier;
	}

	public function GeneralItemSupplier(){
		$generalItem=$this->selectRaw('
			suppliers.*
		')
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->whereIn('supplier_natures.contact_nature_id',[24,25,31,32,37,38,39,40,41,43,44,45,46,47,49,50,51])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $generalItem;
	}	
	
	public function YarnDyeingSubcontractor(){
		$yarndyesubcontractor=$this->selectRaw('
			suppliers.*
		')
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',56]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $yarndyesubcontractor;
	}	
	
	public function indentor(){
		$indentors=$this->selectRaw('
			suppliers.*
		')
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',39]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $indentors;
	}

	public function insuranceCompany(){
		$indentors=$this->selectRaw('
			suppliers.*
		')
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',59]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $indentors;
	}

	public function fabricSupplier(){
		$indentors=$this->selectRaw('
			suppliers.*
		')
		->join('supplier_natures',function($join){
			$join->on('supplier_natures.supplier_id','=','suppliers.id');
		})
		->where([['supplier_natures.contact_nature_id','=',60]])
		->where([['suppliers.status_id','=',1]])
		->get();
		return $indentors;
	}
}