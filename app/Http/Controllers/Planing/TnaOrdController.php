<?php
namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaOrdRequest;

class TnaOrdController extends Controller {

    private $tnaord;
    private $company;
    private $location;
    private $buyer;

    public function __construct(
        TnaOrdRepository $tnaord,
        CompanyRepository $company,
        LocationRepository $location,
        BuyerRepository $buyer
    ) {
        $this->tnaord = $tnaord;
        $this->company = $company;
        $this->location = $location;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.tnaords',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.tnaords', ['only' => ['store']]);
        $this->middleware('permission:edit.tnaords',   ['only' => ['update']]);
        $this->middleware('permission:delete.tnaords', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      /*$path= public_path('images')."/APR-K.csv";
      $row = 1;
      \DB::beginTransaction();
      if (($handle = fopen($path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
          if($row<=220){
            if($row==1){
            }
            else{
              try
              {
                $this->tnaord->updateOrCreate([
                    'sales_order_id' => $data[0],
                    'tna_task_id' => $data[1],
                ],[
                    'tna_start_date' => $data[2]?date('Y-m-d',strtotime($data[2])):NULL,
                    'tna_end_date' => $data[3]?date('Y-m-d',strtotime($data[3])):NULL,
                ]);
              }
              catch(EXCEPTION $e)
              {
                \DB::rollback();
                throw $e;
              }
            }
          }
          $row++;
        }
        fclose($handle);
      }
      \DB::commit();
      echo $row;

      die;*/
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView("Planing.TnaOrd",['company'=>$company,'location'=>$location,'yesno'=>$yesno,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnaOrdRequest $request) {
        $tnaord = $this->tnaord->create($request->except(['id']));
        if ($tnaord) {
            return response()->json(array('success' => true, 'id' => $tnaord->id, 'message' => 'Save Successfully'), 200);
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
        $tnaord = $this->tnaord->find($id);
        $row ['fromData'] = $tnaord;
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
    public function update(TnaOrdRequest $request, $id) {
        $tnaord = $this->tnaord->update($id, $request->except(['id']));
        if ($tnaord) {
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
        if ($this->tnaord->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
