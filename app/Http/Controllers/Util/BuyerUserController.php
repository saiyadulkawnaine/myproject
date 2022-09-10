<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerUserRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\BuyerUserRequest;

class BuyerUserController extends Controller {

    private $buyeruser;
    private $buyer;
    private $user;

    public function __construct(BuyerUserRepository $buyeruser, UserRepository $user,BuyerRepository $buyer) {
        $this->buyeruser = $buyeruser;
		$this->buyer = $buyer;
        $this->user = $user;
        $this->middleware('auth');
        $this->middleware('permission:view.buyerusers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyerusers', ['only' => ['store']]);
        $this->middleware('permission:edit.buyerusers',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyerusers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $buyerusers=array();
        $rows=$this->$buyeruser->get();
        foreach ($rows as $row) {
          $buyeruser['id']=$row->id;
          $buyeruser['name']=$row->name;
          $buyeruser['code']=$row->code;
          $buyeruser['user']=$user[$row->user_id];
          array_push($buyerusers,$buyeruser);
        }
        echo json_encode($buyerusers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$buyer=$this->buyer
		->leftJoin('buyer_users', function($join)  {
			$join->on('buyer_users.buyer_id', '=', 'buyers.id');
			$join->where('buyer_users.user_id', '=', request('user_id',0));
			$join->whereNull('buyer_users.deleted_at');
		})
		->get([
		'buyers.id',
		'buyers.name',
		'buyer_users.id as buyer_user_id'
		]);
		$saved = $buyer->filter(function ($value) {
			if($value->buyer_user_id){
				return $value;
			}
		})->values();
		
		$new = $buyer->filter(function ($value) {
			if(!$value->buyer_user_id){
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
    public function store(BuyerUserRequest $request) {
		foreach($request->buyer_id as $index=>$val){
				$buyeruser = $this->buyeruser->updateOrCreate(
				['user_id' => $request->user_id, 'buyer_id' => $request->buyer_id[$index]]);
		}
        if ($buyeruser) {
            return response()->json(array('success' => true, 'id' => $buyeruser->id, 'message' => 'Save Successfully'), 200);
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
        $buyeruser = $this->buyeruser->find($id);
        $row ['fromData'] = $buyeruser;
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
    public function update(BuyerUserRequest $request, $id) {
        $buyeruser = $this->buyeruser->update($id, $request->except(['id']));
        if ($buyeruser) {
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
        if ($this->buyeruser->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
