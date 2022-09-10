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
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqAopRequest;

class InvDyeChemIsuRqAopController extends Controller {

    private $invdyechemisurq;
    private $company;
    private $buyer;
    private $location;
    private $itemaccount;
    private $autoyarn;
    private $colorrange;
    private $prodbatch;
    private $color;
    private $embelishmenttype;
    private $prodaopbatch;

    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        ProdAopBatchRepository $prodaopbatch,
        CompanyRepository $company, 
        BuyerRepository $buyer, 
        LocationRepository $location,
        ItemAccountRepository $itemaccount,
        AutoyarnRepository $autoyarn,
        ColorrangeRepository $colorrange,
        ProdBatchRepository $prodbatch,
        ColorRepository $color,
        EmbelishmentTypeRepository $embelishmenttype
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->prodaopbatch = $prodaopbatch;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;
        $this->prodbatch = $prodbatch;
        $this->color = $color;
        $this->embelishmenttype = $embelishmenttype;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurqaop',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqaop', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqaop',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqaop', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $rows = $this->invdyechemisurq
       ->join('prod_aop_batches',function($join){
        $join->on('prod_aop_batches.id','=','inv_dye_chem_isu_rqs.prod_aop_batch_id');
       })
       ->join('so_aops',function($join){
        $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','so_aops.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','so_aops.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_aop_batches.batch_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
       })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->where([['inv_dye_chem_isu_rqs.rq_basis_id','=',3]])
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',210]])
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'companies.code as company_id',
        'buyers.name as buyer_id',
        'locations.name as location_id',
        'prod_aop_batches.batch_no',
        'prod_aop_batches.design_no'
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
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
      $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');



