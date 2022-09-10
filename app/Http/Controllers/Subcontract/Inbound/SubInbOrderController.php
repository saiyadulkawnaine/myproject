<?php

namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbOrderRequest;

class SubInbOrderController extends Controller {

    private $subinborder;
    private $subinbmarketing;
    private $company;
    private $buyer;
    private $uom;

    public function __construct(SubInbOrderRepository $subinborder,BuyerRepository $buyer,CompanyRepository $company, UomRepository $uom, SubInbMarketingRepository $subinbmarketing, ItemAccountRepository $itemaccount, ItemclassRepository $itemclass, ItemcategoryRepository $itemcategory, SubInbOrderProductRepository $subinborderproduct) {
        $this->subinborder = $subinborder;
        $this->subinbmarketing = $subinbmarketing;
        $this->subinborderproduct = $subinborderproduct;
        $this->itemaccount = $itemaccount;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;

        $this->middleware('auth');
        $this->middleware('permission:view.subinborders',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.subinborders', ['only' => ['store']]);
        $this->middleware('permission:edit.subinborders',   ['only' => ['update']]);
        $this->middleware('permission:delete.subinborders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[10,20,25]),'-Select-','');
        //$productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
         
        $subinborders=array();
        $rows=$this->subinborder
        ->leftJoin('buyers', function($join)  {
            $join->on('sub_inb_orders.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('sub_inb_orders.company_id', '=', 'companies.id');
        })
        ->leftJoin('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_orders.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
        })
        ->when(request('buyer'), function ($q) {
            return $q->where('sub_inb_orders.buyer', '=', request('buyer', 0));
        })
        ->when(request('style_ref'), function ($q) {
            return $q->where('sub_inb_orders.style_ref', 'like', '%'.request('style_ref', 0).'%');
        })
        ->when(request('order_no'), function ($q) {
            return $q->where('sub_inb_orders.order_no', 'like', '%'.request('order_no', 0).'%');
        })  
        ->orderBy('sub_inb_orders.id','desc')
        ->get([
            'sub_inb_orders.*',
            'sub_inb_marketings.id as sub_inb_marketing_id',
            'buyers.name as buyer_id',
            'companies.name as company_id'
		]);
        foreach($rows as $row){
            $subinborder['id']=$row->id;
            $subinborder['company_id']=$row->company_id;
            $subinborder['production_area_id']=$productionarea[$row->production_area_id];
            $subinborder['sub_inb_marketing_id']=$row->sub_inb_marketing_id;
            $subinborder['buyer_id']=$row->buyer_id;
            $subinborder['sales_order_no']=$row->sales_order_no;
            $subinborder['recv_date']=date('Y-m-d',strtotime($row->recv_date));
            $subinborder['buyer']=$row->buyer;
            $subinborder['style_ref']=$row->style_ref;
            $subinborder['order_no']=$row->order_no;
            $subinborder['remarks']=$row->remarks;
            array_push($subinborders,$subinborder);
        }
        echo json_encode($subinborders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');//->where([['identity','=',9]])
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[10,20,25]),'-Select-','');
        $subinborder=array_prepend(array_pluck($this->subinborder->get(),'name','id'),'-Select-','');
        $subinborderproduct=array_prepend(array_pluck($this->subinborderproduct->get(),'name','id'),'-Select-','');

        return Template::LoadView('Subcontract.Inbound.SubInbOrder',['company'=>$company,'buyer'=>$buyer,'productionarea'=>$productionarea,'uom'=>$uom,'itemcategory'=>$itemcategory,'itemclass'=>$itemclass,'itemnature'=>$itemnature,'subinborderproduct'=>$subinborderproduct,'subinborder'=>$subinborder]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubInbOrderRequest $request) {
		$subinborder=$this->subinborder->create($request->except(['id']));
        if($subinborder){
            return response()->json(array('success' => true,'id' =>  $subinborder->id,'message' => 'Save Successfully'),200);
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
        $subinborder = $this->subinborder
        ->selectRaw(
            '
            sub_inb_orders.id,
            sub_inb_orders.company_id,
            sub_inb_orders.buyer_id,
            sub_inb_orders.sub_inb_marketing_id,
            sub_inb_orders.production_area_id,
            sub_inb_orders.sales_order_no,
            sub_inb_orders.recv_date,
            sub_inb_orders.buyer,
            sub_inb_orders.style_ref,
            sub_inb_orders.order_no,
            sub_inb_orders.remarks
            '
        )
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_orders.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'sub_inb_orders.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'sub_inb_orders.company_id');
        })   
        ->where([['sub_inb_orders.id','=',$id]])
        ->get([
            'sub_inb_orders.*',
            'sub_inb_marketings.id as sub_inb_marketing_id',
            'buyers.name as buyer_id',
            'companies.name as company_id'
		])
        ->first();
        $row ['fromData'] = $subinborder;
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
    public function update(SubInbOrderRequest $request, $id) {
        $subinborder=$this->subinborder->update($id,$request->except(['id']));
        if($subinborder){
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
        if($this->subinborder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getMktRef(){
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        $rows=$this->subinbmarketing
        ->leftJoin('buyers', function($join)  {
            $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
        })
        ->leftJoin('teams', function($join)  {
            $join->on('sub_inb_marketings.team_id', '=', 'teams.id');
        })
        ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'sub_inb_marketings.teammember_id');
        })
        ->leftJoin('users', function($join)  {
            $join->on('users.id', '=', 'teammembers.user_id');
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('sub_inb_marketings.company_id', '=', request('company_id', 0));
        })
        ->when(request('production_area_id'), function ($q) {
            return $q->where('sub_inb_marketings.production_area_id', '=' , request('production_area_id',0));
        })
        ->when(request('buyer_id'), function($q) {
            return $q->where('sub_inb_marketings.buyer_id', '=' , request('buyer_id',0));
        })
        ->when(request('mkt_date'), function($q) {
            return $q->where('sub_inb_marketings.mkt_date', '=' , request('mkt_date',0));
        })
        ->orderBy('sub_inb_marketings.id','desc')
        ->get(['sub_inb_marketings.*',
            'buyers.name as buyer_id',
            'companies.name as company_id',
            'teams.name as team_name',
            'users.name as team_member_name'
		]);
        
        echo json_encode($rows);
    }

    public function getStyleRef(Request $request) {
        return $this->subinborder->where([['style_ref', 'LIKE', '%'.$request->q.'%']])->orderBy('style_ref', 'asc')->get(['style_ref as name']);

    }

    public function getBuyer(Request $request) {
        return $this->subinborder->where([['buyer', 'LIKE', '%'.$request->q.'%']])->orderBy('buyer', 'asc')->get(['buyer as name']);
    }

    public function getOrder(Request $request) {
        return $this->subinborder->where([['order_no', 'LIKE', '%'.$request->q.'%']])->orderBy('order_no', 'asc')->get(['order_no as name']);
    }
}