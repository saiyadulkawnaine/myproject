<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnTransInRequest;

class InvYarnTransInController extends Controller {

    private $invyarntransin;
    private $company;
    private $store;
    private $itemaccount;
    private $invrcv;
    private $invyarnrcv;
    private $supplier;

    public function __construct(
        InvYarnTransInRepository $invyarntransin,
        CompanyRepository $company, 
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        InvRcvRepository $invrcv,
        InvYarnRcvRepository $invyarnrcv,
        SupplierRepository $supplier
    ) {
        $this->invyarntransin = $invyarntransin;
        $this->company = $company;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->invrcv = $invrcv;
        $this->invyarnrcv = $invyarnrcv;
        $this->supplier = $supplier;
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
        /*$rows = $this->invyarntransin
        ->join('companies',function($join){
        $join->on('companies.id','=','inv_yarn_trans_ins.company_id');
        })
        ->join('companies as fromcompanies',function($join){
        $join->on('fromcompanies.id','=','inv_yarn_trans_ins.from_company_id');
        })
        
        ->orderBy('inv_yarn_trans_ins.id','desc')
        ->get([
          'inv_yarn_trans_ins.*',
          'companies.name as company_name',
          'fromcompanies.name as from_company_name'
        ])
        ->map(function($rows){
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
        return $rows;
        });
        echo json_encode($rows);*/

        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $rows = $this->invrcv
       ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
        })

        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('companies as fromcompanies',function($join){
        $join->on('fromcompanies.id','=','inv_rcvs.from_company_id');
        })
       ->where([['inv_rcvs.menu_id','=',108]])
       ->orderBy('inv_rcvs.id','desc')
       ->get([
        'inv_rcvs.*',
        'inv_yarn_rcvs.id as inv_yarn_rcv_id',
        'companies.name as company_name',
        'suppliers.name as supplier_name',
        'fromcompanies.name as from_company_name',
        ])
       ->map(function($rows){
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
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
      $supplier=array_prepend(array_pluck($this->supplier->yarnSupplier(),'name','id'),'-Select-','');

      return Template::loadView('Inventory.Yarn.InvYarnTransIn',['company'=>$company,'store'=>$store,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvYarnTransInRequest $request) {
        /*$max=$this->invyarntransin
        ->where([['company_id','=',$request->company_id]])
        ->max('receive_no');
        $receive_no=$max+1;

        $invyarntransin=$this->invyarntransin->create([
            'receive_no'=>$receive_no,
            'company_id'=>$request->company_id,
            'from_company_id'=>$request->from_company_id,
            'receive_date'=>$request->receive_date,
            'remarks'=>$request->remarks,
        ]);


        if($invyarntransin){
            return response()->json(array('success' =>true ,'id'=>$invyarntransin->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
        }*/

        $max=$this->invrcv
        ->where([['company_id','=',$request->company_id]])
        //->where([['menu_id','=',100]])
        ->whereIn('menu_id',[100,105,106,108])
        ->max('receive_no');
        $receive_no=$max+1;

        $invrcv=$this->invrcv->create([
        'menu_id'=>108,
        'receive_no'=>$receive_no,
        'company_id'=>$request->company_id,
        'receive_basis_id'=>9,
        'receive_against_id'=>0,
        'supplier_id'=>$request->supplier_id,
        'from_company_id'=>$request->from_company_id,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'remarks'=>$request->remarks,
      ]);

      $invyarnrcv=$this->invyarnrcv->create([
        'menu_id'=>108,
        'inv_rcv_id'=>$invrcv->id,
      ]);

        


        if($invyarnrcv){
            return response()->json(array('success' =>true ,'id'=>$invyarnrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invyarnrcv = $this->invrcv
        ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_yarn_rcvs.id  as inv_yarn_rcv_id'
        ])
        ->first();
        $row ['fromData'] = $invyarnrcv;
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
    public function update(InvYarnTransInRequest $request, $id) {
        $invyarnrcv=$this->invrcv->update($id,$request->except(['id','inv_yarn_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id','from_company_id']));
        if($invyarnrcv){
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
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getPdf()
    {
      $id=request('id',0);
      $invissuebasis=array_prepend(config('bprs.invissuebasis'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');  

       $rows = $this->invrcv
       ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
        })

        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('companies as fromcompanies',function($join){
        $join->on('fromcompanies.id','=','inv_rcvs.from_company_id');
        })
       ->where([['inv_rcvs.id','=',$id]])
       ->orderBy('inv_rcvs.id','desc')
       ->get([
        'inv_rcvs.*',
        'inv_yarn_rcvs.id as inv_yarn_rcv_id',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'suppliers.name as supplier_name',
        'fromcompanies.name as from_company_name',
        ])
       ->first();
      $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));



        



        

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

       

      



      
      

      $invyarnrcvitem=$this->invrcv
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
        ->whereNull('inv_yarn_rcv_items.deleted_at');
        })

        ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
        ->whereNull('inv_yarn_rcv_items.deleted_at');
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
        
        
        ->where([['inv_rcvs.id','=',$id]])
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get([
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',

        'inv_yarn_rcv_items.id',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_name',
        'inv_yarn_rcv_items.qty',
        'inv_yarn_rcv_items.rate',
        'inv_yarn_rcv_items.amount',
        'inv_yarn_rcv_items.store_qty',
        'uoms.code as uom',
        'inv_yarn_rcv_items.store_rate',
        'inv_yarn_rcv_items.store_amount',
        ])
        ->map(function($invyarnrcvitem) use($yarnDropdown) {
        $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
        $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
        $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
        return $invyarnrcvitem;
        });
      
      
      $data['master']    =$rows;
      $data['details']   =$invyarnrcvitem;

      
     

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
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Write(0, 'Yarn Transfer In Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Yarn Transfer In Report');
      $view= \View::make('Defult.Inventory.Yarn.YarnTransInPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/YarnTransInPdf.pdf';
      $pdf->output($filename);
    }
}