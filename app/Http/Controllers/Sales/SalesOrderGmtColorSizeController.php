<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderGmtColorSizeRequest;

class SalesOrderGmtColorSizeController extends Controller {

    private $salesordergmtcolorsize;
    private $salesordercountry;
    private $job;
    public function __construct(
        SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
        SalesOrderCountryRepository $salesordercountry,
        JobRepository $job
    ) {
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->salesordercountry = $salesordercountry;
        $this->job = $job;
        $this->middleware('auth');
        $this->middleware('permission:view.salesordergmtcolorsizes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.salesordergmtcolorsizes', ['only' => ['store']]);
        $this->middleware('permission:edit.salesordergmtcolorsizes',   ['only' => ['update']]);
        $this->middleware('permission:delete.salesordergmtcolorsizes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $salesordergmtcolorsizes=array();
        $rows=$this->salesordergmtcolorsize->get();
        foreach($rows as $row){
        $salesordergmtcolorsize['id']=  $row->id;
        $salesordergmtcolorsize['sort']=    $row->sort_id;
        $salesordergmtcolorsize['salesordercountry']=   $salesordercountry[$row->sale_order_country_id];
           array_push($salesordergmtcolorsizes,$salesordergmtcolorsize);
        }
        echo json_encode($salesordergmtcolorsizes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $stylesize=$this->salesordercolor->join('jobs', function($join)  {
        $join->on('sales_order_colors.job_id', '=', 'jobs.id');
        })
        ->rightJoin('style_sizes', function($join)  {
        $join->on('jobs.style_id', '=', 'style_sizes.style_id');

        })
        ->leftJoin('sales_order_sizes', function($join)  {
        $join->on('style_sizes.id', '=', 'sales_order_sizes.style_size_id');
        $join->on('sales_order_colors.id', '=', 'sales_order_sizes.sale_order_color_id');
        })
        ->join('sizes', function($join) {
        $join->on('style_sizes.size_id', '=', 'sizes.id');
        })
        ->orderBy('style_sizes.sort_id')
         ->where('jobs.style_id', '=',request('style_id',0))
        ->get([
        'sales_order_colors.*',
        'style_sizes.id as stylesize',
        'style_sizes.size_code',
        'sizes.name',
        'sales_order_sizes.qty',
        'sales_order_sizes.rate',
        'sales_order_sizes.amount'
        ]);
        return Template::loadView('Sales.SalesOrderSize', ['stylesize'=>$stylesize]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
            $style_gmt_id_arr=[];
            $totalQty=0;
            
            foreach($request->style_size_id as $index=>$style_size_id){
                //if($request->qty[$index]){
                $salesordergmtcolorsize = $this->salesordergmtcolorsize->updateOrCreate(
                ['job_id' => $request->job_id,'sale_order_id' => $request->sale_order_id,'sale_order_country_id' => $request->sale_order_country_id,'style_gmt_color_size_id' => $request->style_gmt_color_size_id[$index],'style_gmt_id' => $request->style_gmt_id[$index],'style_color_id' => $request->style_color_id[$index],'article_no' => $request->article_no[$index],'style_size_id' => $style_size_id],
                ['qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
                );
                $totalQty+=$request->qty[$index];
                $style_gmt_id_arr[$request->style_gmt_id[$index]]=$request->style_gmt_id[$index];
                //}
            }

            $job= $this->job
            ->selectRaw('
                jobs.id as job_id,
                jobs.company_id,
                styles.buyer_id
            ')
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->where([['jobs.id','=',$request->job_id]])
            ->get()
            ->first();
            $v_extra_qty=0;
            $v_qty=0;

            foreach($style_gmt_id_arr as $key=>$value)
            {

                $results = collect(\DB::select('
                select 
                p.job_id,
                p.style_gmt_id,
                p.style_color_id,
                p.style_size_id, 
                p.country_ship_date,
                p.qty, 
                l.gmt_qty_range_start, 
                l.gmt_qty_range_end,
                l.id,
                l.process_loss_per
                from
                (
                select 
                sales_order_gmt_color_sizes.job_id,
                sales_order_gmt_color_sizes.style_gmt_id,
                sales_order_gmt_color_sizes.style_color_id,
                sales_order_gmt_color_sizes.style_size_id,
                sales_order_countries.country_ship_date,
                sum (sales_order_gmt_color_sizes.qty) as qty 
                from sales_order_gmt_color_sizes 
                join sales_order_countries on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id 
                where sales_order_gmt_color_sizes.job_id=?  
                group by 
                sales_order_gmt_color_sizes.job_id,
                sales_order_gmt_color_sizes.style_gmt_id,
                sales_order_gmt_color_sizes.style_color_id,
                sales_order_gmt_color_sizes.style_size_id,
                sales_order_countries.country_ship_date
                ) p
                join
                (
                select 
                gmts_process_losses.id,
                gmts_process_losses.gmt_qty_range_start,
                gmts_process_losses.gmt_qty_range_end,
                sum(gmts_process_loss_pers.process_loss_per) as process_loss_per 
                from 
                gmts_process_losses 
                left join gmts_process_loss_pers on gmts_process_losses.id=gmts_process_loss_pers.gmts_process_loss_id 
                join production_processes on production_processes.id=gmts_process_loss_pers.production_process_id 
                where(
                production_processes.id in (
                select id 
                from production_processes where production_area_id=40
                ) or production_processes.id in (
                select production_process_id 
                from embelishments 
                join style_embelishments on style_embelishments.embelishment_id=embelishments.id 
                where style_embelishments.style_gmt_id=? 
                )
                ) 
                and gmts_process_losses.company_id=? 
                and gmts_process_losses.buyer_id=? 
                group by gmts_process_losses.id,
                gmts_process_losses.gmt_qty_range_start,gmts_process_losses.gmt_qty_range_end 
                ) l
                on p.qty between l.gmt_qty_range_start and l.gmt_qty_range_end  order by l.id', [$job->job_id,$key,$job->company_id,$job->buyer_id]));
                foreach($results as $result){
                    $v_extra_qty=$v_extra_qty+(($result->qty*$result->process_loss_per)/100);
                    $v_qty=$v_qty+$result->qty;
                }
            }



            $v_percent=0;
            if($v_qty)
            {
                $v_percent=$v_extra_qty/$v_qty*100;
            }

           $forupdate= $this->salesordergmtcolorsize
            ->where([['sales_order_gmt_color_sizes.job_id','=',$job->job_id]])
            ->get();
            foreach($forupdate as $row)
            {
                $v_row_extra_qty  =($row->qty*$v_percent)/100;
                $v_plan_cut_qty =$row->qty+$v_row_extra_qty;

                $salesordergmtcolorsize = $this->salesordergmtcolorsize->update($row->id,[
                    'extra_percent'=>$v_percent,
                    'extra_qty'=>$v_row_extra_qty,
                    'plan_cut_qty'=>$v_plan_cut_qty
                ]);
            }
        

            $packingRatio=$this->job
            ->selectRaw('
                style_pkgs.id,
                sum(style_pkg_ratios.qty) as qty
            ')
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('style_pkgs', function($join)  {
                $join->on('style_pkgs.style_id', '=', 'styles.id');
            })
            ->join('style_pkg_ratios', function($join)  {
                $join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
            })
            ->groupBy(['style_pkgs.id'])
            ->where([['jobs.id','=',$request->job_id]])
            ->get();
            if($packingRatio->count()==1){
                $packingData=$packingRatio->first();
                $no_of_carton=$totalQty/$packingData->qty;
                $countryObj=$this->salesordercountry->find($request->sale_order_country_id);
                $countryObj->no_of_carton=$no_of_carton;
                $countryObj->save();

            }

            return response()->json(array('success' => true, 'id' => '', 'message' => 'Save Successfully'), 200);
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
        $salesordergmtcolorsize = $this->salesordergmtcolorsize->find($id);
        $row ['fromData'] = $salesordergmtcolorsize;
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
    public function update(SalesOrderGmtColorSizeRequest $request, $id) {
        $salesordergmtcolorsize = $this->salesordergmtcolorsize->update($id, $request->except(['id']));
        if ($salesordergmtcolorsize) {
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
        if ($this->salesordergmtcolorsize->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
