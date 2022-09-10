<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;

use App\Library\Numbertowords;
//use Illuminate\Support\Facades\DB;
//use App\Library\pdf;

use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRequest;

class InvDyeChemIsuController extends Controller {

    private $invisu;
    private $invdyechemisu;
    private $company;
    private $location;
    private $store;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;
    private $department;
    private $invdyechemisurq;
    private $embelishmenttype;

    public function __construct(
        InvIsuRepository $invisu,
        InvDyeChemIsuRepository $invdyechemisu,
        CompanyRepository $company,
        LocationRepository $location,
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        ItemclassRepository $itemclass,
        ItemcategoryRepository $itemcategory,
        DepartmentRepository $department,
        InvDyeChemIsuRqRepository $invdyechemisurq,
       EmbelishmentTypeRepository $embelishmenttype

    ) {
        $this->invisu = $invisu;
        $this->invdyechemisu = $invdyechemisu;
        $this->company = $company;
        $this->location = $location;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->department = $department;
        $this->invdyechemisurq = $invdyechemisurq;
        $this->embelishmenttype = $embelishmenttype;
        $this->middleware('auth');
        
        $this->middleware('permission:view.invdyechemisus',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisus', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisus',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisus', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->invisu
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_isus.company_id');
        })
        ->join('locations',function($join){
            $join->on('locations.id','=','inv_isus.location_id');
        })
        ->where([['inv_isus.menu_id','=',212]])
        ->orderBy('inv_isus.id','desc')
        ->take(1000)
        ->get([
            'inv_isus.*',
            'companies.code as company_code',
            'locations.name as location_name',
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
        $menu=array_prepend(array_only(config('bprs.menu'),[208,209,210,211,223]),'-Select-','');

        return Template::loadView('Inventory.DyeChem.InvDyeChemIsu', ['company'=>$company,'location'=>$location,'store'=>$store,'department'=>$department,'generalisurqpurpose'=>$generalisurqpurpose,'menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemIsuRequest $request) {
        $max=$this->invisu
        ->where([['company_id','=',$request->company_id]])
        ->whereIn('menu_id',[212,213,215])
        ->max('issue_no');
        $issue_no=$max+1;

        $invisu=$this->invisu->create([
            'menu_id'=>212,
            'issue_no'=>$issue_no,
            'company_id'=>$request->company_id,
            'location_id'=>$request->location_id,
            'isu_basis_id'=>1,
            'isu_against_id'=>$request->isu_against_id,
            'issue_date'=>$request->issue_date,
            'remarks'=>$request->remarks,
        ]);

        if($invisu){
            return response()->json(array('success' =>true ,'id'=>$invisu->id, 'issue_no'=>$issue_no,'message'=>'Saved Successfully'),200);
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
        $invisu = $this->invisu->find($id);
        /*$invisu=$this->invisu
        ->join('companies',function($join){
        $join->on('companies.id','=','inv_isus.company_id');
        })
        ->join('locations',function($join){
        $join->on('locations.id','=','inv_isus.location_id');
        })
        ->join('inv_dye_chem_isu_rqs',function($join){
        $join->on('inv_dye_chem_isu_rqs.id','=','inv_isus.inv_dye_chem_isu_rq_id');
        })
        ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
        })
        ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->where([['inv_isus.menu_id','=',212]])
        ->orderBy('inv_isus.id','desc')
        ->get([
        'inv_isus.*',
        'inv_dye_chem_isu_rqs.rq_no',
        'inv_dye_chem_isu_rqs.rq_date',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'colors.name as fabric_color',
        ])->first();*/
        $row ['fromData'] = $invisu;
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
    public function update(InvDyeChemIsuRequest $request, $id) {
        $invisu=$this->invisu->update($id,$request->except(['id','company_id','rq_no','isu_against_id']));
        if($invisu){
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

        /*$req=$this->invisu->find($id);
        if($req->first_approved_by){
            return response()->json(array('success' => false,'message' => 'This Requisition is approved so delete not allowed'),200);

        }*/
        if($this->invisu->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }

    public function getRequisition()
    {

      $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
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
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->when(request('rq_no'), function ($q) {
      return $q->where('inv_dye_chem_isu_rqs.rq_no', '=', request('rq_no', 0));
      })
      ->when(request('company_id'), function ($q) {
      return $q->where('inv_dye_chem_isu_rqs.company_id', '=', request('company_id', 0));
      })
      ->when(request('location_id'), function ($q) {
      return $q->where('inv_dye_chem_isu_rqs.location_id', '=', request('location_id', 0));
      })
      ->when(request('batch_no'), function ($q) {
      return $q->where('prod_batches.batch_no', '=', request('batch_no', 0));
      })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'companies.code as company_name',
        'buyers.name as buyer_name',
        'locations.name as location_name',
       ])
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    public function getDyeChemIsuList() {
        $rows=$this->invisu
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_isus.company_id');
        })
        ->join('locations',function($join){
            $join->on('locations.id','=','inv_isus.location_id');
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
        })
        ->where([['inv_isus.menu_id','=',212]])
        ->orderBy('inv_isus.id','desc')
        ->get([
            'inv_isus.*',
            'companies.code as company_code',
            'locations.name as location_name',
        ]);
        echo json_encode($rows);
    }

    public function getPdf(){
        $id = request('id',0);
        $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'),'-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');

        $rows=$this->invisu
        ->join('companies',function($join){
        $join->on('companies.id','=','inv_isus.company_id');
        })
        ->join('locations',function($join){
        $join->on('locations.id','=','inv_isus.location_id');
        })
        ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','inv_isus.supplier_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','inv_isus.created_by');
        })
        ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->where([['inv_isus.id','=',$id]])
        ->get([
        'inv_isus.*',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'suppliers.name as supplier_name',
        'suppliers.address as supplier_address',
        'locations.name as location_name',
        'users.name as user_name',
        'employee_h_rs.contact'
        ])
        ->first();
        $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));

        


        $invdyechemisuitem=$this->invisu
        ->join('inv_dye_chem_isu_items',function($join){
        $join->on('inv_dye_chem_isu_items.inv_isu_id','=','inv_isus.id')
        ->whereNull('inv_dye_chem_isu_items.deleted_at');
        })
        ->join('inv_dye_chem_isu_rq_items',function($join){
        $join->on('inv_dye_chem_isu_rq_items.id','=','inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id');
        })
        
        ->leftJoin('item_accounts',function($join){
        $join->on('item_accounts.id','=','inv_dye_chem_isu_rq_items.item_account_id');
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
        $join->on('stores.id','=','inv_dye_chem_isu_items.store_id');
        })
        ->leftJoin('so_aops',function($join){
        $join->on('so_aops.id','=','inv_dye_chem_isu_rq_items.so_aop_id');
        })
        
        ->where([['inv_isus.id','=',$id]])
        ->orderBy('inv_dye_chem_isu_items.id','desc')
        ->get([
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.id as item_account_id',
        'item_accounts.sub_class_name',
        'item_accounts.item_description as item_desc',
        'item_accounts.specification',
        'uoms.code as uom_code',
        'stores.name as store_name',
        'inv_dye_chem_isu_items.*',
        'inv_dye_chem_isu_rq_items.sub_process_id',
        'so_aops.sales_order_no',
        ])
        ->map(function($invdyechemisuitem) use($dyeingsubprocess,$aoptype) {
        if($invdyechemisuitem->sub_process_id){
           $invdyechemisuitem->sub_process_name=$dyeingsubprocess[$invdyechemisuitem->sub_process_id];
        }
        if($invdyechemisuitem->print_type_id){
           $invdyechemisuitem->sub_process_name=$aoptype[$invdyechemisuitem->print_type_id];
        }
        return $invdyechemisuitem;
        }); 
        $data['master']    =$rows;
        $data['details']   =$invdyechemisuitem;
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
        $image_file ='images/logo/'.$rows->logo;
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 8);
        //$pdf->Text(115, 12, $rows->company_address);
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
        $pdf->Write(0, 'Dyes & Chemical  Issue  Challan/Gate Pass ', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Dyes & Chemical  Issue  Challan/Gate Pass');
        $view= \View::make('Defult.Inventory.DyeChem.DyeChemIsuPdf',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(45);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/DyeChemIsuPdf.pdf';
        $pdf->output($filename);
    }

}
