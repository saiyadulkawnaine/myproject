<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerSizeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SizeRepository;

use App\Library\Template;
use App\Http\Requests\BuyerSizeRequest;

class BuyerSizeController extends Controller {

    private $buyersize;
    private $buyer;
    private $size;

    public function __construct(BuyerSizeRepository $buyersize, SizeRepository $size,BuyerRepository $buyer) {
        $this->buyersize = $buyersize;
		$this->buyer = $buyer;
        $this->size = $size;
        $this->middleware('auth');
        // $this->middleware('permission:view.buyersizes',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.buyersizes', ['only' => ['store']]);
        // $this->middleware('permission:edit.buyersizes',   ['only' => ['update']]);
        // $this->middleware('permission:delete.buyersizes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
        $buyersizes=array();
        $rows=$this->$buyersize->get();
        foreach ($rows as $row) {
          $buyersize['id']=$row->id;
          $buyersize['name']=$row->name;
          $buyersize['code']=$row->code;
          $buyersize['size']=$size[$row->size_id];
          array_push($buyersizes,$buyersize);
        }
        echo json_encode($buyersizes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$buyer=$this->buyer
		->leftJoin('buyer_sizes', function($join)  {
			$join->on('buyer_sizes.buyer_id', '=', 'buyers.id');
			$join->where('buyer_sizes.size_id', '=', request('size_id',0));
			$join->whereNull('buyer_sizes.deleted_at');
		})
		->get([
		'buyers.id',
		'buyers.name',
		'buyer_sizes.id as buyer_size_id'
		]);
		$saved = $buyer->filter(function ($value) {
			if($value->buyer_size_id){
				return $value;
			}
		})->values();
		
		$new = $buyer->filter(function ($value) {
			if(!$value->buyer_size_id){
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
    public function store(BuyerSizeRequest $request) {
		foreach($request->buyer_id as $index=>$val){
				$buyersize = $this->buyersize->updateOrCreate(
				['size_id' => $request->size_id, 'buyer_id' => $request->buyer_id[$index]]);
		}
        if ($buyersize) {
            return response()->json(array('success' => true, 'id' => $buyersize->id, 'message' => 'Save Successfully'), 200);
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
        $buyersize = $this->buyersize->find($id);
        $row ['fromData'] = $buyersize;
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
    public function update(BuyerSizeRequest $request, $id) {
        $buyersize = $this->buyersize->update($id, $request->except(['id']));
        if ($buyersize) {
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
        if ($this->buyersize->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
