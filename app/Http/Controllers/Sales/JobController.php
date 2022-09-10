<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleColorRepository;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\GmtsProcessLossRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Http\Requests\JobRequest;

class JobController extends Controller
{
    private $job;
    private $company;
    private $style;
    private $stylecolor;
    private $stylesize;
    private $buyer;
    private $currency;
    private $uom;
    private $season;
    private $salesorder;
    private $country;
    private $stylegmts;
    private $projection;
    private $gmtsprocessloss;
    private $mktcost;

    public function __construct(
        JobRepository $job,
        CompanyRepository $company,
        StyleRepository $style,
        StyleColorRepository $stylecolor,
        StyleSizeRepository $stylesize,
        BuyerRepository $buyer,
        CurrencyRepository $currency,
        UomRepository $uom,
        SeasonRepository $season,
        CountryRepository $country,
        StyleGmtsRepository $stylegmts,
        SalesOrderRepository $salesorder,
        ProjectionRepository $projection,
        GmtsProcessLossRepository $gmtsprocessloss,
        MktCostRepository $mktcost
    ) {
        $this->job      = $job;
        $this->company  = $company;
        $this->style    = $style;
        $this->stylecolor = $stylecolor;
        $this->stylesize = $stylesize;
        $this->buyer    = $buyer;
        $this->currency = $currency;
        $this->uom      = $uom;
        $this->seasons  = $season;
        $this->salesorder = $salesorder;
        $this->country = $country;
        $this->stylegmts = $stylegmts;
        $this->projection = $projection;
        $this->gmtsprocessloss = $gmtsprocessloss;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.jobs',   ['only' => ['create', 'index', 'show']]);
        $this->middleware('permission:create.jobs', ['only' => ['store']]);
        $this->middleware('permission:edit.jobs',   ['only' => ['update']]);
        $this->middleware('permission:delete.jobs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = $this->job
            ->join('styles', function ($join) {
                $join->on('jobs.style_id', '=', 'styles.id');
            })
            ->join('companies', function ($join) {
                $join->on('jobs.company_id', '=', 'companies.id');
            })
            ->join('buyers', function ($join) {
                $join->on('styles.buyer_id', '=', 'buyers.id');
            })
            ->join('currencies', function ($join) {
                $join->on('jobs.currency_id', '=', 'currencies.id');
            })
            ->join('uoms', function ($join) {
                $join->on('styles.uom_id', '=', 'uoms.id');
            })
            ->join('seasons', function ($join) {
                $join->on('styles.season_id', '=', 'seasons.id');
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'like', '%' . request('style_ref', 0) . '%');
            })
            ->when(request('job_no'), function ($q) {
                return $q->where('jobs.job_no', 'like', '%' . request('job_no', 0) . '%');
            })
            ->when(request('style_description'), function ($q) {
                return $q->where('styles.style_description', 'like', '%' . request('style_description', 0) . '%');
            })
            ->orderBy('id', 'desc')
            ->get([
                'jobs.id',
                'jobs.job_no',
                'jobs.company_id',
                'jobs.style_id',
                'jobs.currency_id',
                'jobs.exch_rate',
                'jobs.remarks',
                'styles.id as style_id',
                'styles.style_ref',
                'styles.buyer_id',
                'styles.uom_id',
                'styles.season_id',
                'companies.code as company_name',
                'buyers.name as buyer_name',
                'currencies.code as currency_name',
                'seasons.name as season_name',
                'uoms.code as uom_name',
            ]);
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
        $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
        $stylecolor = array('' => "-Select-");
        $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
        $uom = array_prepend(array_pluck($this->uom->get(), 'name', 'id'), '-Select-', '');
        $season = array_prepend(array_pluck($this->seasons->get(), 'name', 'id'), '-Select-', '');
        $country = array_prepend(array_pluck($this->country->get(), 'name', 'id'), '-Select-', '');
        $stylegmts = array('' => "-Select-");
        $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
        $tnatask = array_prepend(config('bprs.tnatask'), '-Select-', '');
        $cutoff = array_prepend(config('bprs.cutoff'), '-Select-', '');
        $breakdownbasis = array_prepend(config('bprs.breakdownbasis'), '-Select-', '');
        $status = array_only(config('bprs.status'), [1, 2, 4]);
        $projection = array_prepend(array_pluck($this->projection->get(), 'proj_no', 'id'), '-Select-', '');
        return Template::loadView('Sales.Job', ['company' => $company, 'stylecolor' => $stylecolor, 'buyer' => $buyer, 'currency' => $currency, 'uom' => $uom, 'season' => $season, 'country' => $country, 'stylegmts' => $stylegmts, 'fabriclooks' => $fabriclooks, 'tnatask' => $tnatask, 'cutoff' => $cutoff, 'breakdownbasis' => $breakdownbasis, 'projection' => $projection, 'status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRequest $request)
    {

        $style = $this->style->find($request->style_id);
        $gmtsprocessloss = $this->gmtsprocessloss
            ->where([['company_id', $request->company_id]])
            ->where([['buyer_id', $style->buyer_id]])
            ->get(['id'])->count();
        if (!$gmtsprocessloss) {
            return response()->json(array('success' => false, 'message' => 'GMT Process Loss not set'), 200);
        }
        $mktcost = $this->mktcost->where([['style_id', '=', $request->style_id]])->whereNotNull('final_approved_by')->get();
        if (!$mktcost->first()) {
            return response()->json(array('success' => false,  'message' => 'Please approved the marketing cost for this style '), 200);
        }

        $max = $this->job->where([['company_id', $request->company_id]])->max('job_no');
        $job_no = $max + 1;
        $job = $this->job->create(['job_no' => $job_no, 'company_id' => $request->company_id, 'style_id' => $request->style_id, 'buyer_id' => $request->buyer_id, 'currency_id' => $request->currency_id, 'exch_rate' => $request->exch_rate, 'uom_id' => $request->uom_id, 'season_id' => $request->season_id, 'remarks' => $request->remarks]);
        if ($job) {
            return response()->json(array('success' => true, 'id' => $job->id, 'job_no' => $job_no, 'message' => 'Save Successfully'), 200);
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
        //$job = $this->job->find($id);
        $job = $this->job
            ->join('styles', function ($join) use ($id) {
                $join->on('jobs.style_id', '=', 'styles.id');
            })
            ->where('jobs.id', '=', $id)
            ->get([
                'jobs.id',
                'jobs.job_no',
                'jobs.company_id',
                'jobs.style_id',
                'jobs.currency_id',
                'jobs.remarks',
                'jobs.exch_rate',
                'styles.style_ref',
                'styles.buyer_id',
                'styles.uom_id',
                'styles.season_id',
            ]);
        $row['fromData'] = $job[0];
        $dropdown['att'] = '';
        $row['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JobRequest $request, $id)
    {

        $salesorder = $this->salesorder->where([['job_id', '=', $id]])->first();
        if ($salesorder) {
            $this->job->update($id, ['currency_id' => $request->currency_id, 'exch_rate' => $request->exch_rate, 'remarks' => $request->remarks]);
            return response()->json(array('success' => false, 'message' => 'Style change not allowed after Order entry in second tab.'), 200);
        }
        $style = $this->style->find($request->style_id);
        $gmtsprocessloss = $this->gmtsprocessloss
            ->where([['company_id', $request->company_id]])
            ->where([['buyer_id', $style->buyer_id]])
            ->get(['id'])->count();
        if (!$gmtsprocessloss) {
            return response()->json(array('success' => false, 'message' => 'GMT Process Loss not set'), 200);
        }

        $job = $this->job->update($id, $request->except(['id', 'job_no', 'company_id', 'style_ref']));
        if ($job) {
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
        if ($this->job->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
