<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTransEmployeeRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccTransEmployeeRequest;

class AccTransEmployeeController extends Controller {

    private $acctransemployee;

    public function __construct(AccTransEmployeeRepository $acctransemployee) {
        $this->acctransemployee = $acctransemployee;

        $this->middleware('auth');
        //$this->middleware('permission:view.acctransemployees',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.acctransemployees', ['only' => ['store']]);
        //$this->middleware('permission:edit.acctransemployees',   ['only' => ['update']]);
        //$this->middleware('permission:delete.acctransemployees', ['only' => ['destroy']]);
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
    public function store(AccTransEmployeeRequest $request) {
		
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
    public function update(AccTransEmployeeRequest $request, $id) {
        
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
