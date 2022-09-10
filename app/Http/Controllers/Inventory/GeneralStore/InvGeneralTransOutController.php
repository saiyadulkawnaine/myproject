<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvGeneralTransOutRequest;

class InvGeneralTransOutController extends Controller {

    private $invisu;
    private $company;
    private $store;
    private $itemaccount;

    public function __construct(
        InvIsuRepository $invisu,
        CompanyRepository $company, 
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invisu = $invisu;
        $this->company = $company;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        //$this->middleware('permission:view.invyarnisu',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invyarnisu', ['only' => ['store']]);
        //$this->middleware('permission:edit.invyarnisu',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invyarnisu', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $rows = $this->invisu
      ->join('companies',function($join){
          $join->on('companies.id','=','inv_isus.company_id');
      })
      ->join('companies as tocompanies',function($join){
          $join->on('tocompanies.id','=','inv_isus.to_company_id');
      })
      ->orderBy('inv_isus.id','desc')
      ->where([['inv_isus.menu_id','=',206]])
      ->get([
        'inv_isus.*',
        'companies.name as company_name',
        'tocompanies.name as to_company_name'
      ])
      ->map(function($rows){
        $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
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
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.GeneralStore.InvGeneralTransOut',['company'=>$company,'store'=>$store]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvGeneralTransOutRequest $request) {
        $max=$this->invisu
        ->where([['company_id','=',$request->company_id]])
        ->whereIn('menu_id',[203,204,206])
        ->max('issue_no');
        $issue_no=$max+1;

        $invisu=$this->invisu->create([
            'menu_id'=>206,
            'issue_no'=>$issue_no,
            'company_id'=>$request->company_id,
            'to_company_id'=>$request->to_company_id,
            'isu_basis_id'=>9,
            'isu_against_id'=>0,
            'issue_date'=>$request->issue_date,
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
        $invyarnisu = $this->invisu
        ->where([['inv_isus.id','=',$id]])
        ->get()
        ->first();
        $row ['fromData'] = $invyarnisu;
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
    public function update(InvGeneralTransOutRequest $request, $id) {
        $invyarntransout=$this->invisu->update($id,$request->except(['id','company_id']));
        if($invyarntransout){
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
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invisu->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getPdf()
    {
      $id=request('id',0);
      $invissuebasis=array_prepend(config('bprs.invissuebasis'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');  

      $rows=$this->invisu
      
      ->join('companies',function($join){
      $join->on('companies.id','=','inv_isus.company_id');
      })
      
      ->join('companies as tocompanies',function($join){
      $join->on('tocompanies.id','=','inv_isus.to_company_id');
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
      'inv_isus.remarks as master_remarks',
      'companies.name as company_name',
      'tocompanies.name as to_company_name',
      'companies.logo as logo',
      'companies.address as company_address',
      'tocompanies.address as to_company_address',
      'users.name as user_name',
      'employee_h_rs.contact'
      ])
      ->first();
      $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));

        $invgeneralisuitem=$this->invisu
        ->join('inv_general_isu_items',function($join){
        $join->on('inv_general_isu_items.inv_isu_id','=','inv_isus.id')
        ->whereNull('inv_general_isu_items.deleted_at');
        })
        
        ->leftJoin('item_accounts',function($join){
        $join->on('item_accounts.id','=','inv_general_isu_items.item_account_id');
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
        $join->on('stores.id','=','inv_general_isu_items.store_id');
        })
        
        ->where([['inv_isus.id','=',$id]])
        ->orderBy('inv_general_isu_items.id','desc')
        ->get([
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.id as item_account_id',
        'item_accounts.sub_class_name',
        'item_accounts.item_description as item_desc',
        'item_accounts.specification',
        'uoms.code as uom_code',
        'stores.name as store_name',
        'inv_general_isu_items.*',
        ])
        ->map(function($invgeneralisuitem) {

        return $invgeneralisuitem;
        });
      
      
      $data['master']    =$rows;
      $data['details']   =$invgeneralisuitem;

      
     

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

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Write(0, 'General Item Transfer Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('General Item Transfer Report');
      $view= \View::make('Defult.Inventory.GeneralStore.GeneralTransOutPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(45);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/GeneralTransOutPdf.pdf';
      $pdf->output($filename);
    }
}