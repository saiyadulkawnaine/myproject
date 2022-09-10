<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerColorRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ColorRepository;

use App\Library\Template;
use App\Http\Requests\BuyerColorRequest;

class BuyerColorController extends Controller {

    private $buyercolor;
    private $buyer;
    private $color;

    public function __construct(BuyerColorRepository $buyercolor, ColorRepository $color,BuyerRepository $buyer) {
        $this->buyercolor = $buyercolor;
		$this->buyer = $buyer;
        $this->color = $color;
        $this->middleware('auth');
        // $this->middleware('permission:view.buyercolors',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.buyercolors', ['only' => ['store']]);
        // $this->middleware('permission:edit.buyercolors',   ['only' => ['update']]);
        // $this->middleware('permission:delete.buyercolors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $buyercolors=array();
        $rows=$this->$buyercolor->get();
        foreach ($rows as $row) {
          $buyercolor['id']=$row->id;
          $buyercolor['name']=$row->name;
          $buyercolor['code']=$row->code;
          $buyercolor['color']=$color[$row->color_id];
          array_push($buyercolors,$buyercolor);
        }
        echo json_encode($buyercolors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$buyer=$this->buyer
		->leftJoin('buyer_colors', function($join)  {
			$join->on('buyer_colors.buyer_id', '=', 'buyers.id');
			$join->where('buyer_colors.color_id', '=', request('color_id',0));
			$join->whereNull('buyer_colors.deleted_at');
		})
		->get([
		'buyers.id',
		'buyers.name',
		'buyer_colors.id as buyer_color_id'
		]);
		$saved = $buyer->filter(function ($value) {
			if($value->buyer_color_id){
				return $value;
			}
		})->values();
		
		$new = $buyer->filter(function ($value) {
			if(!$value->buyer_color_id){
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
    public function store(BuyerColorRequest $request) {
		foreach($request->buyer_id as $index=>$val){
				$buyercolor = $this->buyercolor->updateOrCreate(
				['color_id' => $request->color_id, 'buyer_id' => $request->buyer_id[$index]]);
		}
        if ($buyercolor) {
            return response()->json(array('success' => true, 'id' => $buyercolor->id, 'message' => 'Save Successfully'), 200);
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
        $buyercolor = $this->buyercolor->find($id);
        $row ['fromData'] = $buyercolor;
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
    public function update(BuyerColorRequest $request, $id) {
        $buyercolor = $this->buyercolor->update($id, $request->except(['id']));
        if ($buyercolor) {
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
        if ($this->buyercolor->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
