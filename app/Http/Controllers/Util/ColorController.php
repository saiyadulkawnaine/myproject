<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\ColorRequest;

class ColorController extends Controller {

    private $color;
    private $buyer;

    public function __construct(ColorRepository $color, BuyerRepository $buyer) {
        $this->color = $color;
        $this->buyer = $buyer;
        $this->middleware('auth');
        $this->middleware('permission:view.colors',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.colors', ['only' => ['store']]);
        $this->middleware('permission:edit.colors',   ['only' => ['update']]);
        $this->middleware('permission:delete.colors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /*$path= public_path('images')."\color.csv";
       // echo $path; die;
        $row = 1;
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if($row<=429){
                    if($row==1){
                    }
                    else{
                        $wh=explode("*",$data[5]);
                        $affected = \DB::table('BUDGET_FABRIC_PROD_CONS')
                        ->whereIn('fabric_color_id', $wh)
                        ->update(['fabric_color_id' => $data[4]]);
                    }
                }
                $row++;
            }
            fclose($handle);
        }
        echo $row;
        die;*/



        $colors=array();
        $rows=$this->color->orderBy('id','desc')->get();
        foreach($rows as $row){
          $color['id']=$row->id;
          $color['name']=$row->name;
          $color['code']=$row->code;
          array_push($colors,$color);
        }
        echo json_encode($colors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
        $permited=[];
        return Template::loadView("Util.Color",['buyer'=>$buyer,'permited'=>$permited]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ColorRequest $request) {
        $color = $this->color->create($request->except(['id']));
        if ($color) {
            return response()->json(array('success' => true, 'id' => $color->id, 'message' => 'Save Successfully'), 200);
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
        $color = $this->color->find($id);
        $row ['fromData'] = $color;
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
    public function update(ColorRequest $request, $id) {
        $res = $this->color->update($id, $request->except(['id']));
        if ($res) {
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
        if ($this->color->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getcolor(Request $request) {
		return $this->color->where([['name', 'LIKE', '%'.$request->q.'%']])->orderBy('name','asc')->get();
	}

}
