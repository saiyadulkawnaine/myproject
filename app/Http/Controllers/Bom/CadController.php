<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\CadRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Library\Template;
use App\Http\Requests\Bom\CadRequest;

class CadController extends Controller
{
    private $cad;
    private $style;
    private $buyer;
	private $autoyarn;

    public function __construct(CadRepository $cad,StyleRepository $style,BuyerRepository $buyer,AutoyarnRepository $autoyarn) {
        $this->cad = $cad;
        $this->style = $style;
        $this->buyer = $buyer;
		    $this->autoyarn = $autoyarn;

        $this->middleware('auth');
        $this->middleware('permission:view.cads',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cads', ['only' => ['store']]);
        $this->middleware('permission:edit.cads',   ['only' => ['update']]);
        $this->middleware('permission:delete.cads', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cads=array();
        $rows=$this->cad
        ->join('styles',function($join){
          $join->on('styles.id','=','cads.style_id');
        })
        ->join('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','cads.created_by');
        })
		    ->orderBy('cads.id','desc')
        ->get([
          'cads.*',
          'styles.style_ref',
          'buyers.name',
          'users.name as created_by'
        ]);
        foreach ($rows as $row){
            $cad['id']=$row->id;
            $cad['created_by']=$row->created_by;
            $cad['style']=$row->style_ref;
            $cad['buyer']=$row->name;
            $cad['cad_date']=$row->cad_date;
            array_push($cads,$cad);
        }
        echo json_encode($cads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');

        return Template::loadView("Bom.Cad",['style'=>$style,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CadRequest $request) {
        $cad= $this->cad->create($request->except(['id','style_ref']));
        if ($cad) {
            return response()->json(array('success' => true, 'id' => $cad->id, 'message' => 'Save Successfully'), 200);
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
        //$cad = $this->cad->find($id);

        $autoyarn=$this->autoyarn->join('autoyarnratios', function($join) {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join) {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
        }


        $cad=$this->cad
        ->join('styles',function($join){
        $join->on('styles.id','=','cads.style_id');
        })
        ->join('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->where([['cads.id','=',$id]])
        ->get([
        'cads.*',
        'styles.style_ref',
        'buyers.id as buyer_id'
        ])->first();

        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabric=$this->cad
        ->join('styles',function($join){
        $join->on('styles.id','=','cads.style_id');
        })
        ->join('style_gmt_color_sizes',function($join){
        $join->on('style_gmt_color_sizes.style_id','=','styles.id');
        })
        ->join('style_fabrications',function($join){
        $join->on('style_fabrications.style_gmt_id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->join('style_gmts', function($join) {
        $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->join('style_colors', function($join) {
        $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
        })
        ->join('colors', function($join) {
        $join->on('style_colors.color_id', '=', 'colors.id');
        })
        ->join('style_sizes', function($join) {
        $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
        $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->leftJoin('cad_cons',function($join){
        $join->on('cad_cons.cad_id','=','cads.id');
        $join->on('cad_cons.style_fabrication_id','=','style_fabrications.id');
        $join->on('cad_cons.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
        })
        ->where([['cads.id','=',$cad->id]])
        ->orderBy('style_fabrications.id')
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->get([
        'cads.id as cad_id',
        'style_fabrications.*',
        'item_accounts.item_description',
        'gmtsparts.name as gmtspart_name',
        'uoms.code as uom_name',
        'style_sizes.id as style_size_id',
        'sizes.name',
        'style_colors.id as style_color_id',
        'style_gmt_color_sizes.id as style_gmt_color_size_id',
        'colors.name as color_name',
        'cad_cons.id as cad_con_id',
        'cad_cons.cons',
        'cad_cons.dia',
        ]);
        $stylefabrications=array();
        foreach($fabric as $row){
        $stylefabrication['id']=	$row->cad_con_id;
        $stylefabrication['cad_id']=	$row->cad_id;
        $stylefabrication['style_fabrication_id']=	$row->id;
        $stylefabrication['style_gmt_color_size_id']= $row->style_gmt_color_size_id;
        $stylefabrication['style_color_id']= $row->style_color_id;
        $stylefabrication['color_name']=  $row->color_name;
        $stylefabrication['style_size_id']=	$row->style_size_id;
        $stylefabrication['name']=  $row->name;
        $stylefabrication['style']=	$row->style_ref;
        $stylefabrication['stylegmts']=	$row->item_description;
        $stylefabrication['gmtspart']=	$row->gmtspart_name;
        $stylefabrication['gmtspart_id']=  $row->gmtspart_id;
        $stylefabrication['fabrication']=	$desDropdown[$row->autoyarn_id];
        $stylefabrication['uom_name']=	$row->uom_name;
        $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
        $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
        $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
        $stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
        $stylefabrication['cons']=	$row->cons;
        $stylefabrication['dia']=	$row->dia;
        array_push($stylefabrications,$stylefabrication);
        }

        $row ['fromData'] = $cad;
        $dropdown['cadconmatrix'] = "'".Template::loadView('Bom.CadConMatrix',['stylefabrications'=>$stylefabrications])."'";
        $row ['dropDown'] = $dropdown;
        //$row ['extra'] = $stylefabrications;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CadRequest $request, $id) {
        $cad = $this->cad->update($id, $request->except(['id','style_ref','style_id']));
        if ($cad) {
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
        if ($this->cad->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
