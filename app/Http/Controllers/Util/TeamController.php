<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\TeamRequest;

class TeamController extends Controller
{
    private $team;
	  private $user;

    public function __construct(TeamRepository $team,UserRepository $user) {
        $this->team = $team;
		    $this->user = $user;
        $this->middleware('auth');
        $this->middleware('permission:view.teams',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.teams', ['only' => ['store']]);
        $this->middleware('permission:edit.teams',   ['only' => ['update']]);
        $this->middleware('permission:delete.teams', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $teams=array();
      $rows=$this->team->get();
      foreach ($rows as $row) {
        $team['id']=$row->id;
        $team['name']=$row->name;
        $team['code']=$row->code;
        array_push($teams,$team);
      }
        echo json_encode($teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$membertype=array_prepend(config('bprs.membertype'),'-Select-',0);
		$teamtype=array_prepend(config('bprs.teamtype'),'-Select-',0);
		$user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-',0);
        return Template::loadView("Util.Team",['teamtype'=>$teamtype,'user'=>$user,'membertype'=>$membertype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request) {
        $team = $this->team->create($request->except(['id']));
        if ($team) {
            return response()->json(array('success' => true, 'id' => $team->id, 'message' => 'Save Successfully'), 200);
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
        $team = $this->team->find($id);
        $row ['fromData'] = $team;
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
    public function update(TeamRequest $request, $id) {
        $team = $this->team->update($id, $request->except(['id']));
        if ($team) {
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
        if ($this->team->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
