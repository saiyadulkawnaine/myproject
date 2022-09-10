<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqLoanRequest;

class InvDyeChemIsuRqLoanController extends Controller {

    private $invdyechemisurq;
    private $company;
    private $buyer;
    private $supplier;
    private $location;
    private $itemaccount;
    private $autoyarn;
    private $colorrange;
    private $prodbatch;
    private $color;

    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        CompanyRepository $company, 
        BuyerRepository $buyer, 
        SupplierRepository $supplier, 
        LocationRepository $location,
        ItemAccountRepository $itemaccount,
        AutoyarnRepository $autoyarn,
        ColorrangeRepository $colorrange,
        ProdBatchRepository $prodbatch,
        ColorRepository $color
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->location = $location;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;
        $this->prodbatch = $prodbatch;
        $this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurqloan',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqloan', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqloan',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqloan', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $rows = $this->invdyechemisurq
       
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
       })
       
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','inv_dye_chem_isu_rqs.supplier_id');
       })
       
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->whereIn('inv_dye_chem_isu_rqs.rq_basis_id',[5,6,7,8])
         ->where([['inv_dye_chem_isu_rqs.menu_id','=',211]])
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'companies.code as company_id',
        'locations.name as location_id',
        'suppliers.name as supplier_id',
       ])
       ->take(100)
       ->map(function($rows){
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

      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $buyer = array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $supplier = array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
      $dyechemrequisitionbasis=array_prepend(array_only(config('bprs.dyechemrequisitionbasis'),[5,6,7,8]), '-Select-','');


      return Template::loadView('Inventory.DyeChem.InvDyeChemIsuRqLoan',['company'=>$company,'location'=>$location, 'buyer'=>$buyer, 'colorrange'=>$colorrange,'dyeingsubprocess'=>$dyeingsubprocess,'dyechemrequisitionbasis'=>$dyechemrequisitionbasis,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemIsuRqLoanRequest $request) {
      $max=$this->invdyechemisurq
      ->where([['company_id','=',$request->company_id]])
      ->max('rq_no');
      $rq_no=$max+1;
      
      
      $invdyechemisurq=$this->invdyechemisurq->create([
        'rq_no'=>$rq_no,
        'menu_id'=>211,
        'company_id'=>$request->company_id,
        'location_id'=>$request->location_id,
        'rq_basis_id'=>$request->rq_basis_id,
        'supplier_id'=>$request->supplier_id,
        'rq_date'=>$request->rq_date,
        'paste_wgt'=>$request->paste_wgt,
        'remarks'=>$request->remarks,
      ]);
      if($invdyechemisurq){
        return response()->json(array('success' =>true ,'id'=>$invdyechemisurq->id, 'rq_no'=>$rq_no,'message'=>'Saved Successfully'),200);
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
        $rows = $this->invdyechemisurq
       
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
       })
       
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','inv_dye_chem_isu_rqs.supplier_id');
       })
       ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
       ])
       ->map(function($rows){
        return $rows;
       })->first();
        $row ['fromData'] = $rows;
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
    public function update(InvDyeChemIsuRqLoanRequest $request, $id) {
      $invdyechemisurq=$this->invdyechemisurq->update($id,[
        'location_id'=>$request->location_id,
        'rq_basis_id'=>$request->rq_basis_id,
        'supplier_id'=>$request->supplier_id,
        'rq_date'=>$request->rq_date,
        'paste_wgt'=>$request->paste_wgt,
        'remarks'=>$request->remarks,
      ]);
      if($invdyechemisurq){
        return response()->json(array('success' =>true ,'id'=>$id, 'message'=>'Saved Successfully'),200);
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
        return response()->json(array('success'=>false,'message'=>'Deleted not Successfully'),200);
        if($this->invdyechemisurq->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getRq() {
       $rows = $this->invdyechemisurq
       
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
       })
       
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','inv_dye_chem_isu_rqs.supplier_id');
       })
       
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
        ->whereIn('inv_dye_chem_isu_rqs.rq_basis_id',[5,6,7,8])
        ->where([['inv_dye_chem_isu_rqs.menu_id','=',211]])
        ->when(request('from_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '>=', request('from_rq_date', 0));
        })
        ->when(request('to_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '<=', request('to_rq_date', 0));
        })
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'companies.code as company_id',
        'locations.name as location_id',
        'suppliers.name as supplier_id',
       ])
       
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    public function getPdf()
    {
      $id=request('id',0);
      $dyechemrequisitionbasis=array_prepend(array_only(config('bprs.dyechemrequisitionbasis'),[5,6,7,8]), '-Select-','');
      $rows=$this->invdyechemisurq
      ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
      })
      ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','inv_dye_chem_isu_rqs.supplier_id');
      })
      ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','inv_dye_chem_isu_rqs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
      ->get([
        'inv_dye_chem_isu_rqs.*',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'suppliers.name as supplier_name',
        'suppliers.address as supplier_address',
        'locations.name as location_id',
        'users.name as user_name',
        'employee_h_rs.contact'
      ])
      ->first();
      $rows->rq_date=date('d-M-Y',strtotime($rows->rq_date));
      $rows->rq_for=$dyechemrequisitionbasis[$rows->rq_basis_id];

      $invdyechemisurqitem=$this->invdyechemisurq
      ->join('inv_dye_chem_isu_rq_items',function($join){
      $join->on('inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id','=','inv_dye_chem_isu_rqs.id');
      })
      ->join('item_accounts',function($join){
      $join->on('inv_dye_chem_isu_rq_items.item_account_id','=','item_accounts.id');
      })
      ->join('itemclasses', function($join){
      $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
      })
      ->join('itemcategories', function($join){
      $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
      })
      ->leftJoin('uoms', function($join){
      $join->on('uoms.id', '=', 'item_accounts.uom_id');
      })
      ->leftJoin('asset_quantity_costs',function($join){
      $join->on('asset_quantity_costs.id','=','inv_dye_chem_isu_rq_items.asset_quantity_cost_id');
      })
      ->leftJoin(\DB::raw("(SELECT 
      inv_dye_chem_transactions.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty 
      FROM inv_dye_chem_transactions 
      where  inv_dye_chem_transactions.deleted_at is null
      group by inv_dye_chem_transactions.item_account_id
      ) stock"), "stock.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(SELECT 
      inv_dye_chem_transactions.item_account_id,
      sum(inv_dye_chem_transactions.store_amount) as receive_amount 
      FROM inv_dye_chem_transactions 
      where trans_type_id=1 and 
      inv_dye_chem_transactions.deleted_at is null
      group by inv_dye_chem_transactions.item_account_id
      ) receives"), "receives.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(SELECT 
      inv_dye_chem_transactions.item_account_id,
      sum(inv_dye_chem_transactions.store_amount) as issue_amount 
      FROM inv_dye_chem_transactions 
      where trans_type_id=2 and 
      inv_dye_chem_transactions.deleted_at is null
      group by inv_dye_chem_transactions.item_account_id
      ) issues"), "issues.item_account_id", "=", "item_accounts.id")
      ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
      ->orderBy('inv_dye_chem_isu_rq_items.id')
      ->orderBy('inv_dye_chem_isu_rq_items.sort_id')
      ->get([
      'inv_dye_chem_isu_rq_items.*',
      'itemcategories.name as category_name',
      'itemclasses.name as class_name',
      'item_accounts.sub_class_name',
      'item_accounts.item_description',
      'item_accounts.specification',
      'uoms.code as uom_name',
      'asset_quantity_costs.custom_no',
      'stock.qty as stock_qty',
      'receives.receive_amount',
      'issues.issue_amount',
      ]) 
      ->map(function($invdyechemisurqitem) {
        $invdyechemisurqitem->stock_amount=$invdyechemisurqitem->receive_amount-$invdyechemisurqitem->issue_amount;
        $invdyechemisurqitem->stock_rate=0;
        if($invdyechemisurqitem->stock_qty){
          $invdyechemisurqitem->stock_rate=number_format($invdyechemisurqitem->stock_amount/$invdyechemisurqitem->stock_qty,2);
        }
        return $invdyechemisurqitem;
      });
      
      $data['master']    =$rows;
      $data['details']   =$invdyechemisurqitem;

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
      $pdf->SetX(200);
      $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Dyes & Chemicals Issue Requisition', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Dyes & Chemicals Issue Requisition');
      $view= \View::make('Defult.Inventory.DyeChem.DyeChemIsuRqLoanPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/DyeChemIsuRqLoanPdf.pdf';
      $pdf->output($filename);
    }
}