<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Library\Template;
use App\Http\Requests\BuyerBranchRequest;

class BuyerBranchController extends Controller {

    private $buyerbranch;
    private $buyer;
    private $country;

    public function __construct(BuyerBranchRepository $buyerbranch, BuyerRepository $buyer, CountryRepository $country) {
        $this->buyerbranch = $buyerbranch;
        $this->buyer = $buyer;
        $this->country = $country;

        $this->middleware('auth');
        $this->middleware('permission:view.buyerbranchs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyerbranchs', ['only' => ['store']]);
        $this->middleware('permission:edit.buyerbranchs',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyerbranchs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $buyerbranchs=array();
        $rows=$this->buyerbranch->where([['buyer_id','=',request('buyer_id',0)]])->get();
        foreach ($rows as $row) {
          $buyerbranch['id']=$row->id;
          //$buyerbranch['name']=$row->name;
          $buyerbranch['contact_person']=$row->contact_person;
          $buyerbranch['buyer']=$buyer[$row->buyer_id];
          $buyerbranch['country']=$country[$row->country_id];
          array_push($buyerbranchs,$buyerbranch);
        }
        echo json_encode($buyerbranchs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.BuyerBranch",['buyer'=>$buyer, 'country'=>$country]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerBranchRequest $request) {
        $buyerbranch = $this->buyerbranch->create($request->except(['id']));
        if ($buyerbranch) {
            return response()->json(array('success' => true, 'id' => $buyerbranch->id, 'message' => 'Save Successfully'), 200);
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
        $buyerbranch = $this->buyerbranch->find($id);
        $row ['fromData'] = $buyerbranch;
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
    public function update(BuyerBranchRequest $request, $id) {
        $buyerbranch = $this->buyerbranch->update($id, $request->except(['id']));
        if ($buyerbranch) {
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
        if ($this->buyerbranch->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
