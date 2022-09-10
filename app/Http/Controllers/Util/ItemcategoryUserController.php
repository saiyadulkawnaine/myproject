<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemcategoryUserRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\ItemcategoryUserRequest;

class ItemcategoryUserController extends Controller {

    private $itemcategory;
    private $itemcatuser;
    private $user;


    public function __construct(ItemcategoryUserRepository $itemcatuser,ItemcategoryRepository $itemcategory,UserRepository $user) {
        $this->itemcategory = $itemcategory;
        $this->itemcatuser = $itemcatuser;
        $this->user = $user;
        
        
        $this->middleware('auth');
        $this->middleware('permission:view.itemcategoryusers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.itemcategoryusers', ['only' => ['create']]);
        $this->middleware('permission:edit.itemcategoryusers',   ['only' => ['update']]);
        $this->middleware('permission:delete.itemcategoryusers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $itemcatusers=array();
        $rows=$this->$itemcatuser->get();
        foreach ($rows as $row) {
          $itemcatuser['id']=$row->id;
          $itemcatuser['name']=$row->name;
          $itemcatuser['code']=$row->code;
          $itemcatuser['user']=$user[$row->user_id];
          array_push($itemcatusers,$itemcatuser);
        }
        echo json_encode($itemcatusers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$itemcategory=$this->itemcategory
		->leftJoin('itemcategory_users', function($join)  {
			$join->on('itemcategory_users.itemcategory_id', '=', 'itemcategories.id');
			$join->where('itemcategory_users.user_id', '=', request('user_id',0));
			$join->whereNull('itemcategory_users.deleted_at');
		})
		->get([
		'itemcategories.id',
		'itemcategories.name',
		'itemcategory_users.id as itemcategory_user_id'
		]);
		$saved = $itemcategory->filter(function ($value) {
			if($value->itemcategory_user_id){
				return $value;
			}
		})->values();
		
		$new = $itemcategory->filter(function ($value) {
			if(!$value->itemcategory_user_id){
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
    public function store(ItemcategoryUserRequest $request) {
		foreach($request->itemcategory_id as $index=>$val){
				$itemcatuser = $this->itemcatuser->updateOrCreate(
				['user_id' => $request->user_id, 'itemcategory_id' => $request->itemcategory_id[$index]]);
		}
        if ($itemcatuser) {
            return response()->json(array('success' => true, 'id' => $itemcatuser->id, 'message' => 'Save Successfully'), 200);
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
        $itemcatuser = $this->itemcatuser->find($id);
        $row ['fromData'] = $itemcatuser;
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
    public function update(ItemcategoryUserRequest $request, $id) {
        $itemcatuser = $this->itemcatuser->update($id, $request->except(['id']));
        if ($itemcatuser) {
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
        if ($this->itemcatuser->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
