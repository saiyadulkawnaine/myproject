<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchTrimRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;


use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchRdTrimRequest;

class ProdBatchRdTrimController extends Controller {

    private $prodbatch;
    private $prodbatchtrim;
    private $itemclass;

    public function __construct(
        ProdBatchRepository $prodbatch,  
        ProdBatchTrimRepository $prodbatchtrim ,
        ItemclassRepository $itemclass 

    ) {
        $this->prodbatch = $prodbatch;
        $this->prodbatchtrim = $prodbatchtrim;
        $this->itemclass = $itemclass;
        $this->middleware('auth');
        
        /*$this->middleware('permission:view.prodbatchrdtrims',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodbatchrdtrims', ['only' => ['store']]);
        $this->middleware('permission:edit.prodbatchrdtrims',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodbatchrdtrims', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodbatchtrim=$this->prodbatch
        ->join('prod_batch_trims', function($join)  {
        $join->on('prod_batch_trims.prod_batch_id', '=', 'prod_batches.id');
        })
        ->join('itemclasses', function($join)  {
        $join->on('itemclasses.id', '=', 'prod_batch_trims.itemclass_id');
        })
        ->join('uoms', function($join)  {
        $join->on('uoms.id', '=', 'prod_batch_trims.uom_id');
        })
        ->where([['prod_batches.id','=',request('prod_batch_id')]])
        ->get([
            'prod_batch_trims.*',
            'itemclasses.name as itemclass_name',
            'uoms.code as uom_code',
        ]);
        echo json_encode($prodbatchtrim);
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdBatchRdTrimRequest $request) {
        $batch=$this->prodbatch->find($request->prod_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $request->prod_batch_id,'message' => 'This Batch is Approved. Trim Adding Not Allowed'),200);
        }

        \DB::beginTransaction();
        try
        {
            $prodbatchtrim = $this->prodbatchtrim->create($request->except(['id','itemclass_name']));

            $trim_wgt=$this->prodbatchtrim->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('wgt_qty');
            $prodbatch=$this->prodbatch->find($request->prod_batch_id);
            $batch_wgt=$trim_wgt+$prodbatch->fabric_wgt;

            $prodbatch= $this->prodbatch->update($request->prod_batch_id,[
                'batch_wgt'=>$batch_wgt,
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        if($prodbatchtrim){
            return response()->json(array('success' => true,'id' =>  $prodbatchtrim->id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Save Successfully'),200);
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
        $prodbatchtrim=$this->prodbatchtrim
        ->join('itemclasses', function($join)  {
        $join->on('itemclasses.id', '=', 'prod_batch_trims.itemclass_id');
        })
        ->where([['prod_batch_trims.id','=',$id]])
        ->get([
            'prod_batch_trims.*',
            'itemclasses.name as itemclass_name'
        ])
        ->first();
        $row ['fromData'] = $prodbatchtrim;
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
    public function update(ProdBatchRdTrimRequest $request, $id) {

        $batch=$this->prodbatch->find($request->prod_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Trim Update Not Allowed'),200);
        }

        \DB::beginTransaction();
        try
        {
            $prodbatchtrim = $this->prodbatchtrim->update($id,$request->except(['id','itemclass_name']));
            $trim_wgt=$this->prodbatchtrim->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('wgt_qty');
            $prodbatch=$this->prodbatch->find($request->prod_batch_id);
            $batch_wgt=$trim_wgt+$prodbatch->fabric_wgt;

            $prodbatch= $this->prodbatch->update($request->prod_batch_id,[
                'batch_wgt'=>$batch_wgt,
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($prodbatchtrim){
            return response()->json(array('success' => true,'id' => $id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $prodbatchtrim=$this->prodbatchtrim->find($id);
        $prodbatch=$this->prodbatch->find($prodbatchtrim->prod_batch_id);
        if($prodbatch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Delete Not Allowed'),200);
        }
        if($this->prodbatchtrim->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getTrim() {
        $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
        $trimstype=array_prepend(config('bprs.trimstype'),'-Select-',0);
        $uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
        $rows=$this->prodbatch
        ->join('prod_batch_trims', function($join)  {
        $join->on('prod_batch_trims.prod_batch_id', '=', 'prod_batches.id');
        })
        ->join('itemclasses', function($join)  {
        $join->on('itemclasses.id', '=', 'prod_batch_trims.itemclass_id');
        })
        ->join('itemcategories', function($join)  {
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('uoms', function($join)  {
        $join->on('uoms.id', '=', 'itemclasses.costing_uom_id');
        })
        ->leftJoin(\DB::raw("(
        select
        prod_batch_trims.id as prod_batch_trim_id,
        prod_batch_trims.root_batch_trim_id
        from
        prod_batch_trims
        where prod_batch_trims.prod_batch_id=".request('prod_batch_id',0)."
        ) batchtrim"),"batchtrim.root_batch_trim_id","=","prod_batch_trims.id"
        )
        ->where([['itemcategories.identity','=',6]])
        ->where([['prod_batches.id','=',request('root_batch_id',0)]])
        ->get([
            'itemclasses.*',
            'itemcategories.name as itemcategory',
            'uoms.code as uom',
            'prod_batch_trims.id as root_batch_trim_id',
            'batchtrim.prod_batch_trim_id',
        ])
        ->map(function($rows) use($uomclass,$itemnature,$trimstype){
           $rows->itemnature= $rows->item_nature_id?$itemnature[$rows->item_nature_id]:'';
           $rows->uomclass= $rows->uomclass_id?$uomclass[$rows->uomclass_id]:'';
           return  $rows;
        })
        ->filter(function($rows){
            if(!$rows->prod_batch_trim_id){
                return $rows;
            }
        })
        ->values();
      
        echo json_encode($rows);
    }
}