<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\Util\ContactNatureRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\BuyerNatureRequest;

class BuyerNatureController extends Controller {

    private $buyernature;
	private $contactnature;
    private $buyer;

    public function __construct(BuyerNatureRepository $buyernature, BuyerRepository $buyer,ContactNatureRepository $contactnature) {
        $this->buyernature = $buyernature;
		$this->contactnature = $contactnature;
        $this->buyer = $buyer;
        $this->middleware('auth');
        $this->middleware('permission:view.buyernatures',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyernatures', ['only' => ['store']]);
        $this->middleware('permission:edit.buyernatures',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyernatures', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $buyernatures=array();
        $rows=$this->buyernature->get();
        foreach ($rows as $row) {
          $buyernature['id']=$row->id;
          $buyernature['name']=$row->name;
          $buyernature['code']=$row->code;
          $buyernature['buyer']=$buyer[$row->buyer_id];
          array_push($buyernatures,$buyernature);
        }
        echo json_encode($buyernatures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$contactnature=$this->contactnature
		->leftJoin('buyer_natures', function($join)  {
			$join->on('buyer_natures.contact_nature_id', '=', 'contact_natures.id');
			$join->where('buyer_natures.buyer_id', '=', request('buyer_id',0));
			$join->whereNull('buyer_natures.deleted_at');
		})
        ->where([['contact_natures.nature_type','=',1]])
		->get([
		'contact_natures.id',
		'contact_natures.name',
		'buyer_natures.id as buyer_nature_id'
		]);
		$saved = $contactnature->filter(function ($value) {
			if($value->buyer_nature_id){
				return $value;
			}
		})->values();
		
		$new = $contactnature->filter(function ($value) {
			if(!$value->buyer_nature_id){
				return $value;
			}
		})->values();
		$row ['unsaved'] = $new;
		$row ['saved'] = $saved;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerNatureRequest $request) {
        //$buyernature = $this->buyernature->create($request->except(['id']));
		foreach($request->contact_nature_id as $index=>$val){
				$buyernature = $this->buyernature->updateOrCreate(
				['buyer_id' => $request->buyer_id, 'contact_nature_id' => $request->contact_nature_id[$index]]);
		}
        if ($buyernature) {
            return response()->json(array('success' => true, 'id' => $buyernature->id, 'message' => 'Save Successfully'), 200);
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
        $buyernature = $this->buyernature->find($id);
        $row ['fromData'] = $buyernature;
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
    public function update(BuyerNatureRequest $request, $id) {
        $buyernature = $this->buyernature->update($id, $request->except(['id']));
        if ($buyernature) {
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
        if ($this->buyernature->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
