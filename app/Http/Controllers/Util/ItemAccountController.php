<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\YarntypeRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Http\Requests\ItemAccountRequest;


class ItemAccountController extends Controller {

    private $itemaccount;
    private $itemcategory;
    private $itemclass;
    private $yarncount;
    private $yarntype;
    private $composition;
    private $color;
    private $size;
    private $uom;
    private $supplier;
    private $currency;
    private $country;


    public function __construct(ItemAccountRepository $itemaccount,ItemcategoryRepository $itemcategory,ItemclassRepository $itemclass,YarncountRepository $yarncount,YarntypeRepository $yarntype,CompositionRepository $composition,ColorRepository $color,SizeRepository $size,UomRepository $uom,SupplierRepository $supplier,CurrencyRepository $currency,CountryRepository $country) {
      $this->itemaccount  = $itemaccount;
      $this->itemcategory = $itemcategory;
      $this->itemclass    = $itemclass;
      $this->yarncount    = $yarncount;
      $this->yarntype     = $yarntype;
      $this->composition  = $composition;
      $this->color        = $color;
      $this->size         = $size;
      $this->uom          = $uom;
      $this->supplier     = $supplier;
      $this->currency 	  = $currency;
      $this->country      = $country;

      $this->middleware('auth');
      $this->middleware('permission:view.itemaccounts',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.itemaccounts', ['only' => ['store']]);
      $this->middleware('permission:edit.itemaccounts',   ['only' => ['update']]);
      $this->middleware('permission:delete.itemaccounts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
      $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-','');
      $status=array_prepend(config('bprs.status'),'-Select-',''); 
      $consumptionlevel=array_prepend(config('bprs.consumptionlevel'),'-Select-','');
      $itemaccounts=array();
      $rows=$this->itemaccount
      ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
      })
      ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
      })
      ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_accounts.composition_id');
      })
      ->leftJoin('colors',function($join){
        $join->on('colors.id','=','item_accounts.color_id');
      })
      ->leftJoin('sizes',function($join){
        $join->on('sizes.id','=','item_accounts.size_id');
      })
      ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->when(request('id'), function ($q) {
      return $q->where('item_accounts.id', '=', request('id', 0));
      })
      ->when(request('itemcategory_id'), function ($q) {
        return $q->where('item_accounts.itemcategory_id', '=', request('itemcategory_id', 0));
      })
      ->when(request('itemclass_id'), function ($q) {
        return $q->where('item_accounts.itemclass_id', '=', request('itemclass_id', 0));
      })
      ->when(request('item_nature_id'), function ($q) {
        return $q->where('item_accounts.item_nature_id', '=', request('item_nature_id', 0));
      })
      ->when(request('sub_class_name'), function ($q) {
        return $q->where('item_accounts.sub_class_name', 'LIKE', '%'.request('sub_class_name', 0).'%');
      })
      ->when(request('yarncount_id'), function ($q) {
        return $q->where('item_accounts.yarncount_id', '=', request('yarncount_id', 0));
      })
      ->orderBy('item_accounts.id','desc')
      ->get([
      'item_accounts.*',
      'itemcategories.name',
      'itemclasses.name as class_name',
      'yarncounts.count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'compositions.name as composition',
      'colors.name as color',
      'sizes.name as size',
      'uoms.code as uom',
      ]);
      foreach ($rows as $row) {
        $itemaccount['id']=$row->id;
        $itemaccount['itemcategory']=$row->name;
        $itemaccount['itemclass']=$row->class_name;
        $itemaccount['item_nature_id']=$itemnature[$row->item_nature_id];
        $itemaccount['count_name']=$row->count."/".$row->symbol;
        $itemaccount['yarn_type']=$row->yarn_type;
        $itemaccount['sub_class_name']=$row->sub_class_name;
        $itemaccount['sub_class_code']=$row->sub_class_code;
        $itemaccount['composition']=$row->composition;
        $itemaccount['item_description']=$row->item_description;
        $itemaccount['specification']=$row->specification;
        $itemaccount['color']=$row->color;
        $itemaccount['size']=$row->size;
        $itemaccount['gmt_position']=$row->gmt_position;
        $itemaccount['gmt_category']=$gmtcategory[$row->gmt_category];
        $itemaccount['reorder_level']=$row->reorder_level;
        $itemaccount['uom']=$row->uom;
        $itemaccount['min_level']=$row->min_level;
        $itemaccount['max_level']=$row->max_level;
        $itemaccount['custom_code']=$row->custom_code;
        $itemaccount['status_id']=$status[$row->status_id];
        $itemaccount['consumption_level_id']=$consumptionlevel[$row->consumption_level_id];
        array_push($itemaccounts,$itemaccount);
      }
      echo json_encode($itemaccounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $itemcategory=array_prepend(array_pluck($this->itemcategory->orderBy('name','asc')->get(),'name','id'),'-Select-','');
      //$itemclass=array_prepend(array_pluck($this->itemclass->orderBy('name','asc')->get(),'name','id'),'-Select-','');
      $concatitem=$this->itemclass
      ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','itemclasses.itemcategory_id');
      })
      ->orderBy('itemclasses.name','asc')
      ->get([
          'itemclasses.name as itemclass_name',
        'itemclasses.id',
        'itemcategories.name as itemcategory_name'
      ])
      ->map(function($concatitem){
        $concatitem->name=$concatitem->itemclass_name.' ( '.$concatitem->itemcategory_name.')';
        return $concatitem;
      });
      $itemclass=array_prepend(array_pluck($concatitem,'name','id'),'-Select-',0);
      
      $yarncount=array_prepend(array_pluck($this->yarncount->get(),'count','id'),'-Select-','');
      $yarntype=array_prepend(array_pluck($this->yarntype->get(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
	    $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Currency-','');
      $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
      $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-','');
      $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
      $feature=array_prepend(config('bprs.supplyfeature'),'-Select-','');
      $consumptionlevel=array_prepend(config('bprs.consumptionlevel'),'-Select-','');

      return Template::loadView("Util.ItemAccount",['itemcategory'=>$itemcategory,'itemclass'=>$itemclass,'yarncount'=>$yarncount,'yarntype'=>$yarntype,'composition'=>$composition,'color'=>$color,'size'=>$size,'uom'=>$uom,'itemnature'=>$itemnature,'gmtcategory'=>$gmtcategory,'supplier'=>$supplier,'currency'=>$currency,'status'=>$status,'yesno'=>$yesno,'feature'=>$feature,'country'=>$country,'consumptionlevel'=>$consumptionlevel]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemAccountRequest $request) {
        $itemaccount = $this->itemaccount->create($request->except(['id','identity']));
        if ($itemaccount) {
            return response()->json(array('success' => true, 'id' => $itemaccount->id, 'message' => 'Save Successfully'), 200);
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
        $itemaccount = $this->itemaccount
		->join('itemcategories',function($join){
			$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['item_accounts.id','=',$id]])
		->get([
		'item_accounts.*',
		'itemcategories.identity',
		])
		->first();
        $row ['fromData'] = $itemaccount;
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
    public function update(ItemAccountRequest $request, $id) {
        $itemaccount = $this->itemaccount->update($id, $request->except(['id','identity']));
        if ($itemaccount) {
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
        if ($this->itemaccount->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getitemdescription(Request $request) {
		return $this->itemaccount->where([['item_description', 'LIKE', '%'.$request->q.'%']])->get(['item_description as name']);
	}


  public function getItemAccount() 
  {
      $rows=$this->itemaccount
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
      })
      ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
      })
      ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_accounts.composition_id');
      })
      ->leftJoin('colors',function($join){
      $join->on('colors.id','=','item_accounts.color_id');
      })
      ->leftJoin('sizes',function($join){
      $join->on('sizes.id','=','item_accounts.size_id');
      })
      ->leftJoin('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->when(request('id'), function ($q) {
      return $q->where('item_accounts.id', '=', request('id', 0));
      })
      ->orderBy('item_accounts.id','desc')
      ->get([
      'item_accounts.*',
      'itemcategories.name',
      'itemclasses.name as class_name',
      'yarncounts.count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'compositions.name as composition',
      'colors.name as color',
      'sizes.name as size',
      'uoms.code as uom'
      ]);
      echo json_encode($rows);
    }

}
