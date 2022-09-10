<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CgroupRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\CompanyRequest;


class CompanyController extends Controller
{

    private $company;
    private $cgroup;
    private $currency;

	public function __construct(
        CompanyRepository $company,
        CgroupRepository $cgroup,
        CurrencyRepository $currency
    )
	{
		$this->company = $company;
		$this->cgroup = $cgroup;
        $this->currency = $currency;
        
		$this->middleware('auth');
		$this->middleware('permission:view.companys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.companys', ['only' => ['store']]);
        $this->middleware('permission:edit.companys',   ['only' => ['update']]);
		$this->middleware('permission:delete.companys', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cgroup=array_prepend(array_pluck($this->cgroup->get(),'name','id'),'-Select-','');
        $companies=array();
        $rows=$this->company->get();
        foreach ($rows as $row) {
          $company['id']=$row->id;
          $company['name']=$row->name;
          $company['code']=$row->code;
          $company['email']=$row->email;
          $company['cgroup']=$cgroup[$row->cgroup_id];
          array_push($companies,$company);
        }
        echo json_encode($companies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$cgroup=array_prepend(array_pluck($this->cgroup->get(),'name','id'),'-Select-','');
		$yesno=array_prepend(config('bprs.yesno'),'-Select-','');
		$companynature=array_prepend(config('bprs.companynature'),'-Select-','');
		$status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-','');
		$legalstatus=array_prepend(config('bprs.legalstatus'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.Company", ['cgroups'=>$cgroup,'yesno'=>$yesno,'companynature'=>$companynature,'legalstatus'=>$legalstatus,'currency'=>$currency,'status'=>$status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $company=$this->company->create($request->except(['id']));
		if($company){
			return response()->json(array('success' => true,'id' =>  $company->id,'message' => 'Save Successfully'),200);
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
        $company = $this->company->find($id);
        
        $company['erc_expiry_date']=($company->erc_expiry_date !== null)?date('Y-m-d',strtotime($company->erc_expiry_date)):null;
        $company['irc_expiry_date']=($company->irc_expiry_date !== null)?date('Y-m-d',strtotime($company->irc_expiry_date)):null;
        $company['trade_lic_renew_date']=($company->trade_lic_renew_date !== null)?date('Y-m-d',strtotime($company->trade_lic_renew_date)):null;
        $company['ban_bank_reg_date']=($company->ban_bank_reg_date !== null)?date('Y-m-d',strtotime($company->ban_bank_reg_date)):null;

        $row ['fromData'] = $company;
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
    public function update(CompanyRequest $request, $id)
    {
        $company=$this->company->update($id,$request->except(['id']));
		if($company){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
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
        if($this->company->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}