      return Template::loadView('Inventory.DyeChem.InvDyeChemIsuRqAop',['company'=>$company,'location'=>$location, 'buyer'=>$buyer, 'colorrange'=>$colorrange,'dyeingsubprocess'=>$dyeingsubprocess,'aoptype'=>$aoptype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemIsuRqAopRequest $request) {
      $max=$this->invdyechemisurq
      ->where([['company_id','=',$request->company_id]])
      ->max('rq_no');
      $rq_no=$max+1;
      /*$color_name=strtoupper($request->fabric_color);
      $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);*/
      
      
      $invdyechemisurq=$this->invdyechemisurq->create([
        'rq_no'=>$rq_no,
        'menu_id'=>210,
        'prod_aop_batch_id'=>$request->prod_aop_batch_id,
        //'company_id'=>$request->company_id,
        'location_id'=>$request->location_id,
        //'buyer_id'=>$request->buyer_id,
        'rq_basis_id'=>3,
        //'color_id'=>$color->id,
        'colorrange_id'=>$request->colorrange_id,
        //'design_no'=>$request->design_no,
        'rq_date'=>$request->rq_date,
        'paste_wgt'=>$request->paste_wgt,
        'fabric_wgt'=>$request->fabric_wgt,
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
       ->join('prod_aop_batches',function($join){
        $join->on('prod_aop_batches.id','=','inv_dye_chem_isu_rqs.prod_aop_batch_id');
       })
       ->join('so_aops',function($join){
        $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
       })
       
       ->join('companies',function($join){
        $join->on('companies.id','=','so_aops.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','so_aops.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_aop_batches.batch_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
       })
       ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_aop_batches.batch_no',
        'prod_aop_batches.design_no',
        'so_aops.company_id',
        'so_aops.buyer_id',
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
    public function update(InvDyeChemIsuRqAopRequest $request, $id) {
      $isurq=$this->invdyechemisurq->find($id);
      /*$color_name=strtoupper($request->fabric_color);
      $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);*/
     
      $invdyechemisurq=$this->invdyechemisurq->update($id,[
        //'rq_no'=>$rq_no,
        //'company_id'=>$request->company_id,
        'location_id'=>$request->location_id,
        //'buyer_id'=>$request->buyer_id,
        //'rq_basis_id'=>$request->rq_basis_id,
        //'color_id'=>$color->id,
        'colorrange_id'=>$request->colorrange_id,
        //'design_no'=>$request->design_no,
        'rq_date'=>$request->rq_date,
        'paste_wgt'=>$request->paste_wgt,
        'fabric_wgt'=>$request->fabric_wgt,
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
       ->join('prod_aop_batches',function($join){
        $join->on('prod_aop_batches.id','=','inv_dye_chem_isu_rqs.prod_aop_batch_id');
       })
       ->join('so_aops',function($join){
        $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','so_aops.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','so_aops.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_aop_batches.batch_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
       })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->whereIn('inv_dye_chem_isu_rqs.rq_basis_id',[3])
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',210]])
       ->when(request('from_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '>=', request('from_rq_date', 0));
        })
        ->when(request('to_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '<=', request('to_rq_date', 0));
        })
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'companies.code as company_id',
        'buyers.name as buyer_id',
        'locations.name as location_id',
        'prod_aop_batches.batch_no',
        'prod_aop_batches.design_no',
       ])
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    public function getBatch (){
      $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
      $rows=$this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','so_aops.company_id');
        })
        ->join('buyers',function($join){
          $join->on('buyers.id','=','so_aops.buyer_id');
        })
        ->join('colors as batch_colors',function($join){
          $join->on('batch_colors.id','=','prod_aop_batches.batch_color_id');
        })
        ->when(request('batch_date_from'), function ($q) {
          return $q->where('prod_aop_batches.batch_date', '>=', request('batch_date_from', 0));
        })
        ->when(request('batch_date_to'), function ($q) {
          return $q->where('prod_aop_batches.batch_date', '<=', request('batch_date_to', 0));
        })
        ->orderBy('prod_aop_batches.id','desc')
        ->get([
          'prod_aop_batches.*',
          'so_aops.company_id',
          'so_aops.buyer_id',
          'companies.code as company_code',
          'buyers.name as customer_name',
          'batch_colors.name as batch_color_name',
        ])
        ->map(function($rows) use($batchfor){
          $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
          $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
          return $rows;
        });
        echo json_encode($rows);
    }

    public function getPdf()
    {
      $id=request('id',0);
      $aoptype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');
      $dyechemrequisitionbasis=array_prepend(array_only(config('bprs.dyechemrequisitionbasis'),[3,4]), '-Select-','');


      $rows=$this->invdyechemisurq
      ->join('prod_aop_batches',function($join){
        $join->on('prod_aop_batches.id','=','inv_dye_chem_isu_rqs.prod_aop_batch_id');
       })
       ->join('so_aops',function($join){
        $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','so_aops.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','so_aops.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_aop_batches.batch_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
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
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'buyers.name as buyer_name',
        'locations.name as location_id',
        'users.name as user_name',
        'employee_h_rs.contact',
        'prod_aop_batches.batch_no',
        'prod_aop_batches.design_no',
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
        ->leftJoin('so_aops',function($join){
        $join->on('so_aops.id','=','inv_dye_chem_isu_rq_items.so_aop_id');
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
        'inv_dye_chem_isu_rqs.paste_wgt',
        'stock.qty as stock_qty',
        'receives.receive_amount',
        'issues.issue_amount',
        'so_aops.sales_order_no as sale_order_no',
        ]) 
        ->map(function($invdyechemisurqitem) use ($aoptype){
            $invdyechemisurqitem->print_type=$aoptype[$invdyechemisurqitem->print_type_id];
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

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Write(0, 'Dyes & Chemicals Issue Requisition ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Dyes & Chemicals Issue Requisition');
      $view= \View::make('Defult.Inventory.DyeChem.DyeChemIsuRqAopPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/DyeChemIsuRqPdf.pdf';
      $pdf->output($filename);
    }


    public function oldlist() {
       $rows = $this->invdyechemisurq
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','inv_dye_chem_isu_rqs.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','inv_dye_chem_isu_rqs.color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
       })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->where([['inv_dye_chem_isu_rqs.rq_basis_id','=',3]])
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',210]])
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'companies.code as company_id',
        'buyers.name as buyer_id',
        'locations.name as location_id',
       ])
       /*->take(100)*/
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    public function getPdfOld()
    {
      $id=request('id',0);
      $aoptype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');
      $dyechemrequisitionbasis=array_prepend(array_only(config('bprs.dyechemrequisitionbasis'),[3,4]), '-Select-','');


      $rows=$this->invdyechemisurq
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','inv_dye_chem_isu_rqs.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','inv_dye_chem_isu_rqs.color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
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
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'buyers.name as buyer_name',
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
        ->leftJoin('so_aops',function($join){
        $join->on('so_aops.id','=','inv_dye_chem_isu_rq_items.so_aop_id');
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
        'inv_dye_chem_isu_rqs.paste_wgt',
        'stock.qty as stock_qty',
        'receives.receive_amount',
        'issues.issue_amount',
        'so_aops.sales_order_no as sale_order_no',
        ]) 
        ->map(function($invdyechemisurqitem) use ($aoptype){
            $invdyechemisurqitem->print_type=$aoptype[$invdyechemisurqitem->print_type_id];
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

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Write(0, 'Dyes & Chemicals Issue Requisition ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Dyes & Chemicals Issue Requisition');
      $view= \View::make('Defult.Inventory.DyeChem.DyeChemIsuRqAopPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(42);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/DyeChemIsuRqPdf.pdf';
      $pdf->output($filename);
    }
}