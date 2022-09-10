<?php

namespace App\Http\Controllers\GateEntry;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\GateEntry\GateEntryRepository;
use App\Repositories\Contracts\GateEntry\GateEntryItemRepository;

use App\Library\Template;
use App\Http\Requests\GateEntry\GateEntryItemRequest;

class GateEntryItemController extends Controller {
    private $gateentry;
    private $gateentryitem;
    

    public function __construct(
        GateEntryRepository $gateentry,
        GateEntryItemRepository $gateentryitem
    ) {
      $this->gateentry  = $gateentry;
      $this->gateentryitem = $gateentryitem;

      $this->middleware('auth');
      //$this->middleware('permission:view.srmproductgateentrys',   ['only' => ['create', 'index','show']]);
     // $this->middleware('permission:create.srmproductgateentrys', ['only' => ['store']]);
      //$this->middleware('permission:edit.srmproductgateentrys',   ['only' => ['update']]);
      //$this->middleware('permission:delete.srmproductgateentrys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

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
    public function store(GateEntryItemRequest $request) {
        $gateentryitem = $this->gateentryitem->create($request->except(['id']));
        if($gateentryitem){
            return response()->json(array('success' => true,'id' => $gateentryitem->id,'message' => 'Update Successfully'),200);
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
        $gateentryitem = $this->gateentryitem->find($id);
        $row ['fromData'] = $gateentryitem;
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
    public function update(GateEntryItemRequest $request, $id) {

        // $gateentry=$this->gateentry->update($request->gate_entry_id,[
        //     'menu_id'=>$request->menu_id,
        //     'barcode_no_id'=>$request->barcode_no_id,
        //     'challan_no'=>$request->challan_no,
        //     'comments'=>$request->comments
        //     ]);

        if($request->gate_entry_id){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
        else{
            foreach($request->item_id as $index=>$item_id){
                if($item_id && $request->qty[$index])
                {
                    $gateentryitem = $this->gateentryitem->create([
                    //  'gate_entry_id'=>$gateentry->id,
                      'item_id'=>$item_id,
                      'qty'=>$request->qty[$index],
                      'remarks'=>$request->remarks[$index],
                    ]);
                }
              }
        }

     
            

        

        //   if($gateentry){
        //     return response()->json(array('success' => true,'id' =>  $id,'message' => 'Save Successfully'),200);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->gateentryitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
