<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SewingCapacityRepository;
use App\Repositories\Contracts\Util\SewingCapacityDateRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\SewingCapacityRequest;

class SewingCapacityController extends Controller {

    private $sewingcapacity;
    private $sewingcapacitydate;
    private $company;
    private $location;

    public function __construct(
        SewingCapacityRepository $sewingcapacity, 
        SewingCapacityDateRepository $sewingcapacitydate, 
        CompanyRepository $company, 
        LocationRepository $location 
    ) {
        $this->sewingcapacity = $sewingcapacity;
        $this->sewingcapacitydate = $sewingcapacitydate;
        $this->company = $company;
        $this->location = $location;

        $this->middleware('auth');
        $this->middleware('permission:view.sewingcapacitys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.sewingcapacitys', ['only' => ['store']]);
        $this->middleware('permission:edit.sewingcapacitys',   ['only' => ['update']]);
        $this->middleware('permission:delete.sewingcapacitys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        /*$array = array(); 


        $period = new \DatePeriod(
        new \DateTime('2020-01-01'),
        new \DateInterval('P1D'),
        new \DateTime('2021-01-01')
        );
        foreach ($period as $key => $value) {
        $Store = $value->format('Y-m-d');    
        $array[]= $Store;  
        }
        print_r($array);
        die;*/



        $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $rows=$this->sewingcapacity
        ->join('companies',function($join){
        $join->on('companies.id','=','sewing_capacities.company_id');
        })
        ->join('locations',function($join){
        $join->on('locations.id','=','sewing_capacities.location_id');
        })
        ->get([
        'sewing_capacities.*',
        'companies.code as company_code',
        'locations.name as location_name'
        ])
        ->map(function($rows) use($productionsource){
        $rows->prodsource=$rows->prod_source_id?$productionsource[$rows->prod_source_id]:'';
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
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $year=array_prepend(config('bprs.years'),'-Select-','');
        return Template::loadView("Util.SewingCapacity",[
            'company'=>$company,
            'location'=>$location,
            'productionsource'=>$productionsource,
            'year'=>$year
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SewingCapacityRequest $request) {
        \DB::beginTransaction();
        $sewingcapacity = $this->sewingcapacity->create($request->except(['id']));
        $year=$sewingcapacity->year;
        $nextyear= $year+1;
        $fromday=$year.'-01-01';
        $today=$nextyear.'-01-01';

        $period = new \DatePeriod(
        new \DateTime($fromday),
        new \DateInterval('P1D'),
        new \DateTime($today)
        );
        try
        {
            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');  
                $day = $value->format('l'); 
                $day_status=1;
                if($day=='Friday') {
                  $day_status=2;  
                }
                $this->sewingcapacitydate->create([
                    'sewing_capacity_id'=>$sewingcapacity->id,
                    'capacity_date'=>$date,
                    'day_name'=>$day,
                    'day_status'=>$day_status,
                    'resource_qty'=>0,
                    'mkt_cap_mint'=>0,
                    'mkt_cap_pcs'=>0,
                    'prod_cap_mint'=>0,
                    'prod_cap_pcs'=>0,
                ]);  
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        
        if ($sewingcapacity) {
            return response()->json(array('success' => true, 'id' => $sewingcapacity->id, 'message' => 'Save Successfully'), 200);
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
        $sewingcapacity = $this->sewingcapacity->find($id);
        $row ['fromData'] = $sewingcapacity;
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
    public function update(SewingCapacityRequest $request, $id) {
        $sewingcapacity = $this->sewingcapacity->update($id, $request->except(['id','year']));
        if ($sewingcapacity) {
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
        if ($this->sewingcapacity->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getPdf(){
        $id=request('id',0);
        $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $capacity=$this->sewingcapacity
        ->join('companies',function($join){
        $join->on('companies.id','=','sewing_capacities.company_id');
        })
        ->join('locations',function($join){
        $join->on('locations.id','=','sewing_capacities.location_id');
        })
        ->where([['sewing_capacities.id','=',$id]])
        ->get([
        'sewing_capacities.*',
        'companies.name as company_name',
        'companies.address as company_address',
        'companies.logo',
        'locations.name as location_name',
        ])
        ->map(function($capacity) use($productionsource){
        $capacity->prod_source=$capacity->prod_source_id?$productionsource[$capacity->prod_source_id]:'';
        return $capacity;
        })
        ->first();
        $dates = collect(
        \DB::select("
        select
        m.cap_month,
        m.cap_month_no,
        m.cap_year,
        sum(m.mkt_cap_mint) as mkt_cap_mint,
        sum(m.mkt_cap_pcs) as mkt_cap_pcs,
        sum(m.prod_cap_mint) as prod_cap_mint,
        sum(m.prod_cap_pcs) as prod_cap_pcs,
        count(m.id) as no_of_day
        from
        (
        SELECT
        sewing_capacities.id,
        sewing_capacity_dates.capacity_date,
        to_char(sewing_capacity_dates.capacity_date, 'Mon') as cap_month,
        to_char(sewing_capacity_dates.capacity_date, 'MM') as cap_month_no,
        to_char(sewing_capacity_dates.capacity_date, 'yy') as cap_year,
        sewing_capacity_dates.mkt_cap_mint,
        sewing_capacity_dates.mkt_cap_pcs,
        sewing_capacity_dates.prod_cap_mint,
        sewing_capacity_dates.prod_cap_pcs
        FROM sewing_capacities
        join sewing_capacity_dates on sewing_capacity_dates.sewing_capacity_id = sewing_capacities.id
        where sewing_capacities.id =? and sewing_capacity_dates.day_status=1
        ) m
        group by m.cap_month,
        m.cap_month_no,
        m.cap_year
        order by m.cap_year,m.cap_month_no
        ",[$id])
        )
        ->map(function($dates) use($productionsource){
        $dates->month=$dates->cap_month.'-'.$dates->cap_year;
        return $dates;
        });
        $pdf = new \Pdf('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $header['logo']=$capacity->logo;
        $header['address']=$capacity->company_address;
        $header['title']='Sewing Capacity';
        //$header['barcodestyle']= '';
        //$header['barcodeno']= '';

        $pdf->setCustomHeader($header);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Sewing Capacity');

        $view= \View::make('Defult.Util.SewingCapacityPdf',['capacity'=>$capacity,'dates'=>$dates]);
        $html_content=$view->render();
        $pdf->SetY(25);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SewingCapacityPdf.pdf';
        $pdf->output($filename);
    }

}
