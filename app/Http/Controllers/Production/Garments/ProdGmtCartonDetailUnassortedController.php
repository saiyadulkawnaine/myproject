<?php
namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailQtyRepository;
use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryQtyRepository;

use App\Http\Requests\StylePkgRequest;
use App\Library\Template;

class ProdGmtCartonDetailUnassortedController extends Controller {
    private $stylepkg;
    private $stylepkgratio;
    private $stylegmtcolorsize;
    private $prodgmtcarton;
    private $prodgmtcartondetail;
    private $salesordercountry;
    private $country;
    private $exfactoryqty;

    public function __construct( 
        StylePkgRepository $stylepkg, 
        StylePkgRatioRepository $stylepkgratio, 
        StyleGmtColorSizeRepository $stylegmtcolorsize, 
        ProdGmtCartonEntryRepository $prodgmtcarton,
        ProdGmtCartonDetailRepository $prodgmtcartondetail,
        SalesOrderCountryRepository $salesordercountry, 
        CountryRepository $country,
        ProdGmtExFactoryQtyRepository $exfactoryqty
        ) {
        $this->stylepkg      = $stylepkg;
        $this->stylepkgratio = $stylepkgratio;
        $this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->prodgmtcarton = $prodgmtcarton;
        $this->prodgmtcartondetail = $prodgmtcartondetail;
        $this->salesordercountry = $salesordercountry;
        $this->country = $country;
        $this->exfactoryqty = $exfactoryqty;
        $this->middleware('auth');
        $this->middleware('permission:view.prodgmtcartondetailunassorteds',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodgmtcartondetailunassorteds', ['only' => ['store']]);
        $this->middleware('permission:edit.prodgmtcartondetailunassorteds',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodgmtcartondetailunassorteds', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows = $this->stylepkg->selectRaw(
        '
        prod_gmt_carton_entries.id,
        style_pkgs.id as style_pkg_id ,
        style_pkgs.style_id,
        style_pkgs.spec,
        style_pkgs.assortment_name,
        style_pkgs.itemclass_id,
        styles.style_ref,
        itemclasses.name,
        sum(style_pkg_ratios.qty) as qty'
        )
        ->leftJoin('style_pkg_ratios', function($join) {
        $join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
        })
        ->join('styles', function($join)  {
        $join->on('styles.id', '=', 'style_pkgs.style_id');
        })
        ->join('itemclasses', function($join)  {
        $join->on('itemclasses.id', '=', 'style_pkgs.itemclass_id');
        })
        ->join('prod_gmt_carton_details', function($join)  {
        $join->on('prod_gmt_carton_details.style_pkg_id', '=', 'style_pkgs.id');
        })
        ->join('prod_gmt_carton_entries', function($join)  {
        $join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
        })
        ->when(request('style_id'), function ($q) {
            return $q->where('style_pkgs.style_id', '=', request('style_id', 0));
        })
        ->when(request('style_gmt_id'), function ($q) {
            return $q->where('style_pkgs.style_gmt_id', '=', request('style_gmt_id', 0));
        })
        ->when(request('assortment_name'), function ($q) {
            return $q->where('style_pkgs.assortment_name', 'like', '%'.request('assortment_name', 0).'%');
        })
        ->when(request('spec'), function ($q) {
            return $q->where('style_pkgs.spec', 'like', '%'.request('spec', 0).'%');
        })
        ->where([['prod_gmt_carton_entries.id','=',request('prod_gmt_carton_entry_id',0)]])
        ->where([['style_pkgs.is_created_by_system','=',1]])
        ->groupBy([
        'prod_gmt_carton_entries.id',
        'style_pkgs.id',
        'style_pkgs.style_id',
        'style_pkgs.spec',
        'style_pkgs.assortment_name',
        'style_pkgs.itemclass_id',
        'styles.style_ref',
        'itemclasses.name'
        ])
        ->orderBy('style_pkgs.id','DESC')
        ->get();


        $stylepkgs=array();
        foreach($rows as $row){
        $stylepkg['id'] = $row->id;
        $stylepkg['style_pkg_id'] = $row->style_pkg_id;
        $stylepkg['spec'] = $row->spec;
        $stylepkg['style'] =    $row->style_ref;
        $stylepkg['style_id'] = $row->style_id;
        $stylepkg['itemclass'] =    $row->name;
        $stylepkg['assortment_name'] =  $row->assortment_name;
        $stylepkg['qty'] =  $row->qty;
        array_push($stylepkgs,$stylepkg);
        }
        echo json_encode($stylepkgs);
         
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $style_pkg_id=request('style_pkg_id',0);
        $salesordercountry=$this->salesordercountry
       ->selectRaw('
        prod_gmt_carton_details.id,
        prod_gmt_carton_details.style_pkg_id,
        sales_order_countries.id as sales_order_country_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        styles.id as style_id,
        jobs.job_no,
        buyers.code as buyer_name,
        countries.name as country_id,
        sum(sales_order_gmt_color_sizes.qty) as order_qty,
        avg(sales_order_gmt_color_sizes.rate) as order_rate,
        sum(sales_order_gmt_color_sizes.amount) as order_amount
        ')
       ->join('prod_gmt_carton_details', function($join)  {
        $join->on('prod_gmt_carton_details.sales_order_country_id', '=', 'sales_order_countries.id');
        })
        ->join('prod_gmt_carton_entries', function($join)  {
        $join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
        })

       ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
       ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
       ->join('sales_order_gmt_color_sizes', function($join)  {
        $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->join('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
        })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
        })
        ->where([['prod_gmt_carton_entries.id','=',request('prod_gmt_carton_entry_id',0)]])
        ->where([['prod_gmt_carton_details.style_pkg_id','=',$style_pkg_id]])
        ->groupBy([
        'prod_gmt_carton_details.id',
        'prod_gmt_carton_details.style_pkg_id',
        'sales_order_countries.id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'styles.id',
        'jobs.job_no',
        'buyers.code',
        'countries.name'
       ])
       ->get()
       ->map(function ($salesordercountry){
         return $salesordercountry;
        })->first();
       

        $colorsizes=$this->stylegmtcolorsize
        ->join('style_pkgs', function($join){
            $join->on('style_pkgs.style_id', '=', 'style_gmt_color_sizes.style_id');
        })
        ->join('style_gmts', function($join) {
        $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
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
        ->join('sizes', function($join) {
        $join->on('style_sizes.size_id', '=', 'sizes.id');
        })
        ->leftJoin('style_pkg_ratios',function($join){
            $join->on('style_pkg_ratios.style_pkg_id','=','style_pkgs.id');
            $join->on('style_pkg_ratios.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
            $join->whereNull('style_pkg_ratios.deleted_at');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
         ->where('style_pkgs.id', '=', $style_pkg_id)
        ->get([
        'style_gmt_color_sizes.id as style_gmt_color_size_id',
        'style_gmt_color_sizes.style_gmt_id',
        'style_colors.id as style_color_id',
        'style_colors.sort_id as color_sort_id',
        'colors.name as color_name',
        'colors.code as color_code',
        'style_sizes.id as style_size_id',
        'style_sizes.sort_id',
        'sizes.name',
        'sizes.code',
        'item_accounts.item_description',
        'style_pkg_ratios.qty'
        ]);
        $row ['fromData'] = $salesordercountry;
        $dropdown['prodgmtcartonunassortedpkgcs'] = "'".Template::loadView('Marketing.GmtColorSizeMatrix',['colorsizes'=>$colorsizes])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StylePkgRequest $request) {

        if(!$request->ctqty)
        {
        return response()->json(array('success' => false,'id' =>'','message' => 'Please Insert Curr. Carton Qty'),200);
        }
        if(!$request->style_id)
        {
        return response()->json(array('success' => false,'id' =>'','message' => 'Please Insert Style ID'),200);
        }
        $totVal=0;
        foreach($request->qty as $index=>$val){
        if($val){
        $totVal+=  $val;
        }
        }
        if(!$totVal)
        {
        return response()->json(array('success' => false,'id' =>'','message' => 'Please Insert ratio data '),200);
        }
        \DB::beginTransaction();
        try
            {
                $stylepkg = $this->stylepkg->create(['style_id'=>$request->style_id,'itemclass_id'=>$request->itemclass_id,'assortment'=>5,'assortment_name'=>'Un-Assorted','is_created_by_system'=>1]);

                foreach($request->qty as $index=>$val){
                    if($val >= 0){

                        $stylepkgratio = $this->stylepkgratio->updateOrCreate(
                        ['style_id' => $request->style_id, 'style_pkg_id' => $stylepkg->id, 'style_gmt_id' =>  $request->style_gmt_id[$index], 'style_color_id' => $request->style_color_id[$index],'style_size_id' => $request->style_size_id[$index],'style_gmt_color_size_id' => $request->style_gmt_color_size_id[$index]],
                        ['qty' => $val]
                        );
                    }
                }

                for($i=1;$i<=$request->ctqty;$i++)
                {
                  $prodgmtcartondetail=$this->prodgmtcartondetail->create(['prod_gmt_carton_entry_id'=>$request->prod_gmt_carton_entry_id,'sales_order_country_id'=>$request->sales_order_country_id,'style_pkg_id'=>$stylepkg->id,'qty'=>$i]);
                }
            }
            catch(EXCEPTION $e)
            {
                \DB::rollback();
                throw $e;
            }
            \DB::commit();

        if($stylepkg){
            return response()->json(array('success' => true,'id' =>'','message' => 'Save Successfully'),200);
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
        $colorsizes=$this->stylegmtcolorsize
        ->join('style_pkgs', function($join){
            $join->on('style_pkgs.style_id', '=', 'style_gmt_color_sizes.style_id');
        })
        ->join('style_gmts', function($join) {
        $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
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
        ->join('sizes', function($join) {
        $join->on('style_sizes.size_id', '=', 'sizes.id');
        })
        ->leftJoin('style_pkg_ratios',function($join){
            $join->on('style_pkg_ratios.style_pkg_id','=','style_pkgs.id');
            $join->on('style_pkg_ratios.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
            $join->whereNull('style_pkg_ratios.deleted_at');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
         ->where('style_pkgs.id', '=', $id)
        ->get([
        'style_gmt_color_sizes.id as style_gmt_color_size_id',
        'style_gmt_color_sizes.style_gmt_id',
        'style_colors.id as style_color_id',
        'style_colors.sort_id as color_sort_id',
        'colors.name as color_name',
        'colors.code as color_code',
        'style_sizes.id as style_size_id',
        'style_sizes.sort_id',
        'sizes.name',
        'sizes.code',
        'item_accounts.item_description',
        'style_pkg_ratios.qty'
        ]);

        $row ['fromData'] = $stylepkgs;
        $dropdown['pkgcs'] = "'".Template::loadView('Marketing.GmtColorSizeMatrix',['colorsizes'=>$colorsizes])."'";
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
    public function update(Request $request, $id) {

        if(!$request->style_pkg_id)
        {
        return response()->json(array('success' => false,'id' =>'','message' => 'Please Insert Style Pakging'),200);
        }
        if(!$request->style_id)
        {
        return response()->json(array('success' => false,'id' =>'','message' => 'Please Insert Style ID'),200);
        }
        $totVal=0;
        foreach($request->qty as $index=>$val){
        if($val){
        $totVal+=  $val;
        }
        }
        if(!$totVal)
        {
        return response()->json(array('success' => false,'id' =>'','message' => 'Please Insert ratio data '),200);
        }

        $prod_gmt_ex_factory_qties=$this->exfactoryqty
      ->join('prod_gmt_carton_details',function($join){
            $join->on('prod_gmt_carton_details.id', '=' , 'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id');
        })
      ->join('style_pkgs',function($join){
            $join->on('style_pkgs.id', '=' , 'prod_gmt_carton_details.style_pkg_id');
        })
      ->where([['style_pkgs.id','=',$request->style_pkg_id]])
      ->get(['prod_gmt_ex_factory_qties.id'])
      ->count();
      if($prod_gmt_ex_factory_qties)
      {
        return response()->json(array('success' => false, 'id' => '', 'message' => 'Ex-Factory Found, So Update Not Possible '), 200);
      }

        foreach($request->qty as $index=>$val){
            if($val >= 0){
                $stylepkgratio = $this->stylepkgratio->updateOrCreate(
                ['style_id' => $request->style_id, 'style_pkg_id' => $request->style_pkg_id, 'style_gmt_id' =>  $request->style_gmt_id[$index], 'style_color_id' => $request->style_color_id[$index],'style_size_id' => $request->style_size_id[$index],'style_gmt_color_size_id' => $request->style_gmt_color_size_id[$index]],
                ['qty' => $val]
                );
            }
        }

        if($stylepkgratio){
            return response()->json(array('success' => true,'id' =>'','message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

    public function getpkgratio()
    {
        $colorsizes=$this->stylegmtcolorsize
        ->join('style_gmts', function($join) {
        $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
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
        ->join('sizes', function($join) {
        $join->on('style_sizes.size_id', '=', 'sizes.id');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
         ->where('style_gmt_color_sizes.style_id', '=', request('style_id',0))
        ->get([
            'style_gmt_color_sizes.id as style_gmt_color_size_id',
            'style_gmt_color_sizes.style_gmt_id',
            'style_colors.id as style_color_id',
            'style_colors.sort_id as color_sort_id',
            'colors.name as color_name',
            'colors.code as color_code',
            'style_sizes.id as style_size_id',
            'style_sizes.sort_id',
            'sizes.name',
            'sizes.code',
            'item_accounts.item_description'
        ]);
        return Template::loadView('Marketing.GmtColorSizeMatrix',['colorsizes'=>$colorsizes]);
    }
}