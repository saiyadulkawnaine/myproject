<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvGeneralRcvRequest;

class InvGeneralRcvController extends Controller {

    private $invrcv;
    private $invgeneralrcv;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvGeneralRcvRepository $invgeneralrcv, 
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invgeneralrcv = $invgeneralrcv;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        //$this->middleware('permission:view.invgeneralrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invgeneralrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invgeneralrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invgeneralrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $rows = $this->invrcv
       ->join('inv_general_rcvs',function($join){
        $join->on('inv_general_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
       })
       ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
       })
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','inv_rcvs.location_id');
       })
       ->where([['inv_rcvs.menu_id','=',201]])
       ->orderBy('inv_rcvs.id','desc')
       ->get([
        'inv_rcvs.*',
        'inv_general_rcvs.id as inv_general_rcv_id',
        'companies.code as company_id',
        'suppliers.name as supplier_id',
        'locations.name as location_id',
       ])
       ->map(function($rows) use($invreceivebasis){
        $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
        return $rows;
       });
      echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $invreceivebasis=array_prepend(array_only(config('bprs.invreceivebasis'),[1,2,3]),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[0,8,109]),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->GeneralItemSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.GeneralStore.InvGeneralRcv',['company'=>$company,'currency'=>$currency, 'invreceivebasis'=>$invreceivebasis,'supplier'=>$supplier,'store'=>$store,'menu'=>$menu,'location'=>$location]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvGeneralRcvRequest $request) {
      $max=$this->invrcv
      ->where([['company_id','=',$request->company_id]])
      ->whereIn('menu_id',[201,205,207])
      ->max('receive_no');
      $receive_no=$max+1;

      if($request->receive_basis_id==2 || $request->receive_basis_id==3){
        $request->receive_against_id=0;
      }

      if($request->receive_against_id==7){
        $request->receive_basis_id=1;
      }

      $invrcv=$this->invrcv->create([
        'menu_id'=>201,
        'receive_no'=>$receive_no,
        'company_id'=>$request->company_id,
        'location_id'=>$request->location_id,
        'receive_basis_id'=>$request->receive_basis_id,
        'receive_against_id'=>$request->receive_against_id,
        'supplier_id'=>$request->supplier_id,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'currency_id'=>$request->currency_id,
        'exch_rate'=>$request->exch_rate,
        'remarks'=>$request->remarks
      ]);

      $invgeneralrcv=$this->invgeneralrcv->create([
        'inv_rcv_id'=>$invrcv->id,
      ]);
      if($invgeneralrcv){
        return response()->json(array('success' =>true ,'id'=>$invgeneralrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invgeneralrcv = $this->invrcv
        ->join('inv_general_rcvs',function($join){
            $join->on('inv_general_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_general_rcvs.id  as inv_general_rcv_id'
        ])
        ->first();
        $row ['fromData'] = $invgeneralrcv;
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
    public function update(InvGeneralRcvRequest $request, $id) {
        $invgeneralrcv=$this->invrcv->update($id,$request->except(['id','inv_general_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id']));
        if($invgeneralrcv){
            return response()->json(array('success'=> true, 'id' =>$id, 'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getPdf()
    {
      $id=request('id',0);
      $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');

      $rows=$this->invrcv
      ->join('inv_general_rcvs',function($join){
      $join->on('inv_general_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->join('companies',function($join){
      $join->on('companies.id','=','inv_rcvs.company_id');
      })
      
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','inv_rcvs.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','inv_rcvs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['inv_rcvs.id','=',$id]])
      ->get([
      'inv_rcvs.*',
      'companies.name as company_name',
      'companies.logo as logo',
      'companies.address as company_address',
      'suppliers.name as supplier_name',
      'suppliers.address as supplier_address',
      'suppliers.contact_person',
      'suppliers.designation',
      'suppliers.email',
      'users.name as user_name',
      'employee_h_rs.contact'
      ])
      ->first();
        $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
        $rows->receive_against_id=$menu[$rows->receive_against_id];
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
        $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;

        $invgeneralrcvitem=$this->invrcv
        ->join('inv_general_rcvs',function($join){
            $join->on('inv_general_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_general_rcv_items',function($join){
            $join->on('inv_general_rcv_items.inv_general_rcv_id','=','inv_general_rcvs.id')
            ->whereNull('inv_general_rcv_items.deleted_at');
        })
        ->leftJoin('po_general_items',function($join){
            $join->on('po_general_items.id','=','inv_general_rcv_items.po_general_item_id');
        })
        ->leftJoin('po_generals',function($join){
            $join->on('po_generals.id','=','po_general_items.po_general_id');
        })
        ->leftJoin('inv_pur_req_items', function($join){
        $join->on('inv_pur_req_items.id', '=', 'inv_general_rcv_items.inv_pur_req_item_id');
        })
        ->leftJoin('inv_pur_reqs', function($join){
        $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_general_rcv_items.item_account_id');
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
        ->leftJoin('currencies',function($join){
        $join->on('currencies.id','=','po_generals.currency_id');
        })

        ->leftJoin('stores',function($join){
        $join->on('stores.id','=','inv_general_rcv_items.store_id');
        })

        
        ->where([['inv_rcvs.id','=',$id]])
        ->orderBy('inv_general_rcv_items.id','desc')
       ->get([
          'po_generals.po_no',
          'po_generals.pi_no',
          'po_generals.exch_rate',
          'inv_pur_reqs.requisition_no as rq_no',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.id as item_account_id',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'currencies.code as currency_code',
          'stores.name as store_name',
          'inv_general_rcv_items.id',
          'inv_general_rcv_items.batch',
          'inv_general_rcv_items.qty',
          'inv_general_rcv_items.rate',
          'inv_general_rcv_items.amount',
          'inv_general_rcv_items.store_rate',
          'inv_general_rcv_items.store_amount',
          'inv_rcvs.receive_basis_id',
        ])
        ->map(function($invgeneralrcvitem) {
          if(!$invgeneralrcvitem->po_no)
          {
            $invgeneralrcvitem->po_no=$invgeneralrcvitem->rq_no;
          }
            
            return $invgeneralrcvitem;
        }); 
      
      $data['master']    =$rows;
      $data['details']   =$invgeneralrcvitem;

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
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Text(115, 12, $rows->company_address);
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

      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'General Item Receiving Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('General Item Receiving Report');
      $view= \View::make('Defult.Inventory.GeneralStore.GeneralRcvPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/GeneralRcvPdf.pdf';
      $pdf->output($filename);

    }
}