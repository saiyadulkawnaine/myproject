<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTransLoanRefRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccTransLoanRefRequest;

class AccTransLoanRefController extends Controller {

    private $acctransloanref;

    public function __construct(AccTransLoanRefRepository $acctransloanref) {
        $this->acctransloanref = $acctransloanref;
        $this->middleware('auth');
        //$this->middleware('permission:view.acctransloanrefs',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.acctransloanrefs', ['only' => ['store']]);
        //$this->middleware('permission:edit.acctransloanrefs',   ['only' => ['update']]);
        //$this->middleware('permission:delete.acctransloanrefs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccTransLoanRefRequest $request) {
		
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccTransLoanRefRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

}
