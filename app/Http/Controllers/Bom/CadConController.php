<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\CadConRepository;
use App\Repositories\Contracts\Bom\CadRepository;
use App\Repositories\Contracts\Marketing\StyleFabricationRepository;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Library\Template;
use App\Http\Requests\Bom\CadConRequest;

class CadConController extends Controller {

    private $cadcon;
    private $cad;
    private $stylefabrication;
    private $stylesize;

    public function __construct(CadConRepository $cadcon,CadRepository $cad,StyleFabricationRepository $stylefabrication,StyleSizeRepository $stylesize) {
        $this->cadcon = $cadcon;
        $this->cad = $cad;
        $this->stylefabrication = $stylefabrication;
        $this->stylesize = $stylesize;
        $this->middleware('auth');
        $this->middleware('permission:view.cadcons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cadcons', ['only' => ['store']]);
        $this->middleware('permission:edit.cadcons',   ['only' => ['update']]);
        $this->middleware('permission:delete.cadcons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $cad=array_prepend(array_pluck($this->cad->get(),'name','id'),'-Select-','');
      $stylefabrication=array_prepend(array_pluck($this->stylefabrication->get(),'name','id'),'-Select-','');
      $stylesize=array_prepend(array_pluck($this->stylesize->get(),'name','id'),'-Select-','');
        $cadcons=array();
		    $rows=$this->cadcon->get();
    		foreach($rows as $row){
          $cadcon['id']=	$row->id;
          $cadcon['cons']=	$row->cons;
          $cadcon['cad']=	$cad[$row->cad_id];
          $cadcon['stylefabrication']=	$stylefabrication[$row->style_fabriction_id];
          $cadcon['resource']=	$stylesize[$row->style_size_id];
    		   array_push($cadcons,$cadcon);
    		}
        echo json_encode($cadcons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $cad=array_prepend(array_pluck($this->cad->get(),'name','id'),'-Select-','');
      $stylefabrication=array_prepend(array_pluck($this->stylefabrication->get(),'name','id'),'-Select-','');
      $stylesize=array_prepend(array_pluck($this->stylesize->get(),'name','id'),'-Select-','');
        return Template::loadView("Bom.CadCon", ['cad'=> $cad,'stylefabrication'=>$stylefabrication,'stylesize'=>$stylesize]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CadConRequest $request) {
      foreach($request->style_gmt_color_size_id as $index=>$style_gmt_color_size_id){
				if($request->cons[$index]){
				$cadcon = $this->cadcon->updateOrCreate(
				['cad_id' => $request->cad_id[$index],'style_fabrication_id' => $request->style_fabrication_id[$index],'style_gmt_color_size_id' => $style_gmt_color_size_id],
				['cons' => $request->cons[$index],'dia' => $request->dia[$index],'style_color_id' => $request->style_color_id[$index],'style_size_id' => $request->style_size_id[$index]]
				);
				}
			}
      return response()->json(array('success' => true,  'message' => 'Save Successfully'), 200);
      //  $cadcon = $this->cadcon->create($request->except(['id']));
        //if ($cadcon) {
            //return response()->json(array('success' => true, 'id' => $cadcon->id, 'message' => 'Save Successfully'), 200);
        //}
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
        $cadcon = $this->cadcon->find($id);
        $row ['fromData'] = $cadcon;
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
    public function update(CadConRequest $request, $id) {
        $cadcon = $this->cadcon->update($id, $request->except(['id']));
        if ($cadcon) {
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
        if ($this->cadcon->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
