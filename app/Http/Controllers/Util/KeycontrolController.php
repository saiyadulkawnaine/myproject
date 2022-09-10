<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\KeycontrolRequest;

class KeycontrolController extends Controller
{
    private $keycontrol;
    private $company;
    private $currency;
	private $location;

    public function __construct(KeycontrolRepository $keycontrol,CompanyRepository $company,CurrencyRepository $currency,LocationRepository $location) {
        $this->keycontrol = $keycontrol;
        $this->company = $company;
        $this->currency = $currency;
        $this->location = $location;
        $this->middleware('auth');
        $this->middleware('permission:view.keycontrols',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.keycontrols', ['only' => ['store']]);
        $this->middleware('permission:edit.keycontrols',   ['only' => ['update']]);
        $this->middleware('permission:delete.keycontrols', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $keycontrols=array();
        $rows=$this->keycontrol->get();
        foreach ($rows as $row){
            $keycontrol['id']=$row->id;
            $keycontrol['workinghour']=$row->working_hour;
            $keycontrol['company']=$company[$row->company_id];
            $keycontrol['currency']=$currency[$row->currency_id];
			$keycontrol['location']=$location[$row->location_id];

            array_push($keycontrols,$keycontrol);
        }
        echo json_encode($keycontrols);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      	$keycontrolparameter=array_prepend(config('bprs.keycontrolparameter'),'-Select-','');
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');

        return Template::loadView("Util.Keycontrol",['company'=>$company,'currency'=>$currency,'location'=>$location,'keycontrolparameter'=>$keycontrolparameter]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KeycontrolRequest $request) {
        $keycontrol= $this->keycontrol->create($request->except(['id']));
        if ($keycontrol) {
            return response()->json(array('success' => true, 'id' => $keycontrol->id, 'message' => 'Save Successfully'), 200);
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
        $keycontrol = $this->keycontrol->find($id);
        $row ['fromData'] = $keycontrol;
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
    public function update(KeycontrolRequest $request, $id) {
        $keycontrol = $this->keycontrol->update($id, $request->except(['id']));
        if ($keycontrol) {
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
        if ($this->keycontrol->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
