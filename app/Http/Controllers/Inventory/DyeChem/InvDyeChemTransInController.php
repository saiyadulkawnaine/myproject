<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemTransInRequest;

class InvDyeChemTransInController extends Controller {

    private $invrcv;
    private $invdyechemrcv;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvDyeChemRcvRepository $invdyechemrcv, 
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invdyechemrcv = $invdyechemrcv;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemtransin',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemtransin', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemtransin',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemtransin', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $rows = $this->invrcv
       ->join('inv_dye_chem_rcvs',function($join){
        $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
       })
       ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
       })
       ->where([['inv_rcvs.menu_id','=',216]])
       ->orderBy('inv_rcvs.id','desc')
       ->get([
        'inv_rcvs.*',
        'inv_dye_chem_rcvs.id as inv_dye_chem_rcv_id',
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
      $invreceivebasis=array_prepend(array_only(config('bprs.invreceivebasis'),[1,2,3,9,10]),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[0,8,109]),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->GeneralItemSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.DyeChem.InvDyeChemTransIn',['company'=>$company,'currency'=>$currency, 'invreceivebasis'=>$invreceivebasis,'supplier'=>$supplier,'store'=>$store,'menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemTransInRequest $request) {
      $max=$this->invrcv
      ->where([['company_id','=',$request->company_id]])
      //->where([['menu_id','=',100]])
      ->whereIn('menu_id',[200,214,216])
      ->max('receive_no');
      $receive_no=$max+1;

      

      $invrcv=$this->invrcv->create([
        'menu_id'=>216,
        'receive_no'=>$receive_no,
        'company_id'=>$request->company_id,
        'from_company_id'=>$request->from_company_id,
        'receive_basis_id'=>9,
        'receive_against_id'=>0,
        'supplier_id'=>26,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        //'currency_id'=>$request->currency_id,
        //'exch_rate'=>$request->exch_rate
      ]);

      $invdyechemrcv=$this->invdyechemrcv->create([
        'inv_rcv_id'=>$invrcv->id,
      ]);
      if($invdyechemrcv){
        return response()->json(array('success' =>true ,'id'=>$invdyechemrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invdyechemrcv = $this->invrcv
        ->join('inv_dye_chem_rcvs',function($join){
            $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_dye_chem_rcvs.id  as inv_dye_chem_rcv_id'
        ])
        ->first();
        $row ['fromData'] = $invdyechemrcv;
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
    public function update(InvDyeChemTransInRequest $request, $id) {
        $invdyechemrcv=$this->invrcv->update($id,$request->except(['id','inv_dye_chem_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id','from_company_id']));
        if($invdyechemrcv){
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
      ->join('inv_dye_chem_rcvs',function($join){
      $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->join('companies',function($join){
      $join->on('companies.id','=','inv_rcvs.company_id');
      })
      
      ->join('companies as fcompanies',function($join){
      $join->on('fcompanies.id','=','inv_rcvs.from_company_id');
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
      'fcompanies.name as fcompany_name',
      'fcompanies.address as fcompany_address',
      'users.name as user_name',
      'employee_h_rs.contact'
      ])
      ->first();
        $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
        $rows->receive_against_id=$menu[$rows->receive_against_id];
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));

        $invdyechemrcvitem=$this->invrcv
        ->join('inv_dye_chem_rcvs',function($join){
            $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_dye_chem_rcv_items',function($join){
            $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id')
            ->whereNull('inv_dye_chem_rcv_items.deleted_at');
        })
       ->join('inv_dye_chem_isu_items',function($join){
        $join->on('inv_dye_chem_isu_items.id','=','inv_dye_chem_rcv_items.inv_dye_chem_isu_item_id');
        })
        ->join('inv_isus',function($join){
        $join->on('inv_isus.id','=','inv_dye_chem_isu_items.inv_isu_id');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_dye_chem_isu_items.item_account_id','=','item_accounts.id');
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
        

        ->leftJoin('stores',function($join){
        $join->on('stores.id','=','inv_dye_chem_rcv_items.store_id');
        })

        
        ->where([['inv_rcvs.id','=',$id]])
        ->orderBy('inv_dye_chem_rcv_items.id','desc')
       ->get([
          'inv_isus.issue_no',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.id as item_account_id',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'stores.name as store_name',
          'inv_dye_chem_rcv_items.id',
          'inv_dye_chem_rcv_items.batch',
          'inv_dye_chem_rcv_items.qty',
          'inv_dye_chem_rcv_items.rate',
          'inv_dye_chem_rcv_items.amount',
          'inv_dye_chem_rcv_items.store_rate',
          'inv_dye_chem_rcv_items.store_amount',
          'inv_rcvs.receive_basis_id',
        ])
        ->map(function($invdyechemrcvitem) {
            return $invdyechemrcvitem;
        }); 
      
      $data['master']    =$rows;
      $data['details']   =$invdyechemrcvitem;

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
      //$pdf->Write(0, $rows->company_address, '', 0, 'C', true, 0, false, false, 0);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );

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
        $pdf->SetX(200);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Dyes & Chemical Transfer In ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Dyes & Chemical Transfer In');
      $view= \View::make('Defult.Inventory.DyeChem.DyeChemTransInPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(42);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/DyeChemTransInPdf.pdf';
      $pdf->output($filename);

    }
}