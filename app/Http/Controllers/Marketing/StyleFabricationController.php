<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleFabricationRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\UomRepository;

use App\Library\Template;
use App\Http\Requests\StyleFabricationRequest;

class StyleFabricationController extends Controller {

    private $stylefabrication;
    private $style;
    private $stylegmts;
    private $gmtspart;
    private $autoyarn;
    private $yarncount;
    private $uom;

    public function __construct(StyleFabricationRepository $stylefabrication,StyleRepository $style,StyleGmtsRepository $stylegmts,GmtspartRepository $gmtspart,AutoyarnRepository $autoyarn, YarncountRepository $yarncount,UomRepository $uom) {
        $this->stylefabrication = $stylefabrication;
        $this->style = $style;
        $this->stylegmts = $stylegmts;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->yarncount = $yarncount;
        $this->uom = $uom;
        $this->middleware('auth');
        $this->middleware('permission:view.stylefabrications',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.stylefabrications', ['only' => ['store']]);
        $this->middleware('permission:edit.stylefabrications',   ['only' => ['update']]);
        $this->middleware('permission:delete.stylefabrications', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$autoyarn=$this->autoyarn->join('autoyarnratios', function($join) use ($request) {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join) use ($request) {
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
        $stylefabrications=array();
		$query = $this->stylefabrication->query();
		$query->join('styles',function($join){
			$join->on('styles.id','=','style_fabrications.style_id');
		});

		$query->join('style_gmts',function($join){
			$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		});
		$query->join('item_accounts',function($join){
			$join->on('item_accounts.id','=','style_gmts.item_account_id');
		});
		$query->join('gmtsparts',function($join){
			$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		});
		$query->join('autoyarns',function($join){
			$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		});
		$query->join('users as createdbys',function($join){
			$join->on('createdbys.id','=','style_fabrications.created_by');
		});
		$query->join('users as updatedbys',function($join){
			$join->on('updatedbys.id','=','style_fabrications.updated_by');
		});
		$query->when(request('style_id'), function ($q) {
		return $q->where('style_fabrications.style_id', '=', request('style_id', 0));
		});
		$query->when(request('style_gmt_id'), function ($q) {
		return $q->where('style_fabrications.style_gmt_id', '=', request('style_gmt_id', 0));
		});
		$rows=$query->get([
		'style_fabrications.*',
		'styles.style_ref',
		'item_accounts.item_description',
		'gmtsparts.name as gmtspart_name',
		'createdbys.name as created_by',
		'updatedbys.name as updated_by',
		]);
    	foreach($rows as $row){
          $stylefabrication['id']=	$row->id;
          $stylefabrication['style']=	$row->style_ref;
		  $stylefabrication['style_gmt_id']=	$row->style_gmt_id;
          $stylefabrication['stylegmts']=	$row->item_description;
          $stylefabrication['gmtspart']=	$row->gmtspart_name;
          $stylefabrication['autoyarn']=	$desDropdown[$row->autoyarn_id];
          $stylefabrication['yarncount']=	$row->count."".$row->symbol;
          $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
          $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
          $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
		  $stylefabrication['fabric_look_id']=	$row->fabric_look_id;
		  $stylefabrication['created_by']=	$row->created_by;
		  $stylefabrication['created_at']=	date('d-M-Y h:i:s',strtotime($row->created_at));
		  $stylefabrication['updated_by']=	$row->updated_by;
		  $stylefabrication['updated_at']=	date('d-M-Y h:i:s',strtotime($row->updated_at));
    	 array_push($stylefabrications,$stylefabrication);
    	}
        echo json_encode($stylefabrications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
	    $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
	  $stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join) use ($request) {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->get([
			'style_gmts.id',
			'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);


      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $autoyarn=array_prepend(array_pluck($this->autoyarn->get(),'name','id'),'-Select-','');
      $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
      $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
	  $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        return Template::loadView('Marketing.StyleFabrication', ['style'=>$style,'stylegmts'=>$stylegmts,'gmtspart'=>$gmtspart,'autoyarn'=>$autoyarn,'yarncount'=>$yarncount,'materialsourcing'=>$materialsourcing,'fabricnature'=>$fabricnature,'fabriclooks'=>$fabriclooks,'yesno'=>$yesno,'fabricshape'=>$fabricshape,'uom'=>$uom]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleFabricationRequest $request) {
        $stylefabrication = $this->stylefabrication->create($request->except(['id','style_ref','fabrication']));
        if ($stylefabrication  ) {
            return response()->json(array('success' => true, 'id' => $stylefabrication->id, 'message' => 'Save Successfully'), 200);
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
       $autoyarn=$this->autoyarn
	   ->join('autoyarnratios', function($join)  {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join)  {
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
		
	   $stylefabrication = $this->stylefabrication
	   ->join('styles', function($join)  {
		$join->on('style_fabrications.style_id', '=', 'styles.id');
		})
		->where('style_fabrications.id','=',$id)
		->get([
			'style_fabrications.*',
			'styles.style_ref',
		])->map(function ($stylefabrication) use($desDropdown) {
			$stylefabrication->fabrication= $desDropdown[$stylefabrication->autoyarn_id];
			return $stylefabrication;
			});
        $row ['fromData'] = $stylefabrication[0];
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
    public function update(StyleFabricationRequest $request, $id) {
    	$budget=$this->stylefabrication
    	->join('budget_fabrics',function($join){
            $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
        })
        ->where([['style_fabrications.id','=',$id]])
        ->get();
        if($budget->first() && \Auth::user()->level() < 5){
        	return response()->json(array('success' => false, 'id' => $id, 'message' => 'Update Not Posible. Budget Found'), 200);
        }

        $stylefabrication = $this->stylefabrication->update($id, $request->except(['id','style_ref','fabrication']));
        if ($stylefabrication) {
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
        if ($this->stylefabrication->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getFabric(){
		$autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join)  {
		$join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->when(request('construction_name'), function ($q) {
			return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
		})
		->when(request('composition_name'), function ($q) {
			return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
		})
		->orderBy('autoyarns.id','desc')
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
        $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
      }
	  
	  $fab=array();
	  $fabs=array();
      foreach($autoyarn as $row){
        $fab[$row->id]['id']=$row->id;
		$fab[$row->id]['name']=$row->name;
		$fab[$row->id]['composition_name']=$desDropdown[$row->id];
      }
	  foreach($fab as $row){
        
		array_push($fabs,$row);
      }
	  echo json_encode($fabs);
	  
	}

}
