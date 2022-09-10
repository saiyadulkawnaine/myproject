<?php

namespace App\Http\Controllers\GateEntry;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\GateEntry\GateOutRepository;
use App\Repositories\Contracts\GateEntry\GateOutItemRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;


use App\Library\Sms;
use App\Library\Template;
use App\Http\Requests\GateEntry\GateOutRequest;

class GateOutController extends Controller {
    private $gateout;
    private $user;
    private $invisu;
    private $itemaccount;
    private $autoyarn;
    private $sodyeingdlv;
    private $soaopdlv;
    private $soknitdlv;
    private $gmtspart;
    private $jhutesaledlv;
    private $assetservice;
    private $assetservicerepair;


    public function __construct(
        GateOutRepository $gateout,
        GateOutItemRepository $gateoutitem,
        InvIsuRepository $invisu,
        SoDyeingDlvRepository $sodyeingdlv,
        SoAopDlvRepository $soaopdlv,
        SoKnitDlvRepository $soknitdlv,
        UserRepository $user,
        ItemAccountRepository $itemaccount,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        JhuteSaleDlvRepository $jhutesaledlv,
        AssetServiceRepository $assetservice,
        AssetServiceRepairRepository $assetservicerepair

    ) {
        $this->gateout  = $gateout;
        $this->gateoutitem  = $gateoutitem;
        $this->invisu = $invisu;
        $this->sodyeingdlv = $sodyeingdlv;
        $this->soaopdlv = $soaopdlv;
        $this->soknitdlv = $soknitdlv;
        $this->user = $user;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->jhutesaledlv=$jhutesaledlv;
        $this->assetservice = $assetservice;
        $this->assetservicerepair = $assetservicerepair;

      $this->middleware('auth');
      //$this->middleware('permission:view.gateouts',   ['only' => ['create', 'index','show']]);
     // $this->middleware('permission:create.gateouts', ['only' => ['store']]);
      //$this->middleware('permission:edit.gateouts',   ['only' => ['update']]);
      //$this->middleware('permission:delete.gateouts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {  
     //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $menu=array_prepend(array_only(config('bprs.menu'),[101,111,107,280,281,282,350,380,381]),'-Select-','');
      return Template::loadView('GateEntry.GateOut', ['menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GateOutRequest $request) {
      $out_date=date('Y-m-d');
      $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
      
        $gateout=$this->gateout->create([
          'menu_id'=>$request->menu_id,
          'barcode_no_id'=>$request->barcode_no_id,
          'out_date'=>$out_date,
        ]);
        $qty=0;
        $returnable_qty=0;
        $itemDescArr=[];
        foreach($request->item_id as $index=>$item_id){
          if($item_id && $request->qty[$index])
          {
            $itemArr[]=$item_id;
            $itemDescArr[]=$request->item_description[$index].' '.$request->qty[$index].' '.$request->uom_code[$index];
            
              $gateoutitem = $this->gateoutitem->create([
                'gate_out_id'=>$gateout->id,
                'item_id'=>$item_id,
                'qty'=>$request->qty[$index],
              ]);
              $qty += $request->qty[$index];
              $returnable_qty += $request->returned_qty[$index];
          }
        }
        if($gateout){
          //dd($itemDescArr);die;
          //Yarn Issue
          if ($request->menu_id==101) {
            $gatemsg=$this->invisu
            ->selectRaw('
              inv_isus.id as inv_isu_id,
              inv_isus.issue_no,
              companies.name as company_name,
              suppliers.name as supplier_name
            ')
            ->join('companies',function($join){
              $join->on('companies.id','=','inv_isus.company_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','inv_isus.supplier_id');
            })
            ->where([['inv_isus.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            //$constructionArr[$request->item_description]
            $title ='Yarn Issue Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode(' ;',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'Returnable Qty:'.$returnable_qty."\n".
            'GIN No:'.$gatemsg->issue_no."\n".
            'Delivery To:'.$gatemsg->supplier_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801714174033,8801714173598,8801916188046,8801772217255,8801712675485');
          }
          //Yarn Transfer Out
          if ($request->menu_id==107) {
            $gatemsg=$this->invisu
            ->selectRaw('
              inv_isus.id as inv_isu_id,
              inv_isus.issue_no,
              tocompanies.name as to_company_name
            ')
            ->join('companies as tocompanies',function($join){
              $join->on('tocompanies.id','=','inv_isus.to_company_id');
            })
            ->where([['inv_isus.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Yarn TranferOut Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode(' ;',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'Returnable Qty:'.$returnable_qty."\n".
            'GIN No:'.$gatemsg->issue_no."\n".
            'Delivery To:'.$gatemsg->to_company_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801714174033,8801714173598,8801916188046,8801772217255,8801712675485');
          }
          //Yarn Purchase Return
          if ($request->menu_id==111) {
            $gatemsg=$this->invisu
            ->selectRaw('
              inv_isus.id as inv_isu_id,
              inv_isus.issue_no,
              suppliers.name as supplier_name
            ')
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','inv_isus.supplier_id');
            })
            ->where([['inv_isus.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Yarn Purchase Return Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode(' ;',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'Returnable Qty:'.$returnable_qty."\n".
            'GIN No:'.$gatemsg->issue_no."\n".
            'Delivery To:'.$gatemsg->supplier_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801714174033,8801714173598,8801916188046,8801772217255,8801712675485');
          }
          //Subcontract Dyeing Delivery
          if ($request->menu_id==280) {

            $gatemsg= $this->sodyeingdlv
            ->selectRaw('
              so_dyeing_dlvs.id as so_dyeing_dlv_id,
              so_dyeing_dlvs.issue_no,
              buyers.name as buyer_name
            ')
            ->leftJoin('buyers', function($join)  {
              $join->on('so_dyeing_dlvs.buyer_id', '=', 'buyers.id');
            })
            ->where([['so_dyeing_dlvs.id','=',$request->barcode_no_id]])
            ->get()
            ->first();
            
            $title ='Subcontract Dyeing Delivery Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode('; ',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'GIN No:'.$gatemsg->issue_no."\n".
            'Delivery To:'.$gatemsg->buyer_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801715813154');
          }
          //Subcontract AOP Delivery
          if ($request->menu_id==281) {
            $gatemsg= $this->soaopdlv
            ->selectRaw('
              so_aop_dlvs.id as so_aop_dlv_id,
              so_aop_dlvs.issue_no,
              buyers.name as buyer_name
            ')
            ->leftJoin('buyers', function($join)  {
              $join->on('so_aop_dlvs.buyer_id', '=', 'buyers.id');
            })
            ->where([['so_aop_dlvs.id','=',$request->barcode_no_id]])
            ->get()
            ->first();
            
            $title ='Subcontract AOP Delivery Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode('; ',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'GIN No:'.$gatemsg->issue_no."\n".
            'Delivery To:'.$gatemsg->buyer_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801714173150,8801712213447');
          }
          //Subcontract Knitting Delivery
          if ($request->menu_id==282) {
            $gatemsg= $this->soknitdlv
            ->selectRaw('
              so_knit_dlvs.id as so_knit_dlv_id,
              so_knit_dlvs.issue_no,
              buyers.name as buyer_name
            ')
            ->leftJoin('buyers', function($join)  {
              $join->on('so_knit_dlvs.buyer_id', '=', 'buyers.id');
            })
            ->where([['so_knit_dlvs.id','=',$request->barcode_no_id]])
            ->get()
            ->first();
            
            $title ='Subcontract Knitting Delivery Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode('; ',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'GIN No:'.$gatemsg->issue_no."\n".
            'Delivery To:'.$gatemsg->buyer_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801714173167');
          }
          //Jhute Sale Delivery
          if ($request->menu_id==350) {
            $gatemsg=$this->jhutesaledlv
            ->selectRaw('
              jhute_sale_dlvs.id as jhute_sale_dlv_id,
              jhute_sale_dlvs.dlv_no,
              companies.name as company_name,
              buyers.name as buyer_name
            ')
            ->join('jhute_sale_dlv_orders',function($join){
              $join->on('jhute_sale_dlv_orders.id','=','jhute_sale_dlvs.jhute_sale_dlv_order_id');
            })
            ->join('companies',function($join){
              $join->on('companies.id','=','jhute_sale_dlv_orders.company_id');
            })
            ->join('buyers',function($join){
              $join->on('buyers.id','=','jhute_sale_dlv_orders.buyer_id');
            })
            ->where([['jhute_sale_dlvs.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Jhute Sale Delivery Gate Out';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Item Description:'.implode(' ;',$itemDescArr)."\n".
            'Total Qty:'.$qty."\n".
            'DLV No:'.$gatemsg->dlv_no."\n".
            'Delivery To:'.$gatemsg->buyer_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

           $sms=Sms::send_sms($text, '8801711563231');
          }
          //Asset Repair Outside
          if ($request->menu_id==380) {
            $gatemsg=$this->assetservicerepair
            ->selectRaw('
              asset_service_repairs.id as asset_service_repair_id,
              suppliers.name as supplier_name,
              asset_quantity_costs.custom_no
            ')
            ->join("asset_breakdowns",function($join){
              $join->on("asset_breakdowns.id","=","asset_service_repairs.asset_breakdown_id");
            })
            ->join('asset_quantity_costs',function($join){
              $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
            })
            ->join("suppliers",function($join){
              $join->on("suppliers.id","=","asset_service_repairs.supplier_id");
            })
            ->where([['asset_service_repairs.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Asset Repair Outside';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Custom No:'.$gatemsg->custom_no."\n".
            'Total Qty:'.$qty."\n".
            'Delivery To:'.$gatemsg->supplier_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

            $sms=Sms::send_sms($text, '8801711563231');
          }
          //Asset Servicing Outside
          if ($request->menu_id==381) {
            $gatemsg=$this->assetservice
            ->selectRaw('
              asset_services.id as asset_service_repair_id,
              suppliers.name as supplier_name,
              asset_services.remarks
            ')
            ->join("suppliers",function($join){
              $join->on("suppliers.id","=","asset_services.supplier_id");
            })
            ->where([['asset_services.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Asset Repair Outside';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($out_date))."\n".
            'Comments:'.$gatemsg->remarks."\n".
            'Total Qty:'.$qty."\n".
            'Delivery To:'.$gatemsg->supplier_name."\n".
            'Gate Out Entered By:'.$user[$gateout->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateout->created_at));

            $sms=Sms::send_sms($text, '8801711563231');
          }
          
          
        return response()->json(array('success' => true,'id' =>  $gateout->id,'sms' => $sms,'message' => 'Save Successfully'),200);
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
      $gate=$this->gateout->find($id);
      $row ['fromData'] = $gateout;
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
    public function update(GateOutRequest $request, $id) {
      $gateout=$this->gateout->update($id,[
        'menu_id'=>$request->menu_id,
        'barcode_no_id'=>$request->barcode_no_id,
        'out_date'=>$request->out_date,
        'remarks'=>$request->remarks
      ]);

      // $this->gateoutitem->where([['gate_entry_id','=',$id]])->forceDelete();

      // foreach($request->item_id as $index=>$item_id){
      //   if($item_id && $request->qty[$index])
      //   {
      //       $gateoutitem = $this->gateoutitem->create([
      //         'gate_entry_id'=>$id,
      //         'item_id'=>$item_id,
      //         'qty'=>$request->qty[$index],
      //         'remarks'=>$request->remarks[$index],
      //       ]);
      //   }
      // }
      if($gateout){
          return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
      }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

    public function getMenuItem()
    {
        $menu_id=request('menu_id', 0);
        $bar_code_no=request('barcode_no_id',0);
        $bar_code_no= (int) $bar_code_no;

        //Yarn Issue
        if($menu_id==101){
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

            $rows =$this->invisu
            ->join('inv_yarn_isu_items',function($join){
              $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
            })
            ->join('inv_yarn_items',function($join){
              $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
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
            ->join('uoms',function($join){
              $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('colors',function($join){
              $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            ->where([['inv_isus.id','=',$bar_code_no]])
            ->where([['inv_isus.menu_id','=',101]])
            ->orderBy('inv_yarn_isu_items.id','desc')
            ->get([
              'inv_isus.id as inv_isu_id',
              'inv_isus.issue_no as gin_no',
              'inv_yarn_isu_items.id as item_id',
              'inv_yarn_items.lot',
              'inv_yarn_items.brand',
              'inv_yarn_isu_items.qty',
              'inv_yarn_isu_items.returned_qty',
              'colors.name as color_name',
              'uoms.code as uom_code',
              'item_accounts.id as item_account_id',
              'yarncounts.count',
              'yarncounts.symbol',
              'yarntypes.name as yarn_type',
            ])
            ->map(function($rows) use($yarnDropdown){
              $rows->barcode_no_id=$rows->inv_isu_id;
              $rows->company_name=$rows->issue_company;
              $rows->yarn_count=$rows->count."/".$rows->symbol;
              $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
              $rows->item_description=$rows->yarn_count.','.$rows->composition;
              $rows->menu_name="Inventory Yarn Issue";
              $rows->qty=$rows->qty-$rows->returned_qty;
              return $rows;
            });
           // dd($rows);die;
            echo json_encode($rows);

        }
        //Yarn Transfer Out
        if($menu_id==107){
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

            $rows =$this->invisu
            ->join('inv_yarn_isu_items',function($join){
              $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
            })
            ->join('inv_yarn_items',function($join){
              $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
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
            ->join('uoms',function($join){
              $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('colors',function($join){
              $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            ->where([['inv_isus.id','=',$bar_code_no]])
            ->where([['inv_isus.menu_id','=',107]])
            ->orderBy('inv_yarn_isu_items.id','desc')
            ->get([
              'inv_isus.id as barcode_no_id',
              'inv_isus.issue_no as gin_no',
              'inv_yarn_isu_items.id as item_id',
              'inv_yarn_items.lot',
              'inv_yarn_items.brand',
              'inv_yarn_isu_items.qty',
              'inv_yarn_isu_items.returned_qty',
              'colors.name as color_name',
              'uoms.code as uom_code',
              'item_accounts.id as item_account_id',
              'yarncounts.count',
              'yarncounts.symbol',
              'yarntypes.name as yarn_type',
            ])
            ->map(function($rows) use($yarnDropdown){
              $rows->yarn_count=$rows->count."/".$rows->symbol;
              $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
              $rows->item_description=$rows->yarn_count.','.$rows->composition;
              return $rows;
            }); 
            echo json_encode($rows);
        }
        //Yarn Purchase Return
        if($menu_id==111){
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
  

            $rows =$this->invisu
              ->join('inv_yarn_isu_items',function($join){
                $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
              })
              ->join('inv_yarn_rcv_items',function($join){
                $join->on('inv_yarn_rcv_items.id','=','inv_yarn_isu_items.inv_yarn_rcv_item_id');
              })
              ->join('inv_yarn_rcvs',function($join){
                $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
              })
              ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
              })
              ->join('inv_yarn_items',function($join){
                $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
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
              ->join('uoms',function($join){
                $join->on('uoms.id','=','item_accounts.uom_id');
              })
              ->join('colors',function($join){
                $join->on('colors.id','=','inv_yarn_items.color_id');
              })
              ->where([['inv_isus.id','=',$bar_code_no]])
              ->where([['inv_isus.menu_id','=',111]])
              ->orderBy('inv_yarn_isu_items.id','desc')
              ->get([
                'inv_isus.id as inv_isu_id',
                'inv_isus.issue_no as gin_no',
                'inv_yarn_isu_items.id as item_id',
                'inv_yarn_items.lot',
                'inv_yarn_items.brand',
                'inv_yarn_isu_items.qty',
                'inv_yarn_isu_items.returned_qty',
                'colors.name as color_name',
                'uoms.code as uom_code',
                'item_accounts.id as item_account_id',
                'yarncounts.count',
                'yarncounts.symbol',
                'yarntypes.name as yarn_type',
              ])
              ->map(function($rows) use($yarnDropdown){
                $rows->barcode_no_id=$rows->inv_isu_id;
                $rows->company_name=$rows->issue_company;
                $rows->yarn_count=$rows->count."/".$rows->symbol;
                $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
                $rows->item_description=$rows->yarn_count.','.$rows->composition;
                $rows->qty=$rows->qty-$rows->returned_qty;
                return $rows;
              });
          // dd($rows);die;
            echo json_encode($rows);

        }
        //Subcontract Dyeing Delivery
        if ($menu_id==280) {

          $autoyarn=$this->autoyarn
          ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
          })
          ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
          })
          ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
          })
          ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
          })
          ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
          })
          ->orderBy('autoyarns.id','desc')
          ->get([
          'autoyarns.*',
          'constructions.name',
          'compositions.name as composition_name',
          'autoyarnratios.ratio'
          ]);

          $fabricDescriptionArr=array();
          $fabricCompositionArr=array();
          foreach($autoyarn as $row){
          $fabricDescriptionArr[$row->id]=$row->name;
          $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
          }
          $desDropdown=array();
          foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val/* .",".implode(",",$fabricCompositionArr[$key]) */;
          }

          $rows=$this->sodyeingdlv
          ->join('so_dyeing_dlv_items',function($join){
            $join->on('so_dyeing_dlv_items.so_dyeing_dlv_id','=','so_dyeing_dlvs.id');
          })
          ->join('so_dyeing_refs',function($join){
            $join->on('so_dyeing_refs.id','=','so_dyeing_dlv_items.so_dyeing_ref_id');
          })
          ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
          })
          ->leftJoin('so_dyeing_items',function($join){
            $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
          })
          ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','so_dyeing_items.uom_id');
          })
          ->leftJoin('colors',function($join){
            $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
          })
          ->where([['so_dyeing_dlvs.id','=',$bar_code_no]])
          ->orderBy('so_dyeing_dlvs.id','desc')
          ->selectRaw('
            so_dyeing_dlvs.id as so_dyeing_dlv_id,
            so_dyeing_items.autoyarn_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_dlv_items.id as item_id,
            so_dyeing_dlv_items.qty,
            so_dyeing_dlv_items.rate,
            so_dyeing_dlv_items.amount,
            uoms.code as uom_code,
            colors.name as fabric_color
          '
          )
          ->get()
          ->map(function($rows) use($desDropdown,$fabricDescriptionArr){
            $rows->barcode_no_id=$rows->so_dyeing_dlv_id;
            $rows->fabrication=$desDropdown[$rows->autoyarn_id];
            $rows->item_description=$rows->fabrication;
            return $rows;
          });

          echo json_encode($rows);
        }
        //Subcontract AOP Delivery
        if ($menu_id==281) {
          $autoyarn=$this->autoyarn
          ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
          })
          ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
          })
          ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
          })
          ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
          })
          ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
          })
          ->orderBy('autoyarns.id','desc')
          ->get([
            'autoyarns.*',
            'constructions.name',
            'compositions.name as composition_name',
            'autoyarnratios.ratio'
          ]);

          $fabricDescriptionArr=array();
          $fabricCompositionArr=array();
          foreach($autoyarn as $row){
            $fabricDescriptionArr[$row->id]=$row->name;
            $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
          }
          $desDropdown=array();
          foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val/* .",".implode(",",$fabricCompositionArr[$key]) */;
          }

          $rows=$this->soaopdlv
          ->selectRaw('
            so_aop_dlvs.id as so_aop_dlv_id,
            so_aop_items.autoyarn_id,
            so_aop_dlv_items.id as item_id,
            so_aop_dlv_items.qty,
            so_aop_dlv_items.rate,
            so_aop_dlv_items.amount,
            uoms.code as uom_code,
            colors.name as fabric_color'
          )
          ->join('so_aop_dlv_items',function($join){
            $join->on('so_aop_dlv_items.so_aop_dlv_id','=','so_aop_dlvs.id');
          })
          ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.id','=','so_aop_dlv_items.so_aop_ref_id');
          })
          ->join('so_aops',function($join){
          $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
          })
          ->leftJoin('so_aop_items',function($join){
          $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
          })
          ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','so_aop_items.uom_id');
          })
          ->leftJoin('colors',function($join){
          $join->on('colors.id','=','so_aop_items.fabric_color_id');
          })
          ->where([['so_aop_dlvs.id','=',$bar_code_no]])
          ->orderBy('so_aop_dlvs.id','desc')
          ->get()
          ->map(function($rows) use($desDropdown){
            $rows->barcode_no_id=$rows->so_aop_dlv_id;
            $rows->fabrication=$desDropdown[$rows->autoyarn_id];
            $rows->item_description=$rows->fabrication;
            return $rows;
          });

          echo json_encode($rows);
        }
        //Subcontract Knitting Delivery
        if ($menu_id==282) {
          $autoyarn=$this->autoyarn
          ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
          })
          ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
          })
          ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
          })
          ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
          })
          ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
          })
          ->orderBy('autoyarns.id','desc')
          ->get([
            'autoyarns.*',
            'constructions.name',
            'compositions.name as composition_name',
            'autoyarnratios.ratio'
          ]);

          $fabricDescriptionArr=array();
          $fabricCompositionArr=array();
          foreach($autoyarn as $row){
            $fabricDescriptionArr[$row->id]=$row->name;
            $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
          }
          $desDropdown=array();
          foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val/* .",".implode(",",$fabricCompositionArr[$key]) */;
          }

          $rows=$this->soknitdlv
          ->selectRaw('
            so_knit_dlvs.id as so_knit_dlv_id,
            so_knit_items.autoyarn_id,
            so_knit_dlv_items.id as item_id,
            so_knit_dlv_items.qty,
            so_knit_dlv_items.rate,
            so_knit_dlv_items.amount,
            uoms.code as uom_code,
            colors.name as fabric_color
          ')
          ->join('so_knit_dlv_items',function($join){
            $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
          })
          ->join('so_knit_refs',function($join){
            $join->on('so_knit_refs.id','=','so_knit_dlv_items.so_knit_ref_id');
          })
          ->join('so_knits',function($join){
            $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
          })
          ->leftJoin('so_knit_items',function($join){
            $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
          })
          ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','so_knit_items.uom_id');
          })
          ->leftJoin('colors',function($join){
            $join->on('colors.id','=','so_knit_items.fabric_color_id');
          })
          ->where([['so_knit_dlvs.id','=',$bar_code_no]])
          ->orderBy('so_knit_dlvs.id','desc')
          ->get()
          ->map(function($rows) use($desDropdown){
            $rows->barcode_no_id=$rows->so_knit_dlv_id;
            $rows->fabrication=$desDropdown[$rows->autoyarn_id];
            $rows->item_description=$rows->fabrication;
            return $rows;
          });

