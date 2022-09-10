<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTransOtherRefRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccTransOtherRefRequest;

class AccTransOtherRefController extends Controller {

    private $acctransotherref;

    public function __construct(AccTransOtherRefRepository $acctransotherref) {
        $this->acctransotherref = $acctransotherref;
        $this->middleware('auth');
        //$this->middleware('permission:view.acctransotherrefs',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.acctransotherrefs', ['only' => ['store']]);
        //$this->middleware('permission:edit.acctransotherrefs',   ['only' => ['update']]);
        //$this->middleware('permission:delete.acctransotherrefs', ['only' => ['destroy']]);
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
    public function store(AccTransOtherRefRequest $request) {
		
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
    public function update(AccTransOtherRefRequest $request, $id) {
        
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
