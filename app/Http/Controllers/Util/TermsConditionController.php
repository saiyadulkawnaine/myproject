<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TermsConditionRepository;
//use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\TermsConditionRequest;

class TermsConditionController extends Controller
{
    private $termscondition;
	  //private $user;

    public function __construct(TermsConditionRepository $termscondition) {
        $this->termscondition = $termscondition;
		   //$this->user = $user;,UserRepository $user
        $this->middleware('auth');
        $this->middleware('permission:view.termsconditions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.termsconditions', ['only' => ['store']]);
        $this->middleware('permission:edit.termsconditions',   ['only' => ['update']]);
        $this->middleware('permission:delete.termsconditions', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $menu=array_prepend(config('bprs.menu'),'-Select-','');
      $termsconditions=array();
      $rows=$this->termscondition->get();
      foreach ($rows as $row) {
        $termscondition['id']=$row->id;
        $termscondition['term']=$row->term;
        $termscondition['menu']=$menu[$row->menu_id];
        $termscondition['sort_id']=$row->sort_id;
        array_push($termsconditions,$termscondition);
      }
        echo json_encode($termsconditions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		// $membertype=array_prepend(config('bprs.membertype'),'-Select-',0);
		// $termsconditiontype=array_prepend(config('bprs.termsconditiontype'),'-Select-',0);
		//$user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-',0);
		$menu=array_prepend(config('bprs.menu'),'-Select-','');
        return Template::loadView("Util.TermsCondition",['menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TermsConditionRequest $request) {
        $termscondition = $this->termscondition->create($request->except(['id']));
        if ($termscondition) {
            return response()->json(array('success' => true, 'id' => $termscondition->id, 'message' => 'Save Successfully'), 200);
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
        $termscondition = $this->termscondition->find($id);
        $row ['fromData'] = $termscondition;
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
    public function update(TermsConditionRequest $request, $id) {
        $termscondition = $this->termscondition->update($id, $request->except(['id']));
        if ($termscondition) {
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
        if ($this->termscondition->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