          echo json_encode($rows);
        }
        //Jhute Sale Delivery
        if ($menu_id==350) {
          $rows = $this->jhutesaledlv
            ->join('jhute_sale_dlv_items',function($join){
              $join->on('jhute_sale_dlvs.id','=','jhute_sale_dlv_items.jhute_sale_dlv_id');
            })
            ->join('jhute_sale_dlv_order_items',function($join){
              $join->on('jhute_sale_dlv_order_items.id','=','jhute_sale_dlv_items.jhute_sale_dlv_order_item_id');
            })
            ->leftJoin('uoms',function($join){
              $join->on('uoms.id','=','jhute_sale_dlv_order_items.uom_id');
            })
            ->join('acc_chart_ctrl_heads',function($join){
              $join->on('acc_chart_ctrl_heads.id','=','jhute_sale_dlv_order_items.acc_chart_ctrl_head_id');
            })
            ->where([['jhute_sale_dlvs.id','=',$bar_code_no]])
            ->orderBy('jhute_sale_dlv_items.id','desc')
            ->get([
              'jhute_sale_dlvs.id as jhute_sale_dlv_id',
              'jhute_sale_dlvs.dlv_no as gin_no',
              'jhute_sale_dlv_items.id as item_id',
              'jhute_sale_dlv_items.qty',
              'uoms.code as uom_code',
              'acc_chart_ctrl_heads.id as item_account_id',
              'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
            ])
            ->map(function($rows){
              $rows->barcode_no_id=$rows->jhute_sale_dlv_id;
              $rows->item_description=$rows->acc_chart_ctrl_head_name;
              $rows->menu_name="Jhute Sale Delivery";
              $rows->qty=$rows->qty;
              return $rows;
            });
           // dd($rows);die;
            echo json_encode($rows);
        }
        //Asset Repair Outside
        if ($menu_id==380) {
            $rows = $this->assetservicerepair
            ->join('asset_breakdowns',function($join){
              $join->on('asset_breakdowns.id','=','asset_service_repairs.asset_breakdown_id'); 
            })
            ->join('asset_quantity_costs',function($join){
              $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','asset_service_repairs.supplier_id');
            })
            ->join('asset_acquisitions',function($join){
              $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','asset_acquisitions.company_id');
            })
            ->join('asset_service_repair_parts',function($join){
              $join->on('asset_service_repairs.id','asset_service_repair_parts.asset_service_repair_id');
            })
            ->leftJoin('item_accounts',function($join){
                $join->on('item_accounts.id','=','asset_service_repair_parts.item_account_id');
            })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
              $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->where([['asset_service_repairs.id','=',$bar_code_no]])
            ->orderBy('asset_service_repair_parts.id','desc')
            ->get([
              'asset_service_repairs.id as asset_service_repair_id',
              'asset_quantity_costs.custom_no as gin_no',
              'asset_service_repair_parts.id as item_id',
              'asset_service_repair_parts.qty',
              'uoms.code as uom_code',
              'asset_service_repair_parts.item_account_id',
              'item_accounts.item_description',
            ])
            ->map(function($rows){
              $rows->barcode_no_id=$rows->asset_service_repair_id;
              $rows->menu_name="Asset Repair Outside";
              return $rows;
            });
           // echo json_encode($rows);
            dd($rows);die;
        }
        //Asset Servicing Outside
        if ($menu_id==381) {
          $rows = $this->assetservice
          ->join('asset_service_details', function ($join) {
            $join->on('asset_service_details.asset_service_id', '=', 'asset_services.id');
          })
          ->join('asset_quantity_costs', function ($join) {
            $join->on('asset_quantity_costs.id', '=', 'asset_service_details.asset_quantity_cost_id');
          })
          ->join('asset_acquisitions', function ($join) {
            $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
          })
          ->leftJoin('uoms', function ($join) {
            $join->on('uoms.id', '=', 'asset_acquisitions.uom_id');
          })
          ->where([['asset_services.id','=',$bar_code_no]])
          ->orderBy('asset_service_details.id','desc')
          ->get([
            'asset_services.id as asset_service_id',
            'asset_quantity_costs.custom_no as gin_no',
            'asset_service_details.id as item_id',
            'uoms.code as uom_code',
            'asset_quantity_costs.id as item_account_id',
            'asset_acquisitions.name as item_description',
          ])
          ->map(function($rows){
            $rows->barcode_no_id=$rows->asset_service_id;
            $rows->qty=1;
            $rows->menu_name="Asset Repair Outside";
            return $rows;
          });
          echo json_encode($rows);
        }
    }

    public function getOutEntry(){
      $menu_id=request('menu_id', 0);
      $from_date=request('from_date', 0);
      $to_date=request('to_date', 0);
      //Yarn Issue
      if($menu_id==101){
        $rows = $this->gateout
            ->selectRaw('
              gate_outs.id,
              gate_outs.barcode_no_id,
              gate_outs.out_date,
              gate_outs.created_by,
              gate_outs.created_at,
              inv_isus.issue_no,
              suppliers.name as to_company_name,
              users.name as created_by_name 
            ')
            ->join('inv_isus',function($join){
              $join->on('inv_isus.id','=','gate_outs.barcode_no_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','inv_isus.supplier_id');
            })
            ->join('users',function($join){
              $join->on('users.id','=','gate_outs.created_by');
            })
            ->where([['gate_outs.menu_id','=',$menu_id]])
            ->when(request('from_date'), function ($q) {
              return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
            })
            ->when(request('to_date'), function ($q) {
              return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
            })
            ->orderBy('gate_outs.id','desc')
            //->limit(10)
            ->get()
            ->map(function($rows){
              $rows->entry_time=date('h:i A',strtotime($rows->created_at));
              $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
              $rows->gin_no=$rows->issue_no;
              $rows->menu_name="Inventory Yarn Issue";
              return $rows;
          });

          echo json_encode($rows);
      }
      //Yarn Transfer Out
      if($menu_id==107){
        $rows = $this->gateout
          ->selectRaw('
            gate_outs.id,
            gate_outs.barcode_no_id,
            gate_outs.out_date,
            gate_outs.created_by,
            gate_outs.created_at,
            inv_isus.issue_no,
            users.name as created_by_name,
            tocompanies.name as to_company_name
          ')
          ->join('inv_isus',function($join){
            $join->on('inv_isus.id','=','gate_outs.barcode_no_id');
          })
          ->join('companies as tocompanies',function($join){
            $join->on('tocompanies.id','=','inv_isus.to_company_id');
          })
          ->join('users',function($join){
            $join->on('users.id','=','gate_outs.created_by');
          })
          ->where([['gate_outs.menu_id','=',$menu_id]])
          ->when(request('from_date'), function ($q) {
            return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
          })
          ->when(request('to_date'), function ($q) {
            return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
          })
          ->orderBy('gate_outs.id','desc')
          ->get()
          ->map(function($rows){
            $rows->entry_time=date('h:i A',strtotime($rows->created_at));
            $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
            $rows->gin_no=$rows->issue_no;
            $rows->menu_name="Inventory Yarn Transfer Out";
            return $rows;
        });

        echo json_encode($rows);
      }
      //Yarn Purchase Return
      if($menu_id==111){
        $rows = $this->gateout
          ->selectRaw('
            gate_outs.id,
            gate_outs.barcode_no_id,
            gate_outs.out_date,
            gate_outs.created_by,
            gate_outs.created_at,
            inv_isus.issue_no,
            users.name as created_by_name,
            suppliers.name as to_company_name
          ')
          ->join('inv_isus',function($join){
            $join->on('inv_isus.id','=','gate_outs.barcode_no_id');
          })
          ->join('suppliers',function($join){
            $join->on('suppliers.id','=','inv_isus.supplier_id');
          })
          ->join('users',function($join){
            $join->on('users.id','=','gate_outs.created_by');
          })
          ->where([['gate_outs.menu_id','=',$menu_id]])
          ->when(request('from_date'), function ($q) {
            return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
          })
          ->when(request('to_date'), function ($q) {
            return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
          })
          ->orderBy('gate_outs.id','desc')
          ->get()
          ->map(function($rows){
            $rows->entry_time=date('h:i A',strtotime($rows->created_at));
            $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
            $rows->gin_no=$rows->issue_no;
            $rows->menu_name="Inventory Yarn Purchase Return";
            return $rows;
        });

        echo json_encode($rows);
      }
      //Subcontract Dyeing Delivery
      if($menu_id==280){
          $rows = $this->gateout
          ->selectRaw('
            gate_outs.id,
            gate_outs.barcode_no_id,
            gate_outs.out_date,
            gate_outs.created_by,
            gate_outs.created_at,
            so_dyeing_dlvs.issue_no,
            users.name as created_by_name,
            buyers.name as to_company_name
          ')
          ->join('so_dyeing_dlvs',function($join){
            $join->on('so_dyeing_dlvs.id','=','gate_outs.barcode_no_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_dyeing_dlvs.buyer_id', '=', 'buyers.id');
          })
          ->join('users',function($join){
            $join->on('users.id','=','gate_outs.created_by');
          })
          ->where([['gate_outs.menu_id','=',$menu_id]])
          ->when(request('from_date'), function ($q) {
            return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
          })
          ->when(request('to_date'), function ($q) {
            return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
          })
          ->orderBy('gate_outs.id','desc')
          ->get()
          ->map(function($rows){
            $rows->entry_time=date('h:i A',strtotime($rows->created_at));
            $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
            $rows->gin_no=$rows->issue_no;
            $rows->menu_name="Subcontract Dyeing Delivery";
            return $rows;
        });

          echo json_encode($rows);
      }
      //Subcontract AOP Delivery
      if($menu_id==281){
          $rows = $this->gateout
          ->selectRaw('
            gate_outs.id,
            gate_outs.barcode_no_id,
            gate_outs.out_date,
            gate_outs.created_by,
            gate_outs.created_at,
            so_aop_dlvs.issue_no,
            users.name as created_by_name,
            buyers.name as to_company_name
          ')
          ->join('so_aop_dlvs',function($join){
            $join->on('so_aop_dlvs.id','=','gate_outs.barcode_no_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_aop_dlvs.buyer_id', '=', 'buyers.id');
          })
          ->join('users',function($join){
            $join->on('users.id','=','gate_outs.created_by');
          })
          ->where([['gate_outs.menu_id','=',$menu_id]])
          ->when(request('from_date'), function ($q) {
            return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
          })
          ->when(request('to_date'), function ($q) {
            return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
          })
          ->orderBy('gate_outs.id','desc')
          ->get()
          ->map(function($rows){
            $rows->entry_time=date('h:i A',strtotime($rows->created_at));
            $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
            $rows->gin_no=$rows->issue_no;
            $rows->menu_name="Subcontract Aop Delivery";
            return $rows;
        });

          echo json_encode($rows);
      }
      //Subcontract Knitting Delivery
      if($menu_id==282){
          $rows = $this->gateout
          ->selectRaw('
            gate_outs.id,
            gate_outs.barcode_no_id,
            gate_outs.out_date,
            gate_outs.created_by,
            gate_outs.created_at,
            so_knit_dlvs.issue_no,
            users.name as created_by_name,
            buyers.name as to_company_name
          ')
          ->join('so_knit_dlvs',function($join){
            $join->on('so_knit_dlvs.id','=','gate_outs.barcode_no_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_knit_dlvs.buyer_id', '=', 'buyers.id');
          })
          ->join('users',function($join){
            $join->on('users.id','=','gate_outs.created_by');
          })
          ->where([['gate_outs.menu_id','=',$menu_id]])
          ->when(request('from_date'), function ($q) {
            return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
          })
          ->when(request('to_date'), function ($q) {
            return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
          })
          ->orderBy('gate_outs.id','desc')
          ->get()
          ->map(function($rows){
            $rows->entry_time=date('h:i A',strtotime($rows->created_at));
            $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
            $rows->gin_no=$rows->issue_no;
            $rows->menu_name="Subcontract Knitting Delivery";
            return $rows;
        });

          echo json_encode($rows);
      }
      //Jhute Sale Delivery
      if ($menu_id==350) {
          $rows = $this->gateout
          ->selectRaw('
            gate_outs.id,
            gate_outs.barcode_no_id,
            gate_outs.out_date,
            gate_outs.created_by,
            gate_outs.created_at,
            jhute_sale_dlvs.dlv_no,
            users.name as created_by_name,
            buyers.name as to_company_name
          ')
          ->join('jhute_sale_dlvs',function($join){
            $join->on('jhute_sale_dlvs.id','=','gate_outs.barcode_no_id');
          })
          ->join('jhute_sale_dlv_orders',function($join){
            $join->on('jhute_sale_dlv_orders.id','=','jhute_sale_dlvs.jhute_sale_dlv_order_id');
          })
          ->join('companies',function($join){
              $join->on('companies.id','=','jhute_sale_dlv_orders.company_id');
          })
          ->join('buyers',function($join){
              $join->on('buyers.id','=','jhute_sale_dlv_orders.buyer_id');
          })
          ->join('users',function($join){
            $join->on('users.id','=','gate_outs.created_by');
          })
          ->where([['gate_outs.menu_id','=',$menu_id]])
          ->when(request('from_date'), function ($q) {
            return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
          })
          ->when(request('to_date'), function ($q) {
            return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
          })
          ->orderBy('gate_outs.id','desc')
          ->get()
          ->map(function($rows){
            $rows->entry_time=date('h:i A',strtotime($rows->created_at));
            $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
            $rows->gin_no=$rows->dlv_no;
            $rows->menu_name="Jhute Sale Delivery";
            return $rows;
          });

        echo json_encode($rows);
      }
      //asset repair outside
      if ($menu_id==380) {
        $rows = $this->gateout
            ->selectRaw('
              gate_outs.id,
              gate_outs.barcode_no_id,
              gate_outs.out_date,
              gate_outs.created_by,
              gate_outs.created_at,
              asset_quantity_costs.custom_no,
              suppliers.name as to_company_name,
              users.name as created_by_name 
            ')
            ->join('asset_service_repairs',function($join){
              $join->on('asset_service_repairs.id','=','gate_outs.barcode_no_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','asset_service_repairs.supplier_id');
            })
            ->join('asset_quantity_costs',function($join){
              $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
            })
            ->join('users',function($join){
              $join->on('users.id','=','gate_outs.created_by');
            })
            ->where([['gate_outs.menu_id','=',$menu_id]])
            ->when(request('from_date'), function ($q) {
              return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
            })
            ->when(request('to_date'), function ($q) {
              return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
            })
            ->orderBy('gate_outs.id','desc')
            ->get()
            ->map(function($rows){
              $rows->entry_time=date('h:i A',strtotime($rows->created_at));
              $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
              $rows->gin_no=$rows->custom_no;
              $rows->menu_name="Asset Repair Outside";
              return $rows;
          });

          echo json_encode($rows);
      }
      //asset service outside
      if ($menu_id==381) {
        $rows = $this->gateout
            ->selectRaw('
              gate_outs.id,
              gate_outs.barcode_no_id,
              gate_outs.out_date,
              gate_outs.created_by,
              gate_outs.created_at,
              --inv_isus.issue_no,
              suppliers.name as to_company_name,
              users.name as created_by_name 
            ')
            ->join('asset_services',function($join){
              $join->on('asset_services.id','=','gate_outs.barcode_no_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','asset_services.supplier_id');
            })
            ->join('users',function($join){
              $join->on('users.id','=','gate_outs.created_by');
            })
            ->where([['gate_outs.menu_id','=',$menu_id]])
            ->when(request('from_date'), function ($q) {
              return $q->where('gate_outs.out_date', '>=', request('from_date', 0));
            })
            ->when(request('to_date'), function ($q) {
              return $q->where('gate_outs.out_date', '<=', request('to_date', 0));
            })
            ->orderBy('gate_outs.id','desc')
            ->get()
            ->map(function($rows){
              $rows->entry_time=date('h:i A',strtotime($rows->created_at));
              $rows->out_date=date('d-M-Y',strtotime($rows->out_date));
              $rows->gin_no='--';
              $rows->menu_name="Asset Service Outside";
              return $rows;
          });

          echo json_encode($rows);
      }


    }

}