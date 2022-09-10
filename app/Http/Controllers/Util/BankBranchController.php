<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\BankBranchRequest;

class BankBranchController extends Controller {

    private $bankbranch;
    private $bank;
    private $currency;
    
    

    public function __construct(BankBranchRepository $bankbranch,BankRepository $bank,CurrencyRepository $currency) {
        $this->bankbranch = $bankbranch;
        $this->bank = $bank;
        $this->currency = $currency;
		
        $this->middleware('auth');
       /*  $this->middleware('permission:view.bankbranchs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.bankbranchs', ['only' => ['store']]);
        $this->middleware('permission:edit.bankbranchs',   ['only' => ['update']]);
        $this->middleware('permission:delete.bankbranchs', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {        
        $bankbranchs=array();
        $rows=$this->bankbranch->where([['bank_id','=',request('bank_id',0)]])
        //->orderBy('bank_branches.id','desc')
        ->get();
        foreach ($rows as $row) {
          $bankbranch['id']=$row->id;
          $bankbranch['address']=$row->address;
          $bankbranch['branch_name']=$row->branch_name;
          $bankbranch['contact']=$row->contact;
          array_push($bankbranchs,$bankbranch);
        }
        echo json_encode($bankbranchs);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankBranchRequest $request) {
		$bankbranch = $this->bankbranch->create($request->except(['id']));
        if ($bankbranch) {
            return response()->json(array('success' => true, 'id' => $bankbranch->id, 'message' => 'Save Successfully'), 200);
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
       $bankbranch = $this->bankbranch->find($id);
        $row ['fromData'] = $bankbranch;
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
    public function update(BankBranchRequest $request, $id) {
      $bankbranch = $this->bankbranch->update($id, $request->except(['id']));
        if ($bankbranch) {
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
         if ($this->bankbranch->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
