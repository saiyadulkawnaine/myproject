<?php
namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaOrdRequest;

class PlaningBoardController extends Controller {

    private $tnaord;
    private $company;
    private $location;
    private $buyer;
    private $assetacquisition;
    private $plknititem;

    public function __construct(
        TnaOrdRepository $tnaord,
        CompanyRepository $company,
        LocationRepository $location,
        BuyerRepository $buyer,
        AssetAcquisitionRepository $assetacquisition,
        PlKnitItemRepository $plknititem
    ) {
        $this->tnaord = $tnaord;
        $this->company = $company;
        $this->location = $location;
        $this->buyer = $buyer;
        $this->assetacquisition = $assetacquisition;
        $this->plknititem = $plknititem;

        $this->middleware('auth');
        $this->middleware('permission:view.tnaords',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.tnaords', ['only' => ['store']]);
        $this->middleware('permission:edit.tnaords',   ['only' => ['update']]);
        $this->middleware('permission:delete.tnaords', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      /*$path= public_path('images')."/APR-K.csv";
      $row = 1;
      \DB::beginTransaction();
      if (($handle = fopen($path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
          if($row<=220){
            if($row==1){
            }
            else{
              try
              {
                $this->tnaord->updateOrCreate([
                    'sales_order_id' => $data[0],
                    'tna_task_id' => $data[1],
                ],[
                    'tna_start_date' => $data[2]?date('Y-m-d',strtotime($data[2])):NULL,
                    'tna_end_date' => $data[3]?date('Y-m-d',strtotime($data[3])):NULL,
                ]);
              }
              catch(EXCEPTION $e)
              {
                \DB::rollback();
                throw $e;
              }
            }
          }
          $row++;
        }
        fclose($handle);
      }
      \DB::commit();
      echo $row;

      die;*/
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView("Planing.PlaningBoard",['company'=>$company,'location'=>$location,'yesno'=>$yesno,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnaOrdRequest $request) {
        $tnaord = $this->tnaord->create($request->except(['id']));
        if ($tnaord) {
            return response()->json(array('success' => true, 'id' => $tnaord->id, 'message' => 'Save Successfully'), 200);
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
        $tnaord = $this->tnaord->find($id);
        $row ['fromData'] = $tnaord;
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
    public function update(TnaOrdRequest $request, $id) {
        $tnaord = $this->tnaord->update($id, $request->except(['id']));
        if ($tnaord) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->tnaord->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function loadResource(){

        $groups = [
            'id'=>"group_1",
            'name'=>"Machine",
            'expanded'=>true,
            'children'=>[],
        ];
        /*$g = new Group();
        $g->id = "group_1";
        $g->name = 'Machine';
        $g->expanded = true;
        $g->children = array();
        $groups[] = $g;*/

        $machines=$this->assetacquisition
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_acquisitions.id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('dia_width'), function ($q) {
        return $q->where('asset_technical_features.dia_width', '=>',request('dia_width', 0));
        })
        ->when(request('no_of_feeder'), function ($q) {
        return $q->where('asset_technical_features.no_of_feeder', '<=',request('no_of_feeder', 0));
        })
        ->where([['asset_acquisitions.production_area_id','=',10]])
        //->where([['asset_acquisitions.company_id','=',request('company_id',0)]])
        //->where([['asset_acquisitions.location_id','=',request('location_id',0)]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder'
        ]);

        foreach($machines as $resource) {
        $r=[];
        $r['id'] = $resource['id'];
        $r['name'] = $resource['asset_name'];
        $groups['children'][] = $r;
        }
        /*header('Content-Type: application/json');
        echo json_encode([$groups]);*/
        return response()->json([$groups],200);

    }
    public function loadPlan(){
      $rows=$this->plknititem
      ->join('pl_knits', function($join)  {
      $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
      })
      ->leftJoin('asset_quantity_costs',function($join){
      $join->on('asset_quantity_costs.id','=','pl_knit_items.machine_id');
      })
      ->join('colorranges', function($join)  {
      $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
      })
      ->join('so_knit_refs', function($join)  {
      $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
      })
      ->leftJoin('so_knit_po_items', function($join)  {
      $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
      })
      ->leftJoin('po_knit_service_item_qties',function($join){
      $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
      })
      ->leftJoin('po_knit_service_items',function($join){
      $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
      })
      ->leftJoin('budget_fabric_prods',function($join){
      $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
      })
      ->leftJoin('budget_fabrics',function($join){
      $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
      })
      ->leftJoin('style_fabrications',function($join){
      $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->leftJoin('so_knit_items', function($join)  {
      $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
      })
      ->leftJoin('colors as so_color',function($join){
      $join->on('so_color.id','=','so_knit_items.fabric_color_id');
      })
      ->leftJoin('colors as po_color',function($join){
      $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
      })
      ->orderBy('pl_knit_items.id','desc')
      ->where([['pl_knits.id','=',101]])
      ->get([
      'pl_knit_items.*',
      'colorranges.name as colorrange_id',
      'style_fabrications.autoyarn_id',
      'so_knit_items.autoyarn_id as c_autoyarn_id',
      'asset_quantity_costs.custom_no as machine_no',
      'so_color.name as c_fabric_color_name',
      'po_color.name as fabric_color_name',

      ])
      ;
      $events=[];
      $i=1;
      foreach($rows as $row) {
       // $r=[];
        $r['id'] = $row->id;
        $r['resource'] = $row->machine_id*1;
        $r['name'] = 'Plan_'.$i;
        $r['start'] = date("Y-m-d H:i:s",strtotime($row->pl_start_date));
        $r['end'] = date("Y-m-d H:i:s",strtotime($row->pl_end_date));
        $r['text'] = 'Plan_'.$i;
        
        $events[]=$r;
        $i++;
        }
        /*header('Content-Type: application/json');
        echo json_encode($events);*/
          return response()->json($events,200);
    }



}
