<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Library\Numbertowords;
//use Illuminate\Support\Facades\DB;
//use App\Library\pdf;

use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvGeneralIsuRqRequest;

class InvGeneralIsuRqController extends Controller {

    private $invgeneralisurq;
    private $company;
    private $location;
    private $store;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;
    private $department;

    public function __construct(
        InvGeneralIsuRqRepository $invgeneralisurq,
        CompanyRepository $company,
        LocationRepository $location,
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        ItemclassRepository $itemclass,
        ItemcategoryRepository $itemcategory,
        DepartmentRepository $department
    ) {
        $this->invgeneralisurq = $invgeneralisurq;
        $this->company = $company;
        $this->location = $location;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->department = $department;
        $this->middleware('auth');
        
        // $this->middleware('permission:view.invgeneralisurqs',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.invgeneralisurqs', ['only' => ['store']]);
        // $this->middleware('permission:edit.invgeneralisurqs',   ['only' => ['update']]);
        // $this->middleware('permission:delete.invgeneralisurqs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        
        $rows=$this->invgeneralisurq
        ->join('companies',function($join){
        $join->on('companies.id','=','inv_general_isu_rqs.company_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_general_isu_rqs.location_id');
       })
       ->orderBy('inv_general_isu_rqs.id','desc')
       ->get([
        'inv_general_isu_rqs.*',
        'companies.code as company_id',
        'locations.name as location_id',
       ]);
       
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
        $generalisurqpurpose=array_prepend(config('bprs.generalisurqpurpose'),'-Select-','');


        return Template::loadView('Inventory.GeneralStore.InvGeneralIsuRq', ['company'=>$company,'location'=>$location,'store'=>$store,'department'=>$department,'generalisurqpurpose'=>$generalisurqpurpose]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvGeneralIsuRqRequest $request) {
        $max = $this->invgeneralisurq
        ->where([['company_id',$request->company_id]])
        ->max('rq_no');
        $rq_no=$max+1;
		$invgeneralisurq=$this->invgeneralisurq->create([
            'rq_no'=>$rq_no,
            'company_id'=>$request->company_id,
            'location_id'=>$request->location_id,
            'rq_date'=>$request->rq_date,
            'remarks'=>$request->remarks
        ]);
		if($invgeneralisurq){
			return response()->json(array('success' => true,'id' =>  $invgeneralisurq->id, 'rq_no' => $rq_no , 'message' => 'Save Successfully'),200);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $invgeneralisurq = $this->invgeneralisurq->find($id);
        $row ['fromData'] = $invgeneralisurq;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvGeneralIsuRqRequest $request, $id) {
         $invgeneralisurq=$this->invgeneralisurq->update($id,$request->except(['id','company_id']));
        if($invgeneralisurq){
            return response()->json(array('success'=> true, 'id' =>$id,  'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        $req=$this->invgeneralisurq->find($id);
        if($req->first_approved_by){
            return response()->json(array('success' => false,'message' => 'This Requisition is approved so delete not allowed'),200);

        }
        if($this->invgeneralisurq->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }

    public function getPdf(){
        $id = request('id',0);
        $generalisurqpurpose=array_prepend(config('bprs.generalisurqpurpose'),'-Select-','');

      $rows=$this->invgeneralisurq
      ->join('companies',function($join){
      $join->on('companies.id','=','inv_general_isu_rqs.company_id');
      })
      
      ->join('locations',function($join){
      $join->on('locations.id','=','inv_general_isu_rqs.location_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','inv_general_isu_rqs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as first_approval',function($join){
      $join->on('first_approval.id','=','inv_general_isu_rqs.first_approved_by');
      })
      ->leftJoin('employee_h_rs as first_approval_emp',function($join){
      $join->on('first_approval.id','=','first_approval_emp.user_id');
      })
      ->leftJoin('users as second_approval',function($join){
      $join->on('second_approval.id','=','inv_general_isu_rqs.second_approved_by');
      })
      ->leftJoin('users as third_approval',function($join){
      $join->on('third_approval.id','=','inv_general_isu_rqs.third_approved_by');
      })
      ->leftJoin('users as final_approval',function($join){
      $join->on('final_approval.id','=','inv_general_isu_rqs.final_approved_by');
      })
      ->where([['inv_general_isu_rqs.id','=',$id]])
      ->get([
      'inv_general_isu_rqs.*',
      'companies.name as company_name',
      'companies.logo as logo',
      'companies.address as company_address',
      'locations.name as location_name',
      'users.name as user_name',
      'employee_h_rs.contact',
      'first_approval.name as first_approval_name',
      'first_approval_emp.name as first_approval_emp_name',
      'first_approval_emp.contact as first_approval_emp_contact',
      'first_approval_emp.designation_id as first_approval_emp_designation',

      'second_approval.name as second_approval_name',
      'third_approval.name as third_approval_name',
      'final_approval.name as final_approval_name',
      ])
      ->first();
        $rows->rq_date=date('d-M-Y',strtotime($rows->rq_date));

        $results = \DB::select('
        select
        inv_general_isu_rq_items.id, 
        inv_general_isu_rqs.rq_date,
        inv_general_isu_rqs.company_id,
        inv_general_isu_rqs.location_id,
        inv_general_isu_rq_items.item_account_id,
        inv_general_isu_rq_items.department_id,
        inv_general_isu_rq_items.purpose_id,
        inv_general_isu_rq_items.asset_quantity_cost_id,
        last.id as last_id,
        last.rq_date as last_rq_date,
        last.qty as last_qty
        from inv_general_isu_rqs
        join inv_general_isu_rq_items
        on inv_general_isu_rqs.id=inv_general_isu_rq_items.inv_general_isu_rq_id
        join(
        select
        inv_general_isu_rq_items.id, 
        inv_general_isu_rqs.rq_date,
        inv_general_isu_rqs.company_id,
        inv_general_isu_rqs.location_id,
        inv_general_isu_rq_items.item_account_id,
        inv_general_isu_rq_items.department_id,
        inv_general_isu_rq_items.purpose_id,
        inv_general_isu_rq_items.asset_quantity_cost_id,
        inv_general_isu_rq_items.qty
        from inv_general_isu_rqs
        join inv_general_isu_rq_items
        on inv_general_isu_rqs.id=inv_general_isu_rq_items.inv_general_isu_rq_id
        where inv_general_isu_rqs.id !=? and inv_general_isu_rqs.deleted_at is null and inv_general_isu_rq_items.deleted_at is null
        ) last on last.company_id = inv_general_isu_rqs.company_id
        and last.location_id = inv_general_isu_rqs.location_id
        and last.item_account_id = inv_general_isu_rq_items.item_account_id
        and last.department_id = inv_general_isu_rq_items.department_id
        and last.purpose_id = inv_general_isu_rq_items.purpose_id
        and last.asset_quantity_cost_id = inv_general_isu_rq_items.asset_quantity_cost_id
        and last.rq_date < inv_general_isu_rqs.rq_date
        where inv_general_isu_rqs.id=? and inv_general_isu_rqs.deleted_at is null and inv_general_isu_rq_items.deleted_at is null order by last.id', [$id,$id]);

      $last_arr=[];
      foreach($results as $result)
      {
        $last_arr[$result->id]['last_date']=date('d-M-Y',strtotime($result->last_rq_date));
        $last_arr[$result->id]['qty']=$result->last_qty;
      }

        $invgeneralisurqitem=$this->invgeneralisurq
        ->join('inv_general_isu_rq_items',function($join){
            $join->on('inv_general_isu_rq_items.inv_general_isu_rq_id','=','inv_general_isu_rqs.id')
            ->whereNull('inv_general_isu_rq_items.deleted_at');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','inv_general_isu_rq_items.asset_quantity_cost_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_general_isu_rq_items.item_account_id');
        })
        ->leftJoin('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('departments',function($join){
        $join->on('departments.id','=','inv_general_isu_rq_items.department_id');
        })

        ->leftJoin('sales_orders', function($join){
        $join->on('sales_orders.id', '=', 'inv_general_isu_rq_items.sale_order_id');
        })
        ->leftJoin('jobs', function($join){
        $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->leftJoin('styles', function($join){
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->where([['inv_general_isu_rqs.id','=',$id]])
        ->orderBy('inv_general_isu_rq_items.id','desc')
       ->get([
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.id as item_account_id',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'inv_general_isu_rq_items.*',
          'departments.name as department_name',
          'styles.style_ref',
          'sales_orders.sale_order_no',
          'asset_quantity_costs.custom_no',
        ])
        ->map(function($invgeneralisurqitem) use($generalisurqpurpose, $last_arr) {
            $invgeneralisurqitem->purpose_id=isset($generalisurqpurpose[$invgeneralisurqitem->purpose_id])?$generalisurqpurpose[$invgeneralisurqitem->purpose_id]:'';
            $invgeneralisurqitem->last_qty=isset($last_arr[$invgeneralisurqitem->id]['qty'])?$last_arr[$invgeneralisurqitem->id]['qty']:'';
            $invgeneralisurqitem->last_date=isset($last_arr[$invgeneralisurqitem->id]['last_date'])?$last_arr[$invgeneralisurqitem->id]['last_date']:'';
            return $invgeneralisurqitem;
        }); 

        
      
      $data['master']    =$rows;
      $data['details']   =$invgeneralisurqitem;
      //$data['last']   =$last_arr;

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      //$txt = "Trim Purchase Order";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(12);
      $pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(115, 12, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Write(0, $rows->company_address, '', 0, 'C', true, 0, false, false, 0);

      $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(210);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'General Item Issue Reqisition Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('General Item Issue Reqisition Report');
      $view= \View::make('Defult.Inventory.GeneralStore.GeneralIsuRqPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(45);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/GeneralIsuRqPdf.pdf';
      $pdf->output($filename);
    }

}
