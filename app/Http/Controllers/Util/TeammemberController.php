<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Library\Template;
use App\Http\Requests\TeammemberRequest;

class TeammemberController extends Controller
{
    private $teammember;
    private $team;
	  private $user;

    public function __construct(TeammemberRepository $teammember,UserRepository $user,TeamRepository $team) {
        $this->teammember = $teammember;
        $this->user = $user;
		    $this->team = $team;
        $this->middleware('auth');
        $this->middleware('permission:view.teammembers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.teammembers', ['only' => ['store']]);
        $this->middleware('permission:edit.teammembers',   ['only' => ['update']]);
        $this->middleware('permission:delete.teammembers', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user=array_pluck($this->user->get(),'name','id');
		$query = $this->teammember->query();
		$query->when(request('team_id'), function ($q) {
			return $q->where('team_id', '=', request('team_id', 0));
		});
		$teammembers=$query->get();
		$membertype=array_prepend(config('bprs.membertype'),'-Select-',0);
    $team=array_pluck($this->team->get(),'name','id');
    $rows=array();
		foreach($teammembers as $row){
			$teammember['id']=$row->id;
			$teammember['user_id']=$row->user_id;
            $teammember['team']=$team[$row->team_id];
			$teammember['type_id']=$row->type_id;
			$teammember['name']=$user[$row->user_id];
			$teammember['type']=$membertype[$row->type_id];
			array_push($rows, $teammember);
		}
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-',0);
        return Template::loadView("Util.Teammember",['user'=>$user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeammemberRequest $request) {
        $teammember = $this->teammember->create($request->except(['id']));
        if ($teammember) {
            return response()->json(array('success' => true, 'id' => $teammember->id, 'message' => 'Save Successfully'), 200);
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
        $teammember = $this->teammember->find($id);
        $row ['fromData'] = $teammember;
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
    public function update(TeammemberRequest $request, $id) {
        $teammember = $this->teammember->update($id, $request->except(['id']));
        if ($teammember) {
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
        if ($this->teammember->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
