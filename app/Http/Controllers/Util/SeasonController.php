<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\SeasonRequest;

class SeasonController extends Controller {

    private $season;
    private $buyer;

    public function __construct(SeasonRepository $season,BuyerRepository $buyer) {
        $this->season = $season;
        $this->buyer = $buyer;
        $this->middleware('auth');
        $this->middleware('permission:view.seasons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.seasons', ['only' => ['store']]);
        $this->middleware('permission:edit.seasons',   ['only' => ['update']]);
        $this->middleware('permission:delete.seasons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $seasons=array();
      $rows=$this->season->get();
      foreach ($rows as $row) {
        $season['id']=$row->id;
        $season['name']=$row->name;
        $season['buyer']=$buyer[$row->buyer_id];
        array_push($seasons,$season);
      }
        echo json_encode($seasons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.Season",['buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonRequest $request) {
        $season = $this->season->create($request->except(['id']));
        if ($season) {
            return response()->json(array('success' => true, 'id' => $season->id, 'message' => 'Save Successfully'), 200);
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
        $season = $this->season->find($id);
        $row ['fromData'] = $season;
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
    public function update(SeasonRequest $request, $id) {
        $season = $this->season->update($id, $request->except(['id']));
        if ($season) {
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
        if ($this->season->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
