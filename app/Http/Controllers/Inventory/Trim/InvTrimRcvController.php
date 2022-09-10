<?php

namespace App\Http\Controllers\Inventory\Trim;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Trim\InvTrimRcvRequest;

class InvTrimRcvController extends Controller {

    private $invrcv;
    private $invtrimrcv;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvTrimRcvRepository $invtrimrcv, 
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invtrimrcv = $invtrimrcv;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.invtrimrcvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invtrimrcvs', ['only' => ['store']]);
        $this->middleware('permission:edit.invtrimrcvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.invtrimrcvs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $rows = $this->invrcv
       ->join('inv_trim_rcvs',function($join){
        $join->on('inv_trim_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
       })
       ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
       })
       ->where([['inv_rcvs.menu_id','=',300]])
       ->orderBy('inv_rcvs.id','desc')
       ->get([
        'inv_rcvs.*',
        'inv_trim_rcvs.id as inv_trim_rcv_id',
        'companies.code as company_id',
        'suppliers.name as supplier_id',
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
      $invreceivebasis=array_prepend(array_only(config('bprs.invreceivebasis'),[1]),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[2]),'-Select-','');

      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      //$supplier=array_prepend(array_pluck($this->supplier->yarnSupplier(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->trimsSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.Trim.InvTrimRcv',['company'=>$company,'currency'=>$currency, 'invreceivebasis'=>$invreceivebasis,'supplier'=>$supplier,'store'=>$store,'menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvTrimRcvRequest $request) {
      $max=$this->invrcv
      ->where([['company_id','=',$request->company_id]])
      //->where([['menu_id','=',100]])
      ->whereIn('menu_id',[300,301,302])
      ->max('receive_no');
      $receive_no=$max+1;

     

      $invrcv=$this->invrcv->create([
        'menu_id'=>300,
        'receive_no'=>$receive_no,
        'company_id'=>$request->company_id,
        'receive_basis_id'=>1,
        'receive_against_id'=>2,
        'supplier_id'=>$request->supplier_id,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'currency_id'=>$request->currency_id,
        'exch_rate'=>$request->exch_rate
      ]);

      $invtrimrcv=$this->invtrimrcv->create([
        'inv_rcv_id'=>$invrcv->id,
      ]);
      if($invtrimrcv){
        return response()->json(array('success' =>true ,'id'=>$invtrimrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invtrimrcv = $this->invrcv
        ->join('inv_trim_rcvs',function($join){
            $join->on('inv_trim_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_trim_rcvs.id  as inv_trim_rcv_id'
        ])
        ->first();
        $row ['fromData'] = $invtrimrcv;
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
    public function update(InvTrimRcvRequest $request, $id) {
        $invtrimrcv=$this->invrcv->update($id,$request->except(['id','inv_trim_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id']));
        if($invtrimrcv){
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
       return response()->json(array('success'=>false,'message'=>'Deleted Not Successfull'),200);

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
      ->join('inv_trim_rcvs',function($join){
      $join->on('inv_trim_rcvs.inv_rcv_id','=','inv_rcvs.id');
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

        $invtrimrcvitem=$this->invrcv
        ->join('inv_trim_rcvs',function($join){
        $join->on('inv_trim_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_trim_rcv_items',function($join){
        $join->on('inv_trim_rcv_items.inv_trim_rcv_id','=','inv_trim_rcvs.id');
        })
        ->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
        })
        ->join('po_trim_items', function($join){
        $join->on('po_trim_items.id', '=', 'po_trim_item_reports.po_trim_item_id');
        })
        ->join('po_trims', function($join){
        $join->on('po_trim_items.po_trim_id', '=', 'po_trims.id');
        })
       
        ->join('currencies', function($join){
        $join->on('currencies.id', '=', 'po_trims.currency_id');
        })
        ->join('budget_trims', function($join){
        $join->on('budget_trims.id', '=', 'po_trim_items.budget_trim_id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('sales_orders', function($join){
        $join->on('sales_orders.id', '=', 'po_trim_item_reports.sales_order_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'itemclasses.costing_uom_id');
        })
        ->leftJoin('style_colors', function($join){
        $join->on('style_colors.id', '=', 'po_trim_item_reports.style_color_id');
        })
        ->leftJoin('style_sizes', function($join){
        $join->on('style_sizes.id', '=', 'po_trim_item_reports.style_size_id');
        })
        ->leftJoin('colors', function($join){
        $join->on('colors.id', '=', 'style_colors.color_id');
        })
        ->leftJoin('sizes', function($join){
        $join->on('sizes.id', '=', 'style_sizes.size_id');
        })
        ->leftJoin('colors as itemcolors', function($join){
        $join->on('itemcolors.id', '=', 'po_trim_item_reports.trim_color');
        })
        ->leftJoin('stores', function($join){
        $join->on('stores.id', '=', 'inv_trim_rcv_items.store_id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->orderBy('inv_trim_rcvs.id','desc')
        ->get([
        'inv_trim_rcv_items.*',
        'inv_trim_rcvs.id as inv_trim_rcv_id',
        'po_trims.po_no',
        'po_trims.pi_no',
        'po_trims.exch_rate',
        'po_trim_items.id as po_trim_item_id',
        'po_trim_item_reports.id as po_trim_item_report_id',
        'po_trim_item_reports.description',
        'po_trim_item_reports.measurment',
        'po_trim_item_reports.trim_color as trim_color_id',
        'itemcolors.name as item_color_name',
        'colors.name as style_color_name',
        'sizes.name as style_size_name',
        'po_trim_item_reports.qty as po_qty',
        'po_trim_item_reports.rate as po_rate',
        'po_trim_item_reports.amount as po_amount',
        'itemcategories.name as category_name',
        'itemclasses.id as itemclass_id',
        'itemclasses.name as class_name',
        'uoms.code as uom_name',
        'currencies.code as currency_code',
        'sales_orders.sale_order_no',
        'stores.name as store_name',
        ])
        ->map(function($rows){
        return $rows;
        });
      
      $data['master']    =$rows;
      $data['details']   =$invtrimrcvitem;

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
      $pdf->Write(0, 'Trims Receiving Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Trims Receiving Report');
      $view= \View::make('Defult.Inventory.Trim.TrimRcvPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/TrimRcvPdf.pdf';
      $pdf->output($filename);

    }
}