<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnTransOutRequest;

class InvYarnTransOutController extends Controller {

    private $invyarntransout;
    private $invisu;
    private $invyarnisu;
    private $company;
    private $store;
    private $itemaccount;

    public function __construct(
        InvYarnTransOutRepository $invyarntransout,
        InvIsuRepository $invisu,
        InvYarnIsuRepository $invyarnisu,
        CompanyRepository $company, 
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invyarntransout = $invyarntransout;
        $this->invisu = $invisu;
        $this->invyarnisu = $invyarnisu;
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
      ->where([['inv_isus.menu_id','=',107]])
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
      return Template::loadView('Inventory.Yarn.InvYarnTransOut',['company'=>$company,'store'=>$store]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvYarnTransOutRequest $request) {
        /*$max=$this->invyarntransout
        ->where([['company_id','=',$request->company_id]])
        ->max('transfer_no');
        $transfer_no=$max+1;

        $invyarntransout=$this->invyarntransout->create([
            'transfer_no'=>$transfer_no,
            'company_id'=>$request->company_id,
            'to_company_id'=>$request->to_company_id,
            'transfer_date'=>$request->transfer_date,
            'remarks'=>$request->remarks,
        ]);


        if($invyarntransout){
            return response()->json(array('success' =>true ,'id'=>$invyarntransout->id, 'transfer_no'=>$transfer_no,'message'=>'Saved Successfully'),200);
        }*/

        $max=$this->invisu
        ->where([['company_id','=',$request->company_id]])
        ->whereIn('menu_id',[101,104,107,111])
        ->max('issue_no');
        $issue_no=$max+1;

        $invisu=$this->invisu->create([
            'menu_id'=>107,
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
    public function update(InvYarnTransOutRequest $request, $id) {
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
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);
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


        



        

        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }

       

      



      
      

        $invyarntransout = $this->invisu
        ->join('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
        })
        
        
        ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
        })
        ->join('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
        })
       ->where([['inv_isus.id','=',$id]])
       ->orderBy('inv_yarn_isu_items.id','desc')
       ->get([
        'inv_yarn_isu_items.*',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_name',
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',
        'suppliers.name as supplier_name',
       ])
       ->map(function($invyarntransout) use($yarnDropdown) {
            $invyarntransout->yarn_count=$invyarntransout->count."/".$invyarntransout->symbol;
            $invyarntransout->composition=isset($yarnDropdown[$invyarntransout->item_account_id])?$yarnDropdown[$invyarntransout->item_account_id]:'';
            return $invyarntransout;
        });
      
      
      $data['master']    =$rows;
      $data['details']   =$invyarntransout;

      
     

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
        $pdf->SetY(3);
        $pdf->SetX(190);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Write(0, 'Yarn Transfer Challan/Gate Pass ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Yarn Transfer Challan/Gate Pass');
      $view= \View::make('Defult.Inventory.Yarn.YarnTransOutPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/YarnTransOutPdf.pdf';
      $pdf->output($filename);
    }
}