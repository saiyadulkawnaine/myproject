<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\RegisterVisitorRepository;
use App\Model\HRM\RegisterVisitor;
use App\Traits\Eloquent\MsTraits; 
class RegisterVisitorImplementation implements RegisterVisitorRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * RegisterVisitorImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(RegisterVisitor $model)
	{
		$this->model = $model;
	}
	
	public function smsVisitor($id){
		$visitor=$this->selectRaw('
			register_visitors.id,
			register_visitors.name as visitor_name,
			register_visitors.contact_no,
			register_visitors.organization_dtl,
			register_visitors.arrival_time,
			register_visitors.user_id,
			register_visitors.purpose,
			users.name as user_name,
			departments.name as department_name
		')
		->leftJoin('users',function($join){
			$join->on('users.id','=','register_visitors.user_id');
		  })
		  ->leftJoin('employee_h_rs',function($join){
			$join->on('users.id','=','employee_h_rs.user_id');
		  })
		  ->leftJoin('departments',function($join){
			$join->on('departments.id','=','employee_h_rs.department_id');
		  })
		  ->where([['register_visitors.id','=',$id]])
		  ->get()
		  ->first();
			$text="FamKam ERP\n";
			$text.="Visitor Name ".$visitor->visitor_name."\n";
			$text.="Phone Number : ".$visitor->contact_no."\n";
			$text.="Organization : ".$visitor->organization_dtl."\n";
			// $text.="Buying House : ".$buying_house->name.", ".$style->contact."\n";
			// $text.="Value Addition : ".implode(", ",$value_addition)."\n";
			// $text.="Fabric Type : ".implode(", ",$contruction)."\n";
			// $text.="Item : ".implode(", ",$item)."\n";
			// $text.="Style : ".$style->style_ref."\n";
			// $text.="Est. OP Date : ".date('d-M-Y',strtotime($style->op_date))."\n";
			// $text.="Est. Ship Date : ".date('d-M-Y',strtotime($style->est_ship_date))."\n";
			// $text.="Lead Time : ".$style->lead_time." Days\n"; 
			// $text.="GMT Qty : ".number_format($style->offer_qty,0,'.',',')."\n";
			// $text.="Order UOM : ".$style->uom_name."\n";
			// $text.="Fabric Cons/Dzn : ".$consDzn."\n";
            // $text.="Price/Pcs : ".$style->quote_price."\n";
			// $text.="Cost/Pcs : ".$totalCostPcs."\n";
			return $text;
	}
}