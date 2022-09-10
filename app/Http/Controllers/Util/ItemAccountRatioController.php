<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemAccountRatioRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Library\Template;
use App\Http\Requests\ItemAccountRatioRequest;

class ItemAccountRatioController extends Controller {

  private $itemaccountratio;
  private $itemaccount;
  private $composition;

    public function __construct(ItemAccountRatioRepository $itemaccountratio,ItemAccountRepository $itemaccount,CompositionRepository $composition) {
      $this->itemaccountratio = $itemaccountratio;
      $this->itemaccount = $itemaccount;
      $this->composition = $composition;
      $this->middleware('auth');
      $this->middleware('permission:view.itemaccountratios',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.itemaccountratios', ['only' => ['store']]);
      $this->middleware('permission:edit.itemaccountratios',   ['only' => ['update']]);
      $this->middleware('permission:delete.itemaccountratios', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $itemaccount=array_prepend(array_pluck($this->itemaccount->get(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $itemaccountratios=array();
	    $rows=$this->itemaccountratio->where([['item_account_id','=',request('item_account_id',0)]])->orderBy('id','desc')->get();
  		foreach($rows as $row){
        $itemaccountratio['id']=	$row->id;
        $itemaccountratio['ratio']=	$row->ratio;
        $itemaccountratio['itemaccount']=	$itemaccount[$row->item_account_id];
        $itemaccountratio['composition']=	$composition[$row->composition_id];
  		   array_push($itemaccountratios,$itemaccountratio);
  		}
        echo json_encode($itemaccountratios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $itemaccount=array_prepend(array_pluck($this->itemaccount->get(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.ItemAccountRatio", ["itemaccount"=> $itemaccount,'composition'=>$composition]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemAccountRatioRequest $request) {
        $itemaccountratio = $this->itemaccountratio->create($request->except(['id']));
        if ($itemaccountratio) {
            return response()->json(array('success' => true, 'id' => $itemaccountratio->id, 'message' => 'Save Successfully'), 200);
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
        $itemaccountratio = $this->itemaccountratio->find($id);
        $row ['fromData'] = $itemaccountratio;
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
    public function update(ItemAccountRatioRequest $request, $id) {
        $itemaccountratio = $this->itemaccountratio->update($id, $request->except(['id']));
        if ($itemaccountratio) {
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
        if ($this->itemaccountratio->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
