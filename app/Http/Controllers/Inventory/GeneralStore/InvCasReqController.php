<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Inventory\GeneralStore\InvCasReqRequest;


class InvCasReqController extends Controller {

    private $casreq;
    private $company;
    private $location;
    private $currency;
    private $uom;
    private $designation;

    public function __construct(InvCasReqRepository $casreq,CompanyRepository $company,LocationRepository $location,CurrencyRepository $currency,UomRepository $uom,UserRepository $user,DesignationRepository $designation) {
        $this->casreq = $casreq;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->uom = $uom;
        $this->user = $user;
        $this->designation = $designation;


        $this->middleware('auth');
        $this->middleware('permission:view.invcashreqs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invcashreqs', ['only' => ['store']]);
        $this->middleware('permission:edit.invcashreqs',   ['only' => ['update']]);
        $this->middleware('permission:delete.invcashreqs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
       $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
       $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
       $casreqs = array();
       $rows = $this->casreq
       ->where([['inv_pur_reqs.requisition_type_id',2]])
       ->orderBy('inv_pur_reqs.id','desc')
       ->get();
       foreach ($rows as $row) {
           $casreq['id']=$row->id;
           $casreq['requisition_type_id']=$row->requisition_type_id;
           $casreq['requisition_no']=$row->requisition_no;
           $casreq['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
           $casreq['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
           $casreq['currency_id']=$currency[$row->currency_id];
           $casreq['req_date']=date('Y-m-d',strtotime($row->req_date));
           $casreq['disburse_by']=date('Y-m-d',strtotime($row->disburse_by));
           $casreq['remarks']=$row->remarks;
           array_push($casreqs, $casreq);
        }
        echo json_encode($casreqs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
		$company=array_prepend(array_pluck($this->company->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');

        return Template::loadView('Inventory.GeneralStore.InvCasReqItem',['company'=>$company,'location'=>$location,'currency'=>$currency,'uom'=>$uom,'user'=>$user,'yesno'=>$yesno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvCasReqRequest $request) {

        $max = $this->casreq
        ->where([['company_id',$request->company_id]])
        ->max('requisition_no');
        $requisition_no=$max+1;
        $request->request->add(['requisition_type_id'=>2]);
		$casreq=$this->casreq->create([
            'requisition_no'=>$requisition_no,
            'requisition_type_id'=>$request->requisition_type_id,
            'company_id'=>$request->company_id,
            'location_id'=>$request->location_id,
            'req_date'=>$request->req_date,
            'currency_id'=>$request->currency_id,
            'disburse_by'=>$request->disburse_by,
            'demand_by_id'=>$request->demand_by_id,
            'price_verified_by_id'=>$request->price_verified_by_id,
            'ready_to_approve_id'=>0,
            'remarks'=>$request->remarks
        ]);
		if($casreq){
			return response()->json(array('success' => true,'id' =>  $casreq->id, 'requisition_no' => $requisition_no , 'message' => 'Save Successfully'),200);
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
        $casreq = $this->casreq->find($id);
        $row ['fromData'] = $casreq;
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
    public function update(InvCasReqRequest $request, $id) {
        $casreq=$this->casreq->update($id,$request->except(['id','requisition_no','company_id']));
        if($casreq){
            return response()->json(array('success'=> true, 'id' =>$id, 'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->casreq->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getCrPdf(){
        $id = request('id',0);
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $rows=$this->casreq
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_pur_reqs.company_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','inv_pur_reqs.currency_id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','inv_pur_reqs.created_by');
        })
        ->leftJoin('users as updated_user',function($join){
            $join->on('updated_user.id','=','inv_pur_reqs.updated_by');
        })

        ->leftJoin('users as demand_user',function($join){
            $join->on('demand_user.id','=','inv_pur_reqs.demand_by_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('demand_user.id','=','employee_h_rs.user_id');
        })

        ->leftJoin('users as price_varify_user',function($join){
            $join->on('price_varify_user.id','=','inv_pur_reqs.price_verified_by_id');
        })
        ->leftJoin('employee_h_rs as varify_emp',function($join){
            $join->on('price_varify_user.id','=','varify_emp.user_id');
        })
        ->where([['inv_pur_reqs.id','=',$id]])
        ->get([
            'inv_pur_reqs.*',
            'companies.id as company_id',
            'companies.name as company_name',
            'currencies.code as currency_name',
            'users.name as user_name',
            'updated_user.name as update_user_name',
            'employee_h_rs.name as demand_user_name',
            'employee_h_rs.contact as demand_contact',
            'employee_h_rs.designation_id as dd_designation',
            'varify_emp.name as price_varify_user_name',
            'varify_emp.contact as price_varify_user_contact',
            'varify_emp.designation_id as pv_designation',
        ]);
        
        foreach($rows as $row){
            $casreq['id']=$row->id;
            $casreq['requisition_no']=$row->requisition_no;
            $casreq['requisition_type_id']=$row->requisition_type_id;
            $casreq['company_id']=$row->company_id;
            $casreq['req_date']=date('d-M-Y',strtotime($row->req_date));
            $casreq['disburse_by']=date('d-M-Y',strtotime($row->disburse_by));
           // $casreq['pay_mode']=isset($paymode[$row->pay_mode])?$paymode[$row->pay_mode]:'';
            $casreq['currency_name']=$row->currency_name;
            //$casreq['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
            $casreq['remarks']=$row->remarks;

            $casreq['user_name']=$row->user_name;
            $casreq['created_at']=date('d-M-Y',strtotime($row->created_at)); 
            $created_at=strtotime($row->created_at);
            $updated_at=strtotime($row->updated_at);
            if($created_at==$updated_at){
                $casreq['update_user_name']='';
                $casreq['updated_at']='';
            }else{
                $casreq['update_user_name']=$row->update_user_name;
                $casreq['updated_at']=date('d-M-Y',strtotime($row->updated_at));
            }
            $casreq['demand_user_name']=$row->demand_user_name;
            $casreq['demand_contact']=$row->demand_contact;
            $casreq['price_varify_user_name']=$row->price_varify_user_name;
            $casreq['price_varify_user_contact']=$row->price_varify_user_contact;
            $casreq['dd_designation']=$designation[$row->dd_designation];
            $casreq['pv_designation']=$designation[$row->pv_designation];
            
        }
        
        $casreqitem=$this->casreq
        ->selectRaw('
            inv_pur_reqs.id as inv_pur_req_id,
            inv_pur_reqs.currency_id,
            inv_cas_req_items.item_description, 
            inv_cas_req_items.remarks as item_remarks,
            inv_cas_req_items.uom_id,        
            uoms.code as uom_code,
            sum(inv_cas_req_items.qty) as item_qty,   
            avg(inv_cas_req_items.rate) as item_rate,
            sum(inv_cas_req_items.amount) as item_amount
        ')
        ->join('inv_cas_req_items',function($join){
            $join->on('inv_pur_reqs.id','=','inv_cas_req_items.inv_pur_req_id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','inv_cas_req_items.uom_id');
        })
        ->where([['inv_pur_reqs.id','=',$id]])
        ->groupBy([
            'inv_pur_reqs.id',
            'inv_pur_reqs.currency_id',
            'inv_cas_req_items.item_description',  
            'inv_cas_req_items.remarks',
            'inv_cas_req_items.uom_id',
            'uoms.code',
            
        ])
        ->get()
        ->map(function($casreqitem){
           $casreqitem->currency_name=$casreqitem->currency_name;
          // $max=$casreqitem->max('id');
          //$casreqitem->item_amount=number_format($casreqitem->item_amount,2);
          // $grandTotal=$casreqitem->item_amount;
        return $casreqitem;
        });

           
        $company=$this->company->where([['id','=',$casreq['company_id']]])->get()->first();

        $casreqpaid=$this->casreq
            ->join('inv_cas_req_paids',function($join){
                $join->on('inv_pur_reqs.id','=','inv_cas_req_paids.inv_pur_req_id');
            })
            ->leftJoin('users',function($join){
                $join->on('users.id','=','inv_cas_req_paids.user_id');
            })
            ->leftJoin('users as updated_user',function($join){
                $join->on('updated_user.id','=','inv_cas_req_paids.updated_by');
            })
            ->leftJoin(\DB::raw("(SELECT 
                    inv_pur_reqs.id as inv_pur_req_id,
                    sum(inv_cas_req_paids.amount) as paid_amount
                FROM inv_pur_reqs
                join inv_cas_req_paids on inv_pur_reqs.id=inv_cas_req_paids.inv_pur_req_id
                GROUP BY
                inv_pur_reqs.id ) paid"),'paid.inv_pur_req_id','=','inv_pur_reqs.id')
            ->where([['inv_pur_reqs.id','=',$id]])
            ->get([
                'inv_pur_reqs.id as inv_pur_req_id',
                'inv_cas_req_paids.user_id',
                'inv_cas_req_paids.amount as paid_amount',
                'inv_cas_req_paids.paid_date',
                'users.name as user_name',
                'updated_user.name as updatedby_user_name',
                'inv_cas_req_paids.updated_by',
                'inv_cas_req_paids.updated_at as entry_date',
            ])
            ->map(function($casreqpaid){
                $casreqpaid->paid_date=date('d-M-Y',strtotime($casreqpaid->paid_date));
                $casreqpaid->entry_date=date('d-M-Y',strtotime($casreqpaid->entry_date));
               // $casreqpaid->paid_amount=number_format($casreqpaid->paid_amount,2);
                return $casreqpaid;
        });

        $item_amount=$casreqitem->sum('item_amount');
        $paid_amount=$casreqpaid->sum('paid_amount');
        $amount=$item_amount-$paid_amount;
        $inword=Numbertowords::ntow(number_format($item_amount,2,'.',''),$row->currency_name);
        $casreqitem->inword=$inword;

        //$max=$casreqitem->max('id');
        //$casreqitem->last_id=$max-1;

        //$casreqitem->last_item_qty=$this->casreqitem->where([['id' ,'=', 'last_id']])->get('sum(inv_cas_req_items.qty) as last_item_qty');

        //$max=$this->casreqitem/* ->where([['id' , $request->id]]) */->max(['id']);
        //$lastRequisition=$max-1;

        $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $header=['logo'=>$company->logo,'address'=>$company->address,'title'=>''];
        $pdf->setCustomHeader($header);
        $pdf->SetPrintHeader(true);
        //$pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        /* $pdf->SetY(10);
        $txt = $prodgmtdlvinput['screenPrint']->supplier_name;
        $pdf->Write(0, 'Challan', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetY(5);
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle($txt); */   
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Inventory.GeneralStore.InvCasReqPdf',['casreq'=>$casreq,'casreqitem'=>$casreqitem,'casreqpaid'=>$casreqpaid,'paid_amount'=>
        $paid_amount/* ,'lastRequisition'=>$lastRequisition */]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvCasReqPdf.pdf';
        $pdf->output($filename);
        exit();
    }
}
