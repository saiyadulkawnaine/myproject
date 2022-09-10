<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleFabricationStripeRepository;
use App\Repositories\Contracts\Marketing\StyleFabricationRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\StyleFabricationStripeRequest;

class StyleFabricationStripeController extends Controller {

    private $stylefabricationstripe;
    private $stylefabrication;
    private $color;

    public function __construct(
      StyleFabricationStripeRepository $stylefabricationstripe,
      StyleFabricationRepository $stylefabrication,
      ColorRepository $color
    ) {
        $this->stylefabricationstripe = $stylefabricationstripe;
        $this->stylefabrication = $stylefabrication;
        $this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.stylefabricationstripes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.stylefabricationstripes', ['only' => ['store']]);
        $this->middleware('permission:edit.stylefabricationstripes',   ['only' => ['update']]);
        $this->middleware('permission:delete.stylefabricationstripes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $rows=$this->stylefabricationstripe
        ->join('colors',function($join){
        $join->on('colors.id','=','style_fabrication_stripes.color_id');
        })
        ->join('style_colors',function($join){
        $join->on('style_colors.id','=','style_fabrication_stripes.style_color_id');
        })
        ->join('colors as stylecolors',function($join){
        $join->on('stylecolors.id','=','style_colors.color_id');
        })
        ->join('users as createdbys',function($join){
        $join->on('createdbys.id','=','style_fabrication_stripes.created_by');
        })
        ->join('users as updatedbys',function($join){
        $join->on('updatedbys.id','=','style_fabrication_stripes.updated_by');
        })
        ->where([['style_fabrication_id','=',request('style_fabrication_id', 0)]])
        ->orderBy('style_fabrication_stripes.id','desc')
        ->get([
        'style_fabrication_stripes.*',
        'colors.name',
        'stylecolors.name as style_color',
        'createdbys.name as created_by',
        'updatedbys.name as updated_by',
        ])
        ->map(function($rows) use($yesno){
          $rows->color=$rows->name;
          $rows->style_color=$rows->style_color;
          $rows->measurment=$rows->measurment;
          $rows->dyewash=$rows->is_dye_wash?$yesno[$rows->is_dye_wash]:'';
          $rows->created_by=$rows->created_by;
          //$rows->created_at=$rows->created_at?date('d-M-y h:i:s',strtotime($rows->created_at)):'';
          $rows->updated_by=$rows->updated_by;
          //$rows->updated_at=$rows->updated_at?date('d-M-y h:i:s',strtotime($rows->updated_at)):'';
          return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $stylefabrication=array_prepend(array_pluck($this->stylefabrication->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      return Template::loadView("Marketing.StyleFabricationStripe", [
        'stylefabrication'=> $stylefabrication,
        'color'=>$color
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleFabricationStripeRequest $request) {
        $color = $this->color->firstOrCreate(['name' => $request->color_id]);
        $stylefabricationstripe = $this->stylefabricationstripe->create([
          'style_fabrication_id' => $request->style_fabrication_id,
          'measurment' => $request->measurment,
          'style_color_id' => $request->style_color_id,
          'color_id' => $color->id,
          'feeder' => $request->feeder,
          'is_dye_wash' => $request->is_dye_wash
        ]);
        if ($stylefabricationstripe) {
          return response()->json(array('success' => true, 'id' => $stylefabricationstripe->id, 'message' => 'Save Successfully'), 200);
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
        $stylefabricationstripe=$this->stylefabricationstripe
        ->join('colors',function($join){
        $join->on('colors.id','=','style_fabrication_stripes.color_id');
        })
        ->where([['style_fabrication_stripes.id','=',$id]])

        ->get([
        'style_fabrication_stripes.id',
        'style_fabrication_stripes.style_fabrication_id',
        'style_fabrication_stripes.style_color_id',
        'style_fabrication_stripes.measurment',
        'style_fabrication_stripes.feeder',
        'style_fabrication_stripes.is_dye_wash',
        'colors.name as color_id'
        ])
        ->first();
        $row ['fromData'] = $stylefabricationstripe;
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
    public function update(StyleFabricationStripeRequest $request, $id) {
        $budget=$this->stylefabricationstripe
        ->join('budget_yarn_dyeing_cons',function($join){
          $join->on('budget_yarn_dyeing_cons.style_fabrication_stripe_id','=','style_fabrication_stripes.id');
        })
        ->where([['style_fabrication_stripes.id','=',$id]])
        ->get([
          'style_fabrication_stripes.id'
        ])
        ->first();
        if($budget){
          return response()->json(array('success' => false,'message' => 'Budget Found so update not allowed'),200);
        }

        $color = $this->color->firstOrCreate(['name' => $request->color_id]);
        $stylefabricationstripe = $this->stylefabricationstripe->update($id,['style_fabrication_id' => $request->style_fabrication_id,
        'measurment' => $request->measurment,
        'style_color_id' => $request->style_color_id,
        'color_id' => $color->id,
        'feeder' => $request->feeder,
        'is_dye_wash' => $request->is_dye_wash
        ]);
        if ($stylefabricationstripe) {
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
        $budget=$this->stylefabricationstripe
        ->join('budget_yarn_dyeing_cons',function($join){
        $join->on('budget_yarn_dyeing_cons.style_fabrication_stripe_id','=','style_fabrication_stripes.id');
        })
        ->where([['style_fabrication_stripes.id','=',$id]])
        ->get([
        'style_fabrication_stripes.id'
        ])
        ->first();

        if($budget->id){
        return response()->json(array('success' => false,'message' => 'Budget Found so delete not allowed'),200);
        }

        if ($this->stylefabricationstripe->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
