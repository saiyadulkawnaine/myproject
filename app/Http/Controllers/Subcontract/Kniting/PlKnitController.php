<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\PlKnitRequest;
use Illuminate\Support\Carbon;

class PlKnitController extends Controller {

    private $plknit;
    private $buyer;
    private $company;
    private $supplier;
    private $colorrange;
    private $gmtspart;

    public function __construct(
        PlKnitRepository $plknit,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        SupplierRepository $supplier, 
        ColorrangeRepository $colorrange,
        GmtspartRepository $gmtspart
    ) {
        $this->plknit = $plknit;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->colorrange = $colorrange;
        $this->gmtspart = $gmtspart;
/*  
        $this->middleware('auth');
        $this->middleware('permission:view.plknits',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.plknits', ['only' => ['store']]);
        $this->middleware('permission:edit.plknits',   ['only' => ['update']]);
        $this->middleware('permission:delete.plknits', ['only' => ['destroy']]);

        */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // $from_date=date('Y-m-d');
        // $to=Carbon::parse($from_date);
        // $to->subDays(365);
        // $to_date=date('Y-m-d',strtotime($to));
         
        // $plknits=array();
        // $rows=$this->plknit
        // ->leftJoin('suppliers', function($join)  {
        //     $join->on('pl_knits.supplier_id', '=', 'suppliers.id');
        // })
        // ->leftJoin('companies', function($join)  {
        //     $join->on('pl_knits.company_id', '=', 'companies.id');
        // })
        
        // ->when(request('buyer'), function ($q) {
        //     return $q->where('pl_knits.buyer', '=', request('buyer', 0));
        // })
        // ->when($to_date, function ($q) use($to_date) {
        //     return $q->where('pl_knits.pl_date', '>=',$to_date);
        // })
          
        // ->orderBy('pl_knits.id','desc')
        // ->get([
        //     'pl_knits.*',
        //     'suppliers.name as supplier_id',
        //     'companies.name as company_id'
		// ]);
        // foreach($rows as $row){
        //     $plknit['id']=$row->id;
        //     $plknit['company_id']=$row->company_id;
        //     $plknit['supplier_id']=$row->supplier_id;
        //     $plknit['pl_no']=$row->pl_no;
        //     $plknit['remarks']=$row->remarks;
        //     $plknit['pl_date']=date('Y-m-d',strtotime($row->pl_date));
        //     array_push($plknits,$plknit);
        // }
        // echo json_encode($plknits);
        $rows=$this->plknit
        ->leftJoin('suppliers', function($join)  {
            $join->on('pl_knits.supplier_id', '=', 'suppliers.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('pl_knits.company_id', '=', 'companies.id');
        })
        ->whereNull('pl_knits.deleted_at')
        ->orderBy('pl_knits.id','desc')
        
        ->get([
            'pl_knits.*',
            'suppliers.name as supplier_id',
            'companies.name as company_id'
        ])
        ->map(function($rows){
            $rows->pl_date=date('Y-m-d',strtotime($rows->pl_date));
            return $rows;
        })->take(100);
        
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


        return Template::LoadView('Subcontract.Kniting.PlKnit',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier,'colorrange'=>$colorrange,'fabriclooks'=>$fabriclooks,'fabricshape'=>$fabricshape,'gmtspart'=>$gmtspart]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlKnitRequest $request) {
        $max = $this->plknit->where([['company_id', $request->company_id]])->max('pl_no');
        $pl_no=$max+1;
        $plknit = $this->plknit->create(['pl_no'=>$pl_no,'company_id'=>$request->company_id,'pl_date'=>$request->pl_date,'supplier_id'=>$request->supplier_id,'remarks'=>$request->remarks]);
        if($plknit){
            return response()->json(array('success' => true,'id' =>  $plknit->id,'message' => 'Save Successfully'),200);
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
        $plknit = $this->plknit->find($id);
        $row ['fromData'] = $plknit;
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
    public function update(PlKnitRequest $request, $id) {
        $plknit=$this->plknit->update($id,$request->except(['id','pl_no','company_id']));
        if($plknit){
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
        if($this->plknit->delete($id)){
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
        return $this->plknit->where([['style_ref', 'LIKE', '%'.$request->q.'%']])->orderBy('style_ref', 'asc')->get(['style_ref as name']);

    }

    public function getBuyer(Request $request) {
        return $this->plknit->where([['buyer', 'LIKE', '%'.$request->q.'%']])->orderBy('buyer', 'asc')->get(['buyer as name']);
    }

    public function getOrder(Request $request) {
        return $this->plknit->where([['order_no', 'LIKE', '%'.$request->q.'%']])->orderBy('order_no', 'asc')->get(['order_no as name']);
    }

    public function getPlKnit(){
        $rows=$this->plknit
        ->leftJoin('suppliers', function($join)  {
            $join->on('pl_knits.supplier_id', '=', 'suppliers.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('pl_knits.company_id', '=', 'companies.id');
        })
        ->when('from_date', function ($q) {
            return $q->where('pl_knits.pl_date', '>=',request('from_date', 0));
        })
        ->when(request('to_date'), function ($q) {
            return $q->where('pl_knits.pl_date', '<=', request('to_date', 0));
        })
        ->orderBy('pl_knits.id','desc')
        ->get([
            'pl_knits.*',
            'suppliers.name as supplier_id',
            'companies.name as company_id'
		])
        ->map(function($rows){
            $rows->pl_date=date('Y-m-d',strtotime($rows->pl_date));
            return $rows;
        });
        
        echo json_encode($rows);
    }
}