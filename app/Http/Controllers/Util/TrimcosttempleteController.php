<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TrimcosttempleteRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\TrimcosttempleteRequest;

class TrimcosttempleteController extends Controller
{
    private $trimcosttemplete;
    private $buyer;
    private $supplier;
    private $uom;

    public function __construct(TrimcosttempleteRepository $trimcosttemplete,BuyerRepository $buyer,SupplierRepository $supplier,UomRepository $uom) {
        $this->trimcosttemplete = $trimcosttemplete;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->uom = $uom;
        $this->middleware('auth');
        $this->middleware('permission:view.trimcosttempletes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.trimcosttempletes', ['only' => ['store']]);
        $this->middleware('permission:edit.trimcosttempletes',   ['only' => ['update']]);
        $this->middleware('permission:delete.trimcosttempletes', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');

        $trimcosttempletes=array();
        $rows=$this->trimcosttemplete->get();
        foreach ($rows as $row){
            $trimcosttemplete['id']=$row->id;

            $trimcosttemplete['buyer']=$buyer[$row->buyer_id];
            $trimcosttemplete['supplier']=$supplier[$row->supplier_id];
            $trimcosttemplete['uom']=$uom[$row->uom_id];

            array_push($trimcosttempletes,$trimcosttemplete);
        }
        echo json_encode($trimcosttempletes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
        return Template::loadView("Util\Trimcosttemplete",['buyer'=>$buyer,'supplier'=>$supplier,'uom'=>$uom]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrimcosttempleteRequest $request) {
        $trimcosttemplete = $this->trimcosttemplete->create($request->except(['id']));
        if ($trimcosttemplete) {
            return response()->json(array('success' => true, 'id' => $trimcosttemplete->id, 'message' => 'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trimcosttemplete = $this->trimcosttemplete->find($id);
        $row ['fromData'] = $trimcosttemplete;
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
     public function update(TrimcosttempleteRequest $request, $id) {
        $trimcosttemplete = $this->trimcosttemplete->update($id, $request->except(['id']));
        if ($trimcosttemplete) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->trimcosttemplete->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
