<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PurchaseTermsConditionRequest;

class PurchaseTermsConditionController extends Controller
{
    private $purchasetermscondition;
    private $potrim;
	private $user;

    public function __construct(PurchaseTermsConditionRepository $purchasetermscondition, UserRepository $user, PoTrimRepository $potrim) {
        $this->purchasetermscondition = $purchasetermscondition;
        $this->potrim = $potrim;
		$this->user = $user;

        $this->middleware('auth');
        $this->middleware('permission:view.purchasetermsconditions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.purchasetermsconditions', ['only' => ['store']]);
        $this->middleware('permission:edit.purchasetermsconditions',   ['only' => ['update']]);
        $this->middleware('permission:delete.purchasetermsconditions', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $purchasetermsconditions=array();
      $rows=$this->purchasetermscondition->where([['purchase_order_id','=',request('purchase_order_id',0)]])->where([['menu_id','=',request('menu_id',0)]])->orderBy('sort_id')->get();
      foreach ($rows as $row) {
        $purchasetermscondition['id']=$row->id;
        $purchasetermscondition['term']=$row->term;
        $purchasetermscondition['sort_id']=$row->sort_id;
        array_push($purchasetermsconditions,$purchasetermscondition);
      }
        echo json_encode($purchasetermsconditions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Template::loadView("Purchase.PurchaseTermsCondition",[]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseTermsConditionRequest $request) {
        $potrim=$this->potrim->find($request->purchase_order_id);
        if($request->menu_id==2 && $potrim->approved_at){
            return response()->json(array('success' => false,  'message' => 'Trim Purchase Order is Approved, So Save Or Update not Possible'), 200);
        }
        $purchasetermscondition = $this->purchasetermscondition->create($request->except(['id']));
        if ($purchasetermscondition) {
            return response()->json(array('success' => true, 'id' => $purchasetermscondition->id, 'message' => 'Save Successfully'), 200);
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
        $purchasetermscondition = $this->purchasetermscondition->find($id);
        $row ['fromData'] = $purchasetermscondition;
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
    public function update(PurchaseTermsConditionRequest $request, $id) {
        $potrim=$this->potrim->find($request->purchase_order_id);
        if($request->menu_id==2 && $potrim->approved_at){
            return response()->json(array('success' => false,  'message' => 'Trim Purchase Order is Approved, So Save Or Update not Possible'), 200);
        }
        $purchasetermscondition = $this->purchasetermscondition->update($id, $request->except(['id']));
        if ($purchasetermscondition) {
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
        if ($this->purchasetermscondition->where([['id','=',$id]])->delete()) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
