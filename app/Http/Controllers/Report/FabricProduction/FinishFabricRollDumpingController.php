<?php

namespace App\Http\Controllers\Report\FabricProduction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRollRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;

use Illuminate\Support\Carbon;

class FinishFabricRollDumpingController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	private $autoyarn;
	private $teammember;
    private $team;
    private $gmtspart;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		ProdBatchFinishQcRepository $prodbatchfinishqc,  
        ProdBatchFinishQcRollRepository $prodbatchfinishqcroll, 
        ProdBatchRepository $prodbatch,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ItemAccountRepository $itemaccount
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->prodbatchfinishqc = $prodbatchfinishqc;
        $this->prodbatchfinishqcroll = $prodbatchfinishqcroll;
        $this->prodbatch = $prodbatch;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->itemaccount = $itemaccount;

		//$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$from=request('date_from', 0);
        $to=request('date_to', 0);
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
        return Template::loadView('Report.FabricProduction.FinishFabricRollDumping',['company'=>$company,'buyer'=>$buyer,'from'=>$from,'to'=>$to]);
    }

    public function reportData()
    {
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);

		$producedcompany=null;
		$buyer=null;
		$datefrom=null;
		$dateto=null;

		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and so_dyeings.buyer_id=$buyer_id ";
		}
		if($date_from){
			$datefrom=" and prod_batch_finish_qcs.posting_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and prod_batch_finish_qcs.posting_date<='".$date_to."' ";
		}

		$batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);

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
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

		

		$prodknitqc=$this->prodbatchfinishqc
            ->selectRaw('
                batch_colors.name as batch_color_name,
				prod_batch_finish_qcs.posting_date,
                prod_batch_finish_qc_rolls.id,
                prod_batch_finish_qc_rolls.qty as qc_pass_qty,
                prod_batch_finish_qc_rolls.reject_qty,
                prod_batch_finish_qc_rolls.gsm_weight as qc_gsm_weight,
                prod_batch_finish_qc_rolls.dia_width as qc_dia_width,
                prod_batch_finish_qc_rolls.grade_id,
                prod_batch_rolls.id as prod_batch_roll_id,
                prod_batch_rolls.qty as batch_qty,
				prod_batches.batch_no,
				prod_batches.batch_for,
                so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
                so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
                so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                inv_grey_fab_isu_items.qty as rcv_qty,

                inv_grey_fab_items.autoyarn_id,
                inv_grey_fab_items.gmtspart_id,
                inv_grey_fab_items.fabric_look_id,
                inv_grey_fab_items.fabric_shape_id,
                inv_grey_fab_items.gsm_weight,
                inv_grey_fab_items.dia as dia_width,
                inv_grey_fab_items.measurment as measurement,
                inv_grey_fab_items.roll_length,
                inv_grey_fab_items.stitch_length,
                inv_grey_fab_items.shrink_per,
                inv_grey_fab_items.colorrange_id,
                colorranges.name as colorrange_name,
                inv_grey_fab_items.color_id,
                colors.name as fabric_color,
                inv_grey_fab_items.supplier_id,

                c_colorranges.name as c_colorrange_name,
				so_dyeing_items.gmt_sale_order_no as c_sale_order_no,
                so_dyeing_items.gmt_style_ref as c_style_ref,
                buyers.name as c_buyer_name,
                dyeingcolors.name as c_dyeing_color,
				so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.dia as c_dia_width,
                so_dyeing_items.measurment as c_measurement,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.dyeing_type_id as c_dyeing_type_id,


                inv_grey_fab_rcv_items.inv_grey_fab_item_id,
                inv_grey_fab_rcv_items.store_id,
                prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
                prod_knits.prod_no,

                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
                prod_knit_items.id as prod_knit_item_id,

                suppliers.name as supplier_name,
                asset_quantity_costs.custom_no as machine_no,
                asset_technical_features.dia_width as machine_dia,
                asset_technical_features.gauge as machine_gg,
                gmtssamples.name as gmt_sample,
                
                sales_orders.sale_order_no,
                styles.style_ref,
                buyers. name as buyer_name,
                style_fabrications.dyeing_type_id,
                po_dyeing_service_item_qties.fabric_color_id,
                dyeingcolors.name as dyeing_color,
				so_dyeings.buyer_id,

                CASE 
                WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
                ELSE inhouseprods.customer_name
                END as customer_name

            ')
            ->join('prod_batch_finish_qc_rolls',function($join){
            	$join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
            })
            ->join('prod_batches',function($join){
            	$join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('colors as batch_colors',function($join){
                $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })
            ->join('prod_batch_rolls',function($join){
            	$join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
            ->join('so_dyeing_refs',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
            	$join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
            	$join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
            	$join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
            	$join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
            	$join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
            	$join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
            	$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
            	$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
            	$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
            	$join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('buyers',function($join){
            	$join->on('buyers.id','=','styles.buyer_id');
            })
            ->leftJoin('inv_grey_fab_isu_items',function($join){
                $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
            })
            ->leftJoin('inv_isus',function($join){
                $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
            })
            ->leftJoin('inv_grey_fab_items',function($join){
                $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
            })
            ->leftJoin('inv_grey_fab_rcv_items',function($join){
                $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
            })
            ->leftJoin('inv_grey_fab_rcvs',function($join){
                $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
            })
            ->leftJoin('inv_rcvs',function($join){
                $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
            })
            ->leftJoin('prod_knit_dlvs',function($join){
                $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
            })
            ->leftJoin('prod_knit_dlv_rolls',function($join){
                $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
                $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
            })
            ->leftJoin('prod_knit_qcs',function($join){
                $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
            })
            ->leftJoin('prod_knit_rcv_by_qcs',function($join){
                $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
            })
            ->leftJoin('prod_knit_item_rolls',function($join){
                $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
            })
            ->leftJoin('prod_knit_items',function($join){
                $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
            })
            ->leftJoin ('prod_knits',function($join){
                $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
            })
            ->leftJoin ('suppliers',function($join){
                $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
            })
            ->leftJoin ('colorranges',function($join){
                $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
            }) 
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
            })
            ->leftJoin('asset_quantity_costs',function($join){
                $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
            })
            ->leftJoin('asset_technical_features',function($join){
                $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
            })  
            ->leftJoin('gmtssamples',function($join){
                $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
            })
            ->leftJoin('colors as dyeingcolors',function($join){
                $join->on('dyeingcolors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin(\DB::raw("(
                select 
                pl_knit_items.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                customer.name as customer_name,
                companies.id as company_id

                from pl_knit_items
                join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                left join colorranges on colorranges.id=pl_knit_items.colorrange_id
                join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
                left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
                left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
                and po_knit_service_items.deleted_at is null
                left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
                left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                left join so_knits on so_knits.id=so_knit_refs.so_knit_id
                left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                left join jobs on jobs.id=sales_orders.job_id
                left join styles on styles.id=jobs.style_id
                left join buyers on buyers.id=styles.buyer_id
                left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
                left join buyers customer on customer.id=so_knits.buyer_id
                left join companies  on companies.id=customer.company_id
            ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
            ->leftJoin(\DB::raw("(
                select 
                po_knit_service_item_qties.id,
                buyers.name as buyer_name,
                buyers.id as buyer_id,
                companies.name as customer_name,
                companies.id as company_id   
                from 
                po_knit_service_item_qties
                join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
                join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
                left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
                join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
                join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                
                left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                left join jobs on jobs.id=sales_orders.job_id
                left join styles on styles.id=jobs.style_id
                left join buyers on buyers.id=styles.buyer_id
                left join companies on companies.id=po_knit_services.company_id
                order by po_knit_service_item_qties.id
            ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
            // ->leftJoin('companies',function($join){
            //     $join->on('companies.id','=','outhouseprods.company_id');
            //     $join->Oron('companies.id','=','inhouseprods.company_id');
            // })
			->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_items.so_dyeing_ref_id');
            })
            ->leftJoin ('colorranges as c_colorranges',function($join){
                $join->on('c_colorranges.id', '=', 'so_dyeing_items.colorrange_id');
            }) 
            ->leftJoin ('buyers as c_buyers',function($join){
                $join->on('buyers.id', '=', 'so_dyeing_items.gmt_buyer');
            })
            ->leftJoin('colors as c_dyeingcolors',function($join){
                $join->on('dyeingcolors.id','=','so_dyeing_items.fabric_color_id');
            })
			->when(request('date_from'), function ($q) use($date_from) {
				return $q->where('prod_batch_finish_qcs.posting_date', '>=', $date_from);
			})
			->when(request('date_to'), function ($q) use($date_to)  {
			   return $q->where('prod_batch_finish_qcs.posting_date', '<=', $date_to);
			})
			->when(request('buyer_id'), function ($q) use($buyer_id)  {
			   return $q->where('so_dyeings.buyer_id', '=', $buyer_id);
			})
			->when(request('produced_company_id'), function ($q) use($produced_company_id)  {
			   return $q->where('sales_orders.produced_company_id', '=', $produced_company_id);
			})
            ->orderBy('inv_grey_fab_isu_items.id','desc')
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult,$batchfor,$buyer){//$yarnDtls,
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:$desDropdown[$prodknitqc->c_autoyarn_id];
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:$fabriclooks[$prodknitqc->c_fabric_look_id];
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:$fabricshape[$prodknitqc->c_fabric_shape_id];
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:$gmtspart[$prodknitqc->c_gmtspart_id];
                $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:$dyetype[$prodknitqc->c_dyeing_type_id];
                //$prodknitqc->yarndtl=$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
                $prodknitqc->bal_qty=$prodknitqc->rcv_qty-($prodknitqc->tot_batch_qty-$prodknitqc->batch_qty);
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                $prodknitqc->batch_qty=number_format($prodknitqc->batch_qty,2);
                $prodknitqc->qc_pass_qty=number_format($prodknitqc->qc_pass_qty,2);
                $prodknitqc->reject_qty=number_format($prodknitqc->reject_qty,2);
				$prodknitqc->posting_date=date('Y-m-d',strtotime($prodknitqc->posting_date));
				$prodknitqc->batch_for=$batchfor[$prodknitqc->batch_for];
				$prodknitqc->so_customer_name=$buyer[$prodknitqc->buyer_id]?$buyer[$prodknitqc->buyer_id]:'--';
				$prodknitqc->colorrange_name=$prodknitqc->colorrange_name?$prodknitqc->colorrange_name:$prodknitqc->c_colorrange_name;
				$prodknitqc->sale_order_no=$prodknitqc->sale_order_no?$prodknitqc->sale_order_no:$prodknitqc->c_sale_order_no;
				$prodknitqc->style_ref=$prodknitqc->style_ref?$prodknitqc->style_ref:$prodknitqc->c_style_ref;
				$prodknitqc->buyer_name=$prodknitqc->buyer_name?$prodknitqc->buyer_name:$prodknitqc->c_buyer_name;
				$prodknitqc->dyeing_color=$prodknitqc->dyeing_color?$prodknitqc->dyeing_color:$prodknitqc->c_dyeing_color;
				$prodknitqc->gsm_weight=$prodknitqc->gsm_weight?$prodknitqc->gsm_weight:$prodknitqc->c_gsm_weight;
				$prodknitqc->dia_width=$prodknitqc->dia_width?$prodknitqc->dia_width:$prodknitqc->c_dia_width;
				$prodknitqc->measurement=$prodknitqc->measurement?$prodknitqc->measurement:$prodknitqc->c_measurement;
                return $prodknitqc;
            });
            echo json_encode($prodknitqc);
	}


}
