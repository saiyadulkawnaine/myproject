<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetRequest;
use PDF;

class BudgetController extends Controller
{

	private $budget;
	private $company;
	private $style;
	private $currency;
	private $buyer;
	private $supplier;
	private $uom;
	private $team;
	private $itemaccount;
	private $productionprocess;
	private $itemclass;
	private $job;
	private $embelishmenttype;

	public function __construct(BudgetRepository $budget, CompanyRepository $company, StyleRepository $style, CurrencyRepository $currency, BuyerRepository $buyer, SupplierRepository $supplier, UomRepository $uom, TeamRepository $team, ItemAccountRepository $itemaccount, ProductionProcessRepository $productionprocess, ItemclassRepository $itemclass, JobRepository $job, EmbelishmentTypeRepository $embelishmenttype)
	{
		$this->budget = $budget;
		$this->company  = $company;
		$this->style = $style;
		$this->currency = $currency;
		$this->buyer = $buyer;
		$this->supplier = $supplier;
		$this->uom = $uom;
		$this->team = $team;
		$this->itemaccount = $itemaccount;
		$this->productionprocess = $productionprocess;
		$this->itemclass = $itemclass;
		$this->job = $job;
		$this->embelishmenttype = $embelishmenttype;

		$this->middleware('auth');
		$this->middleware('permission:view.budgets',   ['only' => ['create', 'index', 'show']]);
		$this->middleware('permission:create.budgets', ['only' => ['store']]);
		$this->middleware('permission:edit.budgets',   ['only' => ['update']]);
		$this->middleware('permission:delete.budgets', ['only' => ['destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		echo json_encode($this->budget->getAll());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$commissionfor = array_prepend([1 => "Local Agent", 2 => "Foreign Agent"], '-Select-', '');
		$cmmethod = config('bprs.cmmethod');
		$costingunit = array_prepend(config('bprs.costingunit'), '-Select-', '');
		$othercosthead = array_prepend(config('bprs.othercosthead'), '-Select-', '');
		$currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
		$company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
		$buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
		$supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
		$uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
		$trimgroup = array_prepend(array_pluck($this->itemclass->getAccessories(), 'name', 'id'), '-Select-', '');

		$productionprocess = array_prepend(array_pluck($this->productionprocess->whereNotIn('production_area_id', [5])->get(), 'process_name', 'id'), '-Select-', '');
		$productionprocess_yarn_dyeing = array_pluck($this->productionprocess->where([['production_area_id', '=', 5]])->get(), 'process_name', 'id');

		return Template::loadView('Bom.Budget', ['costingunit' => $costingunit, 'company' => $company, 'currency' => $currency, 'buyer' => $buyer, 'supplier' => $supplier, 'uom' => $uom, 'trimgroup' => $trimgroup, 'productionprocess' => $productionprocess, 'commissionfor' => $commissionfor, 'cmmethod' => $cmmethod, 'othercosthead' => $othercosthead, 'productionprocess_yarn_dyeing' => $productionprocess_yarn_dyeing]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(BudgetRequest $request)
	{
		$budget = $this->budget->create(['job_id' => $request->job_id, 'style_id' => $request->style_id, 'costing_unit_id' => $request->costing_unit_id, 'budget_date' => $request->budget_date, 'remarks' => $request->remarks]);
		if ($budget) {
			return response()->json(array('success' => true, 'id' => $budget->id, 'message' => 'Save Successfully'), 200);
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

		$budget = $this->budget
			->selectRaw(
				'budgets.id,
			budgets.style_id,
			budgets.budget_date,
			budgets.remarks,
			budgets.costing_unit_id,
			jobs.id as job_id,
			jobs.job_no,
			jobs.currency_id,
			jobs.company_id,
			jobs.exch_rate,
			styles.style_ref,
			styles.buyer_id,
			styles.uom_id,
			buyers.name as buyer_name,
			teams.name as team_name,
			currencies.code as currency_code,
			companies.code as company_name,
			uoms.code as uom_code,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.job_id', '=', 'jobs.id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'jobs.company_id');
			})
			->join('teams', function ($join) {
				$join->on('teams.id', '=', 'styles.team_id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'jobs.currency_id');
			})
			->join('uoms', function ($join) {
				$join->on('uoms.id', '=', 'styles.uom_id');
			})
			->groupBy(
				[
					'budgets.id',
					'budgets.style_id',
					'budgets.budget_date',
					'budgets.remarks',
					'budgets.costing_unit_id',
					'jobs.id',
					'jobs.job_no',
					'jobs.currency_id',
					'jobs.company_id',
					'jobs.exch_rate',
					'styles.style_ref',
					'styles.buyer_id',
					'styles.uom_id',
					'buyers.name',
					'teams.name',
					'currencies.code',
					'companies.code',
					'uoms.code'
				]
			)
			->where([['budgets.id', '=', $id]])
			->get()
			->first();

		$JobQty = $this->job->totalJobQty($budget->job_id);
		$JobCutQty = $this->job->totalJobCutQty($budget->job_id);
		$JobAmount = $this->job->totalJobAmount($budget->job_id);
		$Totalcost = $this->budget->totalCost($budget->id);

		$budget->order_qty = $JobQty;
		$budget->plan_cut_qty = $JobCutQty;
		$budget->order_amount = $JobAmount;
		$budget->totalcost = $Totalcost;
		$row['fromData'] = $budget;
		echo json_encode($row);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(budgetRequest $request, $id)
	{
		$budget = $this->budget->update($id, ['budget_date' => $request->budget_date, 'remarks' => $request->remarks]);
		if ($budget) {
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
		if ($this->budget->delete($id)) {
			return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
		}
	}

	public function searchBudget()
	{
		$rows = $this->budget
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'jobs.company_id');
			})
			->join('teams', function ($join) {
				$join->on('teams.id', '=', 'styles.team_id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'jobs.currency_id');
			})
			->join('uoms', function ($join) {
				$join->on('uoms.id', '=', 'styles.uom_id');
			})
			->when(request('buyer_search_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_search_id', 0));
			})
			->when(request('style_ref'), function ($q) {
				return $q->where('styles.style_ref', 'like', '%' . request('style_ref', 0) . '%');
			})
			->when(request('from_date'), function ($q) {
				return $q->where('budgets.budget_date', '>=', request('from_date', 0));
			})
			->when(request('to_date'), function ($q) {
				return $q->where('budgets.budget_date', '<=', request('to_date', 0));
			})
			->orderBy('budgets.id', 'desc')
			->get([
				'budgets.*',
				'jobs.job_no',
				'jobs.exch_rate',
				'styles.style_ref',
				'buyers.code as buyer_name',
				'teams.name as team_name',
				'currencies.code as currency_code',
				'companies.code as company_name',
				'uoms.code as uom_code'
			]);

		echo json_encode($rows);
	}

	public function getPdf()
	{
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->AddPage();
		$pdf->SetY(10);
		$txt = "Budget Cost";
		$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
		$pdf->SetY(5);
		$pdf->Text(90, 5, $txt);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTitle('Budget Cost');
		/*$pdf->SetY(0);
        $pdf->SetX(120);
	    $barcodestyle = array(
	    'position' => '',
	    'align' => 'C',
	    'stretch' => false,
	    'fitwidth' => true,
	    'cellfitalign' => '',
	    'border' => false,
	    'hpadding' => 'auto',
	    'vpadding' => 'auto',
	    'fgcolor' => array(0,0,0),
	    'bgcolor' => false, //array(255,255,255),
	    'text' => true,
	    'font' => 'helvetica',
	    'fontsize' => 8,
	    'stretchtext' => 4
	    );*/

		$id = request('id', 0);
		//$pdf->write1DBarcode(str_pad($id,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

		$incoterm = array_prepend([1 => "FOB", 2 => "CFR", 3 => "CIF"], '-Select-', '');
		$costingunit = array_prepend(config('bprs.costingunit'), '-Select-', '');
		$budgets = array();

		$rows = $this->budget
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'jobs.company_id');
			})
			->join('teams', function ($join) {
				$join->on('teams.id', '=', 'styles.team_id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'jobs.currency_id');
			})
			->join('uoms', function ($join) {
				$join->on('uoms.id', '=', 'styles.uom_id');
			})
			->join('seasons', function ($join) {
				$join->on('seasons.id', '=', 'styles.season_id');
			})
			->join('users', function ($join) {
				$join->on('users.id', '=', 'budgets.created_by');
			})
			->where([['budgets.id', '=', $id]])
			->get([
				'budgets.*',
				'jobs.id as job_id',
				'jobs.job_no',
				'styles.style_ref',
				'styles.flie_src',
				'buyers.name as buyer_name',
				'teams.name as team_name',
				'currencies.code as currency_code',
				'companies.code as company_name',
				'uoms.code as uom_code',
				'seasons.name as season_name',
				'users.name as user_name'
			])->first();
		$rows->costingunit = $costingunit[$rows->costing_unit_id];

		$JobQty = $this->job->totalJobQty($rows->job_id);
		$JobCutQty = $this->job->totalJobCutQty($rows->job_id);
		$JobAmount = $this->job->totalJobAmount($rows->job_id);
		$shipDate = $this->job
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->where([['jobs.id', '=', $rows->job_id]])
			->selectRaw(
				'
		 jobs.id,
         min(sales_orders.ship_date) as min_ship_date,
         max(sales_orders.ship_date) as max_ship_date
        '
			)
			->groupBy([
				'jobs.id'
			])
			->first();

		$rows->order_qty = $JobQty;
		$rows->plan_cut_qty = $JobCutQty;
		$rows->order_amount = $JobAmount;
		$rows->min_ship_date = date('d-M-Y', strtotime($shipDate->min_ship_date));
		$rows->max_ship_date = date('d-M-Y', strtotime($shipDate->max_ship_date));

		$budget['master'] = $rows;
		$budget['fabrics'] = $this->budget->fabricCost($id);
		$yarnDescription = $this->itemaccount
			->join('item_account_ratios', function ($join) {
				$join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
			})
			->join('yarncounts', function ($join) {
				$join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
			})
			->join('yarntypes', function ($join) {
				$join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
			})
			->join('itemclasses', function ($join) {
				$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
			})
			->join('compositions', function ($join) {
				$join->on('compositions.id', '=', 'item_account_ratios.composition_id');
			})
			->join('itemcategories', function ($join) {
				$join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
			})
			->where([['itemcategories.identity', '=', 1]])
			->get([
				'item_accounts.id',
				'yarncounts.count',
				'yarncounts.symbol',
				'yarntypes.name as yarn_type',
				'itemclasses.name as itemclass_name',
				'compositions.name as composition_name',
				'item_account_ratios.ratio',
			]);
		$itemaccountArr = array();
		$yarnCompositionArr = array();
		foreach ($yarnDescription as $row) {
			$itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
			$itemaccountArr[$row->id]['yarn_type'] = $row->yarn_type;
			$itemaccountArr[$row->id]['itemclass_name'] = $row->itemclass_name;
			$yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
		}
		$yarnDropdown = array();
		foreach ($itemaccountArr as $key => $value) {
			$yarnDropdown[$key] = $value['count'] . " " . implode(",", $yarnCompositionArr[$key]) . " " . $value['yarn_type'];
		}

		$yarns = $this->budget->yarnCost($id)
			->map(function ($yarns) use ($yarnDropdown) {
				$yarns->id = $yarns->id;
				$yarns->yarn_cons = $yarns->cons;
				$yarns->yarn_rate = $yarns->rate;
				$yarns->yarn_amount = $yarns->amount;
				$yarns->yarn_des = isset($yarnDropdown[$yarns->item_account_id]) ? $yarnDropdown[$yarns->item_account_id] : '';
				return $yarns;
			});
		$rows = $yarns->groupBy('item_account_id');
		$mktcostyarns = array();
		foreach ($rows as $data) {
			$yarn_cons = 0;
			$yarn_amount = 0;
			$yarn_rate = 0;
			$yarn_des = '';
			foreach ($data as $row) {
				$yarn_cons += $row->yarn_cons;
				$yarn_amount += $row->yarn_amount;
				$yarn_des = $row->yarn_des;
			}
			$mktcostyarn['yarn_cons'] = $yarn_cons;
			$mktcostyarn['yarn_rate'] = 0;
			if ($yarn_cons) {
				$mktcostyarn['yarn_rate'] = number_format($yarn_amount / $yarn_cons, 4);
			}
			$mktcostyarn['yarn_amount'] = $yarn_amount;
			$mktcostyarn['yarn_des'] = $yarn_des;
			array_push($mktcostyarns, $mktcostyarn);
		}
		$budget['yarns'] = $mktcostyarns;


		$mktcostfabricprods = array();
		$fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
		$fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
		$fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');

		$fabricDescription = $this->budget
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->join('autoyarnratios', function ($join) {
				$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
			})
			->join('compositions', function ($join) {
				$join->on('compositions.id', '=', 'autoyarnratios.composition_id');
			})
			->join('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->join('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->where([['budgets.id', '=', $id]])
			->groupBy([
				'budget_fabrics.id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'item_accounts.item_description',
				'gmtsparts.name',
				'autoyarnratios.composition_id',
				'constructions.name',
				'compositions.name',
				'autoyarnratios.ratio',
			])
			->get([
				'budget_fabrics.id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'gmtsparts.name as gmtspart_name',
				'item_accounts.item_description',
				'autoyarnratios.composition_id',
				'constructions.name as construction',
				'compositions.name',
				'autoyarnratios.ratio',
			]);
		$fabricDescriptionArr = array();
		$fabricCompositionArr = array();
		foreach ($fabricDescription as $row) {
			$fabricDescriptionArr[$row->id] = $row->item_description . " " . $row->gmtspart_name . " " . $fabricnature[$row->fabric_nature_id] . " " . $fabriclooks[$row->fabric_look_id] . " " . $fabricshape[$row->fabric_shape_id] . " " . $row->construction;
			$fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
		}
		$desDropdown = array();
		foreach ($fabricDescriptionArr as $key => $val) {
			$desDropdown[$key] = $val . " " . implode(",", $fabricCompositionArr[$key]);
		}

		$prod = $this->budget->fabricProdCost($id)
			->map(function ($prod) use ($desDropdown) {
				$prod->process_id = $prod->process_name;
				$prod->total_amount = $prod->amount + $prod->overhead_amount;
				$prod->budgetfabric = $desDropdown[$prod->budget_fabric_id];
				return $prod;
			});
		$mktcostfabricprods = $prod->groupBy('process_id');
		$budget['fabricProd'] = $mktcostfabricprods;

		$yarnDyeing = $this->budget->yarnDyeingCost($id)
			->map(function ($yarnDyeing) use ($desDropdown) {
				$yarnDyeing->process_id = $yarnDyeing->process_name;
				$yarnDyeing->total_amount = $yarnDyeing->amount + $yarnDyeing->overhead_amount;
				$yarnDyeing->budgetfabric = $desDropdown[$yarnDyeing->budget_fabric_id];
				return $yarnDyeing;
			});
		$mktcostyarndyeing = $yarnDyeing->groupBy('process_id');
		$budget['yarndyeing'] = $mktcostyarndyeing;

		$budget['trims'] = $this->budget->TrimCost($id);
		$budget['embs'] = $this->budget->EmbCost($id);
		$othercosthead = array_prepend(config('bprs.othercosthead'), '-Select-', '');
		$other = $this->budget->otherCost($id);
		$otherArr = array();
		foreach ($other as $key => $value) {
			$otherArr[$key]['cost_head'] = $othercosthead[$key];
			$otherArr[$key]['amount'] = $value;
		}

		$budget['other'] = $otherArr;
		$budget['cm'] = $this->budget->totalCmCost($id);
		$budget['commercial'] = $this->budget->CommercialCost($id);
		$budget['commission'] = $this->budget->Commission($id);
		$budget['total_cost'] = $this->budget->totalCost($id);

		$view = \View::make('Defult.Bom.BudgetPdf', ['budget' => $budget]);
		$html_content = $view->render();
		$pdf->SetY(18);
		$pdf->WriteHtml($html_content, true, false, true, false, '');
		$filename = storage_path() . '/BudgetPdf.pdf';
		$pdf->output($filename);
		//$pdf->output($filename,'F');
		//return response()->download($filename);
	}


	public function getMos()
	{

		$id = request('id', 0);
		$incoterm = array_prepend([1 => "FOB", 2 => "CFR", 3 => "CIF"], '-Select-', '');
		$costingunit = array_prepend(config('bprs.costingunit'), '-Select-', '');
		$budgets = array();

		$rows = $this->budget
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->leftJoin('buyers as buyingagent', function ($join) {
				$join->on('buyingagent.id', '=', 'styles.buying_agent_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'jobs.company_id');
			})
			->join('teams', function ($join) {
				$join->on('teams.id', '=', 'styles.team_id');
			})
			->leftJoin('teammembers', function ($join) {
				$join->on('teammembers.id', '=', 'styles.teammember_id');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'teammembers.user_id');
			})

			->leftJoin('teammembers as factory_marchants', function ($join) {
				$join->on('factory_marchants.id', '=', 'styles.factory_merchant_id');
			})

			->leftJoin('users as factory_marchant_names', function ($join) {
				$join->on('factory_marchant_names.id', '=', 'factory_marchants.user_id');
			})

			->leftJoin('employee_h_rs as teammember_details', function ($join) {
				$join->on('teammember_details.user_id', '=', 'users.id');
			})
			->leftJoin('employee_h_rs as factory_marchant_details', function ($join) {
				$join->on('factory_marchant_details.user_id', '=', 'factory_marchant_names.id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'jobs.currency_id');
			})
			->join('uoms', function ($join) {
				$join->on('uoms.id', '=', 'styles.uom_id');
			})
			->join('seasons', function ($join) {
				$join->on('seasons.id', '=', 'styles.season_id');
			})
			->leftJoin('users as createdbys', function ($join) {
				$join->on('createdbys.id', '=', 'budgets.created_by');
			})
			->where([['budgets.id', '=', $id]])
			->get([
				'budgets.*',
				'jobs.id as job_id',
				'jobs.job_no',
				'styles.style_ref',
				'styles.style_ref',
				'buyers.name as buyer_name',
				'buyingagent.name as buying_agent_name',
				'teams.name as team_name',
				'currencies.code as currency_code',
				'companies.code as company_name',
				'companies.name as full_company_name',
				'companies.logo',
				'uoms.code as uom_code',
				'seasons.name as season_name',
				'users.name as tl_marchant',
				'factory_marchant_names.name as dl_marchant',
				'teammember_details.contact',
				'teammember_details.email',
				'factory_marchant_details.contact as contactb',
				'factory_marchant_details.email as emailb',
				'createdbys.name as created_by_name'
			])
			->first();
		$rows->entry_date = date('d-M-Y', strtotime($rows->created_at));

		$rows->costingunit = $costingunit[$rows->costing_unit_id];

		$JobQty = $this->job->totalJobQty($rows->job_id);
		$JobCutQty = $this->job->totalJobCutQty($rows->job_id);
		$JobAmount = $this->job->totalJobAmount($rows->job_id);

		$rows->order_qty = $JobQty;
		$rows->plan_cut_qty = $JobCutQty;
		$rows->order_amount = $JobAmount;
		$rows->selling_price = number_format($JobAmount / $JobQty, 2);

		$budget['master'] = $rows;

		$materialsourcing = array_prepend(config('bprs.materialsourcing'), '-Select-', '');
		$fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
		$fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
		$fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
		$dyetype = array_prepend(config('bprs.dyetype'), '-Select-', '');


		$fabricDescription = $this->budget->selectRaw(
			'style_fabrications.id,
		constructions.name as construction,
		autoyarnratios.composition_id,
		compositions.name,
		autoyarnratios.ratio'
		)
			->leftJoin('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->leftJoin('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('autoyarnratios', function ($join) {
				$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
			})
			->leftJoin('compositions', function ($join) {
				$join->on('compositions.id', '=', 'autoyarnratios.composition_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->where([['budgets.id', '=', $id]])
			->get();
		$fabricDescriptionArr = array();
		$fabricCompositionArr = array();
		foreach ($fabricDescription as $row) {
			$fabricDescriptionArr[$row->id] = $row->construction;
			$fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
		}
		$desDropdown = array();
		foreach ($fabricDescriptionArr as $key => $val) {
			$desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
		}


		$fabrics = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.dyeing_type_id,
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.fabric_color,
			budget_fabric_cons.dia,
			colors.name as fabric_color,
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(budget_fabric_cons.amount) as amount,
			avg(budget_fabric_cons.rate) as rate,
			sum(sales_order_gmt_color_sizes.qty) as order_qty
			'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->leftJoin('budget_fabric_cons', function ($join) {
				$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('budget_fabric_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'budget_fabric_cons.fabric_color');
			})
			->where([['budgets.id', '=', $id]])
			->where([['style_fabrications.material_source_id', '=', 10]])
			->where([['style_fabrications.is_narrow', '=', 0]])
			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.dyeing_type_id',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'budget_fabric_cons.fabric_color',
				'budget_fabric_cons.dia',
				'colors.name'
			])
			->get()
			->map(function ($fabrics) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $dyetype) {
				$fabrics->fabric_description = isset($desDropdown[$fabrics->style_fabrication_id]) ? $desDropdown[$fabrics->style_fabrication_id] : '';
				$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
				$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
				$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
				$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
				$fabrics->dyetype = $dyetype[$fabrics->dyeing_type_id];
				return $fabrics;
			});

		$narrowfabrics = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.dyeing_type_id,
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			--budget_fabric_cons.dia,
			--budget_fabric_cons.measurment,
			colors.name as fabric_color,
			gmtcolors.name as gmt_color,
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(budget_fabric_cons.amount) as amount,
			avg(budget_fabric_cons.rate) as rate,
			sum(sales_order_gmt_color_sizes.qty) as order_qty
			'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->leftJoin('budget_fabric_cons', function ($join) {
				$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.id', '=', 'budget_fabric_cons.sales_order_gmt_color_size_id');
			})
			->leftJoin('style_gmt_color_sizes', function ($join) {
				$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
			})
			->leftJoin('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
			})
			->leftJoin('colors as gmtcolors', function ($join) {
				$join->on('gmtcolors.id', '=', 'style_colors.color_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'budget_fabric_cons.fabric_color');
			})
			->where([['budgets.id', '=', $id]])
			->where([['style_fabrications.material_source_id', '=', 10]])
			->where([['style_fabrications.is_narrow', '=', 1]])
			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.dyeing_type_id',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'budget_fabric_cons.fabric_color',
				//'budget_fabric_cons.dia',
				//'budget_fabric_cons.measurment',
				'gmtcolors.name',
				'style_colors.id',
				'colors.name'
			])
			->get()
			->map(function ($fabrics) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $dyetype) {
				$fabrics->fabric_description = isset($desDropdown[$fabrics->style_fabrication_id]) ? $desDropdown[$fabrics->style_fabrication_id] : '';
				$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
				$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
				$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
				$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
				$fabrics->dyetype = $dyetype[$fabrics->dyeing_type_id];
				return $fabrics;
			});
		$budget['fabrics'] = ['main' => $fabrics, 'narrow' => $narrowfabrics];

		$yarnDescription = $this->itemaccount
			->join('item_account_ratios', function ($join) {
				$join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
			})
			->join('yarncounts', function ($join) {
				$join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
			})
			->join('yarntypes', function ($join) {
				$join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
			})
			->join('itemclasses', function ($join) {
				$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
			})
			->join('compositions', function ($join) {
				$join->on('compositions.id', '=', 'item_account_ratios.composition_id');
			})
			->join('itemcategories', function ($join) {
				$join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
			})
			->where([['itemcategories.identity', '=', 1]])
			->get([
				'item_accounts.id',
				'yarncounts.count',
				'yarncounts.symbol',
				'yarntypes.name as yarn_type',
				'itemclasses.name as itemclass_name',
				'compositions.name as composition_name',
				'item_account_ratios.ratio',
			]);
		$itemaccountArr = array();
		$yarnCompositionArr = array();
		foreach ($yarnDescription as $row) {
			$itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
			$itemaccountArr[$row->id]['yarn_type'] = $row->yarn_type;
			$itemaccountArr[$row->id]['itemclass_name'] = $row->itemclass_name;
			$yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
		}
		$yarnDropdown = array();
		foreach ($itemaccountArr as $key => $value) {
			$yarnDropdown[$key] = implode(",", $yarnCompositionArr[$key]);
		}


		$yarn = $this->budget
			->selectRaw(
				'budgets.id,
		item_accounts.id as item_account_id,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		suppliers.name as supplier_name,
		sum(budget_yarns.cons) as cons,
		avg(budget_yarns.rate) as rate,
		sum(budget_yarns.amount) as amount
		'
			)
			->leftJoin('budget_yarns', function ($join) {
				$join->on('budget_yarns.budget_id', '=', 'budgets.id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'budget_yarns.item_account_id');
			})
			->leftJoin('yarncounts', function ($join) {
				$join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
			})
			->leftJoin('yarntypes', function ($join) {
				$join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
			})
			->leftJoin('suppliers', function ($join) {
				$join->on('suppliers.id', '=', 'budget_yarns.supplier_id');
			})
			->groupBy([
				'budgets.id',
				'item_accounts.id',
				'yarncounts.count',
				'yarncounts.symbol',
				'yarntypes.name',
				'suppliers.name'
			])
			->where([['budgets.id', '=', $id]])
			->get()
			->map(function ($yarn) use ($yarnDropdown) {
				$yarn->composition = isset($yarnDropdown[$yarn->item_account_id]) ? $yarnDropdown[$yarn->item_account_id] : '';
				return $yarn;
			});
		$budget['yarns'] = $yarn;

		$aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-', '');

		$aops = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.embelishment_type_id,
			style_fabrications.dyeing_type_id,
			style_fabrications.coverage,
			style_fabrications.impression,
			
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			colors.name as fabric_color,
			budget_fabric_prods.production_process_id,
			production_processes.process_name,
			sum(budget_fabric_prod_cons.bom_qty) as fin_fab
			'
			)

			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})

			->join('budget_fabric_prods', function ($join) {
				$join->on('budget_fabric_prods.budget_id', '=', 'budgets.id');
				$join->on('budget_fabric_prods.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('budget_fabric_prod_cons', function ($join) {
				$join->on('budget_fabric_prod_cons.budget_fabric_prod_id', '=', 'budget_fabric_prods.id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'budget_fabric_prod_cons.fabric_color_id');
			})
			->join('production_processes', function ($join) {
				$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
			})
			->where([['budgets.id', '=', $id]])
			//->whereIn('budget_fabric_prods.production_process_id',[61,21])
			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.embelishment_type_id',
				'style_fabrications.dyeing_type_id',
				'style_fabrications.coverage',
				'style_fabrications.impression',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'colors.name',
				'budget_fabric_prods.production_process_id',
				'production_processes.process_name'
			])
			->get()
			->map(function ($aops) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $aoptype, $dyetype) {
				$aops->fabric_description = isset($desDropdown[$aops->style_fabrication_id]) ? $desDropdown[$aops->style_fabrication_id] : '';
				$aops->materialsourcing = $materialsourcing[$aops->material_source_id];
				$aops->fabricnature = $fabricnature[$aops->fabric_nature_id];
				$aops->fabriclooks = $fabriclooks[$aops->fabric_look_id];
				$aops->fabricshape = $fabricshape[$aops->fabric_shape_id];
				$aops->aoptype = $aoptype[$aops->embelishment_type_id];
				$aops->dyeing_type = $dyetype[$aops->dyeing_type_id];
				return $aops;
			});
		$prods = $aops
			->filter(function ($value) {
				if ($value->production_process_id != 61 || $value->production_process_id != 21) {
					return $value;
				}
			})
			->groupBy('production_process_id');



		$aop = $aops->filter(function ($value) {
			if ($value->production_process_id == 61) {
				return $value;
			}
		});
		$burnout = $aops->filter(function ($value) {
			if ($value->production_process_id == 21) {
				return $value;
			}
		});


		$budget['aop'] = $aop;
		$budget['burnout'] = $burnout;
		$budget['fabricProd'] = $prods;


		$yarnDyeingDescription = $this->budget
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->join('autoyarnratios', function ($join) {
				$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
			})
			->join('compositions', function ($join) {
				$join->on('compositions.id', '=', 'autoyarnratios.composition_id');
			})
			->join('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->join('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->where([['budgets.id', '=', $id]])
			->groupBy([
				'budget_fabrics.id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'item_accounts.item_description',
				'gmtsparts.name',
				'autoyarnratios.composition_id',
				'constructions.name',
				'compositions.name',
				'autoyarnratios.ratio',
			])
			->get([
				'budget_fabrics.id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'gmtsparts.name as gmtspart_name',
				'item_accounts.item_description',
				'autoyarnratios.composition_id',
				'constructions.name as construction',
				'compositions.name',
				'autoyarnratios.ratio',
			]);
		$yarndyeingDescriptionArr = array();
		$yarndyeingCompositionArr = array();
		foreach ($yarnDyeingDescription as $row) {
			$yarndyeingDescriptionArr[$row->id] = $row->item_description . " " . $row->gmtspart_name . " " . $fabricnature[$row->fabric_nature_id] . " " . $fabriclooks[$row->fabric_look_id] . " " . $fabricshape[$row->fabric_shape_id] . " " . $row->construction;
			$yarndyeingCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
		}
		$yarndyeingdesDropdown = array();
		foreach ($yarndyeingDescriptionArr as $key => $val) {
			$yarndyeingdesDropdown[$key] = $val . " " . implode(",", $yarndyeingCompositionArr[$key]);
		}

		$yarnDyeing = $this->budget->yarnDyeingCost($id)
			->map(function ($yarnDyeing) use ($yarndyeingdesDropdown) {
				$yarnDyeing->process_id = $yarnDyeing->process_name;
				$yarnDyeing->total_amount = $yarnDyeing->amount + $yarnDyeing->overhead_amount;
				$yarnDyeing->budgetfabric = $yarndyeingdesDropdown[$yarnDyeing->budget_fabric_id];
				return $yarnDyeing;
			});
		$mktcostyarndyeing = $yarnDyeing->groupBy('process_id');
		$budget['yarndyeing'] = $mktcostyarndyeing;

		$cuttings = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.dyeing_type_id,
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.fabric_color,
			budget_fabric_cons.dia,
			colors.name as gmt_color,
			sizes.name as gmt_size,
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(budget_fabric_cons.amount) as amount,
			avg(budget_fabric_cons.rate) as rate,
			avg(budget_fabric_cons.cons) as cons,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->leftJoin('budget_fabric_cons', function ($join) {
				$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.id', '=', 'budget_fabric_cons.sales_order_gmt_color_size_id');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->leftJoin('colors as fabric_colors', function ($join) {
				$join->on('fabric_colors.id', '=', 'budget_fabric_cons.fabric_color');
			})
			->where([['budgets.id', '=', $id]])
			->where([['style_fabrications.is_narrow', '=', 0]])
			->where([['style_fabrications.material_source_id', '=', 10]])

			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.dyeing_type_id',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'budget_fabric_cons.fabric_color',
				'budget_fabric_cons.dia',
				'style_colors.id',
				'style_sizes.id',
				'colors.name',
				'sizes.name'
			])
			->orderBy('style_fabrications.id')
			->get()
			->map(function ($fabrics) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $dyetype) {
				$fabrics->fabric_description = isset($desDropdown[$fabrics->style_fabrication_id]) ? $desDropdown[$fabrics->style_fabrication_id] : '';
				$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
				$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
				$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
				$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
				$fabrics->dyetype = $dyetype[$fabrics->dyeing_type_id];
				return $fabrics;
			});

		$budget['cuttings'] = $cuttings;

		$trims = $this->budget
			->selectRaw(
				'budgets.id,
			itemclasses.name,
			itemclasses.trims_type_id,
			uoms.code,
			budget_trims.description,
			item_accounts.item_description,
			sum(budget_trim_cons.bom_trim) as bom_trim,
			avg(budget_trim_cons.cons) as cons,
			sum(sales_order_gmt_color_sizes.qty) as gmtqty
			'
			)

			->leftJoin('budget_trims', function ($join) {
				$join->on('budget_trims.budget_id', '=', 'budgets.id');
			})
			->leftJoin('itemclasses', function ($join) {
				$join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
			})
			->leftJoin('itemcategories', function ($join) {
				$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'budget_trims.uom_id');
			})
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
			})

			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id')
					->whereNull('sales_order_gmt_color_sizes.deleted_at');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})

			->leftJoin('budget_trim_cons', function ($join) {
				$join->on('budget_trims.id', '=', 'budget_trim_cons.budget_trim_id')
					->on('sales_order_gmt_color_sizes.id', '=', 'budget_trim_cons.sales_order_gmt_color_size_id')
					->whereNull('budget_trim_cons.deleted_at');
			})
			->groupBy([
				'budgets.id',
				'itemclasses.name',
				'itemclasses.trims_type_id',
				'uoms.code',
				'budget_trims.description',
				'item_accounts.item_description'
			])
			->where([['budgets.id', '=', $id]])
			->get();
		$sewingtrims = $trims->filter(function ($value) {
			if ($value->trims_type_id == 1 || $value->trims_type_id == 2) {
				return $value;
			}
		});

		$finishingtrims = $trims->filter(function ($value) {
			if ($value->trims_type_id == 3) {
				return $value;
			}
		});

		$budget['sewingtrims'] = $sewingtrims;
		$budget['finishingtrims'] = $finishingtrims;

		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

		$embs = $this->budget
			->selectRaw(
				'embelishments.name as embelishment_name,
		embelishment_types.name as embelishment_type,
		item_accounts.item_description,
		budgets.costing_unit_id,
		style_embelishments.embelishment_id,
		style_embelishments.embelishment_type_id,
		style_embelishments.embelishment_size_id,
		style_embelishments.gmtspart_id,
		gmtsparts.name as gmt_parts_name,
		production_processes.production_area_id,
		budget_embs.id,                
		budget_embs.budget_id,
		budget_embs.style_embelishment_id,
		budget_embs.cons,
		budget_embs.rate,
		budget_embs.amount,
		budget_embs.company_id,
		budget_embs.overhead_rate,
		budget_embs.overhead_amount'
			)
			->leftJoin('budget_embs', function ($join) {
				$join->on('budget_embs.budget_id', '=', 'budgets.id');
			})

			->join('style_embelishments', function ($join) {
				$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
			})
			->leftJoin('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
			})
			->leftJoin('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
			})
			->leftJoin('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_embelishments.style_gmt_id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->leftJoin('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
			})
			->leftJoin('production_processes', function ($join) {
				$join->on('production_processes.id', '=', 'embelishments.production_process_id');
			})
			->where([['budgets.id', '=', $id]])
			->get()
			->map(function ($embs) use ($embelishmentsize) {
				$embs->embelishment_size = $embelishmentsize[$embs->embelishment_size_id];
				return $embs;
			});

		$screenprint = $embs->filter(function ($value) {
			if ($value->production_area_id == 45) {
				return $value;
			}
		});

		$embroidary = $embs->filter(function ($value) {
			if ($value->embelishment_id == 21) {
				return $value;
			}
		});
		$spembroidary = $embs->filter(function ($value) {
			if ($value->embelishment_id == 51) {
				return $value;
			}
		});
		$gmtdyeing = $embs->filter(function ($value) {
			if ($value->embelishment_id == 22) {
				return $value;
			}
		});
		$gmtwashing = $embs->filter(function ($value) {
			if ($value->embelishment_id == 23) {
				return $value;
			}
		});

		$budget['screenprint'] = $screenprint;
		$budget['embroidary'] = $embroidary;
		$budget['spembroidary'] = $spembroidary;
		$budget['gmtdyeing'] = $gmtdyeing;
		$budget['gmtwashing'] = $gmtwashing;

		$sewingdata = $this->budget
			->selectRaw(
				'budgets.id,
			item_accounts.item_description,
			colors.name as gmt_color,
			sizes.name as gmt_size,
			style_gmts.smv,
			style_gmts.sewing_effi_per,
			sum(sales_order_gmt_color_sizes.qty) as gmtqty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id')
					->whereNull('sales_order_gmt_color_sizes.deleted_at');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})
			->groupBy([
				'budgets.id',
				'item_accounts.item_description',
				'colors.name',
				'sizes.name',
				'style_gmts.smv',
				'style_gmts.sewing_effi_per',
				'style_gmts.id',
				'style_colors.id',
				'style_sizes.id',
			])
			->where([['budgets.id', '=', $id]])
			->get();
		$budget['sewings'] = $sewingdata;

		$orderdetails = $this->budget
			->selectRaw(
				'
			jobs.job_no,
			sales_orders.sale_order_no,
			item_accounts.item_description,
			countries.name as country_name,
			companies.code as company_name,
			sales_order_countries.country_ship_date,
			sum(sales_order_gmt_color_sizes.qty) as gmtqty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id')
					->whereNull('sales_order_gmt_color_sizes.deleted_at');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'sales_orders.produced_company_id');
			})
			->groupBy([
				'jobs.id',
				'jobs.job_no',
				'sales_orders.id',
				'sales_orders.sale_order_no',
				'sales_order_countries.id',
				'sales_order_countries.country_ship_date',
				'countries.name',
				'style_gmts.id',
				'item_accounts.item_description',
				'companies.code'
			])
			->where([['budgets.id', '=', $id]])
			->orderBy('sales_order_countries.country_ship_date')
			->get()
			->map(function ($orderdetails) {
				$orderdetails->country_ship_date = date('d-M-Y', strtotime($orderdetails->country_ship_date));
				return $orderdetails;
			});
		$budget['orderdetails'] = $orderdetails;

		$assortment = array_prepend(config('bprs.assortment'), '-Select-', '');
		$cartondetails = $this->budget
			->selectRaw(
				'
			style_pkgs.id,
			style_pkgs.spec,
			style_pkgs.packing_type,
			style_pkgs.assortment,
			style_pkgs.assortment_name,
			style_pkg_ratios.qty,
			item_accounts.item_description,
			colors.name as color_name,
			sizes.name as size_name
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('style_pkgs', function ($join) {
				$join->on('style_pkgs.style_id', '=', 'styles.id');
			})
			->join('style_pkg_ratios', function ($join) {
				$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
			})
			->join('style_gmt_color_sizes', function ($join) {
				$join->on('style_gmt_color_sizes.id', '=', 'style_pkg_ratios.style_gmt_color_size_id');
			})

			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->where([['budgets.id', '=', $id]])
			->get()
			->map(function ($cartondetails) use ($assortment) {
				$cartondetails->assortment = $assortment[$cartondetails->assortment];
				return $cartondetails;
			})
			->groupBy('id');
		$budget['cartondetails'] = $cartondetails;

		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->AddPage();
		$pdf->SetY(10);
		$txt = "Manufacturing Order Sheet";
		$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
		$pdf->SetY(5);
		$pdf->Write(0, 'Manufacturing Order Sheet', '', 0, 'C', true, 0, false, false, 0);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTitle('Manufacturing Order Sheet');

		$view = \View::make('Defult.Bom.MosPdf', ['budget' => $budget]);
		$html_content = $view->render();
		$pdf->SetY(15);
		$pdf->WriteHtml($html_content, true, false, true, false, '');
		$filename = storage_path() . '/MosPdf.pdf';
		$pdf->output($filename);
	}

	public function getMosByShipDate()
	{
		/*$data = [
			'foo' => 'bar'
		];
		$pdf = PDF::loadView('Defult.Bom.MosPdff', $data);
		return $pdf->stream('MosPdff.pdf');

		die;*/
		$id = request('id', 0);
		$incoterm = array_prepend([1 => "FOB", 2 => "CFR", 3 => "CIF"], '-Select-', '');
		$costingunit = array_prepend(config('bprs.costingunit'), '-Select-', '');
		$budgets = array();

		$rows = $this->budget
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->leftJoin('buyers as buyingagent', function ($join) {
				$join->on('buyingagent.id', '=', 'styles.buying_agent_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'jobs.company_id');
			})
			->join('teams', function ($join) {
				$join->on('teams.id', '=', 'styles.team_id');
			})
			->leftJoin('teammembers', function ($join) {
				$join->on('teammembers.id', '=', 'styles.teammember_id');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'teammembers.user_id');
			})

			->leftJoin('teammembers as factory_marchants', function ($join) {
				$join->on('factory_marchants.id', '=', 'styles.factory_merchant_id');
			})

			->leftJoin('users as factory_marchant_names', function ($join) {
				$join->on('factory_marchant_names.id', '=', 'factory_marchants.user_id');
			})

			->leftJoin('employee_h_rs as teammember_details', function ($join) {
				$join->on('teammember_details.user_id', '=', 'users.id');
			})
			->leftJoin('employee_h_rs as factory_marchant_details', function ($join) {
				$join->on('factory_marchant_details.user_id', '=', 'factory_marchant_names.id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'jobs.currency_id');
			})
			->join('uoms', function ($join) {
				$join->on('uoms.id', '=', 'styles.uom_id');
			})
			->join('seasons', function ($join) {
				$join->on('seasons.id', '=', 'styles.season_id');
			})
			->leftJoin('users as createdbys', function ($join) {
				$join->on('createdbys.id', '=', 'budgets.created_by');
			})
			->where([['budgets.id', '=', $id]])
			->get([
				'budgets.*',
				'jobs.id as job_id',
				'jobs.job_no',
				'styles.style_ref',
				'styles.style_ref',
				'buyers.name as buyer_name',
				'buyingagent.name as buying_agent_name',
				'teams.name as team_name',
				'currencies.code as currency_code',
				'companies.code as company_name',
				'companies.name as full_company_name',
				'companies.logo',
				'uoms.code as uom_code',
				'seasons.name as season_name',
				'users.name as tl_marchant',
				'factory_marchant_names.name as dl_marchant',
				'teammember_details.contact',
				'teammember_details.email',
				'factory_marchant_details.contact as contactb',
				'factory_marchant_details.email as emailb',
				'createdbys.name as created_by_name'
			])
			->first();
		$rows->entry_date = date('d-M-Y', strtotime($rows->created_at));

		$rows->costingunit = $costingunit[$rows->costing_unit_id];

		$JobQty = $this->job->totalJobQty($rows->job_id);
		$JobCutQty = $this->job->totalJobCutQty($rows->job_id);
		$JobAmount = $this->job->totalJobAmount($rows->job_id);

		$rows->order_qty = $JobQty;
		$rows->plan_cut_qty = $JobCutQty;
		$rows->order_amount = $JobAmount;
		$rows->selling_price = number_format($JobAmount / $JobQty, 2);


		$budget['master'] = $rows;

		$materialsourcing = array_prepend(config('bprs.materialsourcing'), '-Select-', '');
		$fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
		$fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
		$fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
		$dyetype = array_prepend(config('bprs.dyetype'), '-Select-', '');


		$fabricDescription = $this->budget->selectRaw(
			'style_fabrications.id,
		constructions.name as construction,
		autoyarnratios.composition_id,
		compositions.name,
		autoyarnratios.ratio'
		)
			->leftJoin('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->leftJoin('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('autoyarnratios', function ($join) {
				$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
			})
			->leftJoin('compositions', function ($join) {
				$join->on('compositions.id', '=', 'autoyarnratios.composition_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->where([['budgets.id', '=', $id]])
			->get();
		$fabricDescriptionArr = array();
		$fabricCompositionArr = array();
		foreach ($fabricDescription as $row) {
			$fabricDescriptionArr[$row->id] = $row->construction;
			$fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
		}
		$desDropdown = array();
		foreach ($fabricDescriptionArr as $key => $val) {
			$desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
		}

		$fabrics = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.dyeing_type_id,
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.fabric_color,
			budget_fabric_cons.dia,
			colors.name as fabric_color,
			sales_orders.ship_date,
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(budget_fabric_cons.amount) as amount,
			avg(budget_fabric_cons.rate) as rate
			'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->leftJoin('budget_fabric_cons', function ($join) {
				$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.id', '=', 'budget_fabric_cons.sales_order_gmt_color_size_id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'budget_fabric_cons.fabric_color');
			})
			->where([['budgets.id', '=', $id]])
			->where([['style_fabrications.material_source_id', '=', 10]])
			->where([['style_fabrications.is_narrow', '=', 0]])
			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.dyeing_type_id',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'budget_fabric_cons.fabric_color',
				'budget_fabric_cons.dia',
				'colors.name',
				'sales_orders.ship_date'
			])
			->get()
			->map(function ($fabrics) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $dyetype) {
				$fabrics->fabric_description = isset($desDropdown[$fabrics->style_fabrication_id]) ? $desDropdown[$fabrics->style_fabrication_id] : '';
				$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
				$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
				$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
				$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
				$fabrics->dyetype = $dyetype[$fabrics->dyeing_type_id];
				$fabrics->ship_date = date('d-M-Y', strtotime($fabrics->ship_date));
				return $fabrics;
			})
			->groupBy('ship_date');

		$narrowfabrics = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.dyeing_type_id,
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.dia,
			budget_fabric_cons.measurment,
			colors.name as fabric_color,
			sales_orders.ship_date,
			gmtcolors.name as gmt_color,
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(budget_fabric_cons.amount) as amount,
			avg(budget_fabric_cons.rate) as rate
			'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->leftJoin('budget_fabric_cons', function ($join) {
				$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.id', '=', 'budget_fabric_cons.sales_order_gmt_color_size_id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
			})
			->leftJoin('style_gmt_color_sizes', function ($join) {
				$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
			})
			->leftJoin('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
			})
			->leftJoin('colors as gmtcolors', function ($join) {
				$join->on('gmtcolors.id', '=', 'style_colors.color_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'budget_fabric_cons.fabric_color');
			})
			->where([['budgets.id', '=', $id]])
			->where([['style_fabrications.material_source_id', '=', 10]])
			->where([['style_fabrications.is_narrow', '=', 1]])
			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.dyeing_type_id',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'budget_fabric_cons.fabric_color',
				'budget_fabric_cons.dia',
				'budget_fabric_cons.measurment',
				'gmtcolors.name',
				'style_colors.id',
				'colors.name',
				'sales_orders.ship_date'
			])
			->get()
			->map(function ($fabrics) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $dyetype) {
				$fabrics->fabric_description = isset($desDropdown[$fabrics->style_fabrication_id]) ? $desDropdown[$fabrics->style_fabrication_id] : '';
				$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
				$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
				$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
				$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
				$fabrics->dyetype = $dyetype[$fabrics->dyeing_type_id];
				$fabrics->ship_date = date('d-M-Y', strtotime($fabrics->ship_date));
				return $fabrics;
			})->groupBy('ship_date');
		$budget['fabrics'] = ['main' => $fabrics, 'narrow' => $narrowfabrics];




		$yarnDescription = $this->itemaccount
			->join('item_account_ratios', function ($join) {
				$join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
			})
			->join('yarncounts', function ($join) {
				$join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
			})
			->join('yarntypes', function ($join) {
				$join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
			})
			->join('itemclasses', function ($join) {
				$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
			})
			->join('compositions', function ($join) {
				$join->on('compositions.id', '=', 'item_account_ratios.composition_id');
			})
			->join('itemcategories', function ($join) {
				$join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
			})
			->where([['itemcategories.identity', '=', 1]])
			->get([
				'item_accounts.id',
				'yarncounts.count',
				'yarncounts.symbol',
				'yarntypes.name as yarn_type',
				'itemclasses.name as itemclass_name',
				'compositions.name as composition_name',
				'item_account_ratios.ratio',
			]);
		$itemaccountArr = array();
		$yarnCompositionArr = array();
		foreach ($yarnDescription as $row) {
			$itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
			$itemaccountArr[$row->id]['yarn_type'] = $row->yarn_type;
			$itemaccountArr[$row->id]['itemclass_name'] = $row->itemclass_name;
			$yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
		}
		$yarnDropdown = array();
		foreach ($itemaccountArr as $key => $value) {
			$yarnDropdown[$key] = implode(",", $yarnCompositionArr[$key]);
		}


		$yarn = $this->budget
			->selectRaw(
				'budgets.id,
		item_accounts.id as item_account_id,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		suppliers.name as supplier_name,
		sum(budget_yarns.cons) as cons,
		avg(budget_yarns.rate) as rate,
		sum(budget_yarns.amount) as amount
		'
			)
			->leftJoin('budget_yarns', function ($join) {
				$join->on('budget_yarns.budget_id', '=', 'budgets.id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'budget_yarns.item_account_id');
			})
			->leftJoin('yarncounts', function ($join) {
				$join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
			})
			->leftJoin('yarntypes', function ($join) {
				$join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
			})
			->leftJoin('suppliers', function ($join) {
				$join->on('suppliers.id', '=', 'budget_yarns.supplier_id');
			})
			->groupBy([
				'budgets.id',
				'item_accounts.id',
				'yarncounts.count',
				'yarncounts.symbol',
				'yarntypes.name',
				'suppliers.name'
			])
			->where([['budgets.id', '=', $id]])
			->get()
			->map(function ($yarn) use ($yarnDropdown) {
				$yarn->composition = isset($yarnDropdown[$yarn->item_account_id]) ? $yarnDropdown[$yarn->item_account_id] : '';
				return $yarn;
			});
		$budget['yarns'] = $yarn;


		$aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-', '');
		$aops = $this->budget
			->selectRaw(
				'style_fabrications.id as style_fabrication_id,
		style_fabrications.material_source_id,
		style_fabrications.fabric_nature_id,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		style_fabrications.is_narrow,
		style_fabrications.embelishment_type_id,
		style_fabrications.coverage,
		style_fabrications.impression,
		autoyarns.id,
		item_accounts.item_description,
		uoms.code as uom_name,
		constructions.name as constructions_name,
		budget_fabrics.id,
		budget_fabrics.gsm_weight,
		colors.name as fabric_color,
		budget_fabric_prods.production_process_id,
		production_processes.process_name,
		sum(budget_fabric_prod_cons.bom_qty) as fin_fab
		'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->join('budget_fabric_prods', function ($join) {
				$join->on('budget_fabric_prods.budget_id', '=', 'budgets.id');
				$join->on('budget_fabric_prods.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('budget_fabric_prod_cons', function ($join) {
				$join->on('budget_fabric_prod_cons.budget_fabric_prod_id', '=', 'budget_fabric_prods.id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'budget_fabric_prod_cons.fabric_color_id');
			})
			->join('production_processes', function ($join) {
				$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
			})
			->where([['budgets.id', '=', $id]])
			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.embelishment_type_id',
				'style_fabrications.coverage',
				'style_fabrications.impression',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'colors.name',
				'budget_fabric_prods.production_process_id',
				'production_processes.process_name',
			])
			->get()
			->map(function ($aops) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $aoptype, $dyetype) {
				$aops->fabric_description = isset($desDropdown[$aops->style_fabrication_id]) ? $desDropdown[$aops->style_fabrication_id] : '';
				$aops->materialsourcing = $materialsourcing[$aops->material_source_id];
				$aops->fabricnature = $fabricnature[$aops->fabric_nature_id];
				$aops->fabriclooks = $fabriclooks[$aops->fabric_look_id];
				$aops->fabricshape = $fabricshape[$aops->fabric_shape_id];
				$aops->aoptype = $aoptype[$aops->embelishment_type_id];
				$aops->dyeing_type = $dyetype[$aops->dyeing_type_id];
				return $aops;
			});

		$prods = $aops
			->filter(function ($value) {
				if ($value->production_process_id != 61 || $value->production_process_id != 21) {
					return $value;
				}
			})
			->groupBy('production_process_id');

		$aop = $aops->filter(function ($value) {
			if ($value->production_process_id == 61) {
				return $value;
			}
		});
		$burnout = $aops->filter(function ($value) {
			if ($value->production_process_id == 21) {
				return $value;
			}
		});

		$budget['aop'] = $aop;
		$budget['burnout'] = $burnout;
		$budget['fabricProd'] = $prods;



		$yarnDyeingDescription = $this->budget
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->join('autoyarnratios', function ($join) {
				$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
			})
			->join('compositions', function ($join) {
				$join->on('compositions.id', '=', 'autoyarnratios.composition_id');
			})
			->join('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->join('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->where([['budgets.id', '=', $id]])
			->groupBy([
				'budget_fabrics.id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'item_accounts.item_description',
				'gmtsparts.name',
				'autoyarnratios.composition_id',
				'constructions.name',
				'compositions.name',
				'autoyarnratios.ratio',
			])
			->get([
				'budget_fabrics.id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'gmtsparts.name as gmtspart_name',
				'item_accounts.item_description',
				'autoyarnratios.composition_id',
				'constructions.name as construction',
				'compositions.name',
				'autoyarnratios.ratio',
			]);
		$yarndyeingDescriptionArr = array();
		$yarndyeingCompositionArr = array();
		foreach ($yarnDyeingDescription as $row) {
			$yarndyeingDescriptionArr[$row->id] = $row->item_description . " " . $row->gmtspart_name . " " . $fabricnature[$row->fabric_nature_id] . " " . $fabriclooks[$row->fabric_look_id] . " " . $fabricshape[$row->fabric_shape_id] . " " . $row->construction;
			$yarndyeingCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
		}
		$yarndyeingdesDropdown = array();
		foreach ($yarndyeingDescriptionArr as $key => $val) {
			$yarndyeingdesDropdown[$key] = $val . " " . implode(",", $yarndyeingCompositionArr[$key]);
		}



		$yarnDyeing = $this->budget->yarnDyeingCost($id)
			->map(function ($yarnDyeing) use ($yarndyeingdesDropdown) {
				$yarnDyeing->process_id = $yarnDyeing->process_name;
				$yarnDyeing->total_amount = $yarnDyeing->amount + $yarnDyeing->overhead_amount;
				$yarnDyeing->budgetfabric = $yarndyeingdesDropdown[$yarnDyeing->budget_fabric_id];
				return $yarnDyeing;
			});
		$mktcostyarndyeing = $yarnDyeing->groupBy('process_id');
		$budget['yarndyeing'] = $mktcostyarndyeing;

		$cuttings = $this->budget
			->selectRaw(
				'
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			style_fabrications.dyeing_type_id,
			autoyarns.id,
			item_accounts.item_description,
			uoms.code as uom_name,
			constructions.name as constructions_name,
			budget_fabrics.id,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.fabric_color,
			budget_fabric_cons.dia,
			colors.name as gmt_color,
			sizes.name as gmt_size,
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(budget_fabric_cons.amount) as amount,
			avg(budget_fabric_cons.rate) as rate,
			avg(budget_fabric_cons.cons) as cons,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'budgets.style_id');
			})
			->join('style_fabrications', function ($join) {
				$join->on('style_fabrications.style_id', '=', 'budgets.style_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
			})
			->leftJoin('autoyarns', function ($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function ($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'style_fabrications.uom_id');
			})
			->leftJoin('budget_fabrics', function ($join) {
				$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
				$join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
			})
			->leftJoin('budget_fabric_cons', function ($join) {
				$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.id', '=', 'budget_fabric_cons.sales_order_gmt_color_size_id');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->leftJoin('colors as fabric_colors', function ($join) {
				$join->on('fabric_colors.id', '=', 'budget_fabric_cons.fabric_color');
			})
			->where([['budgets.id', '=', $id]])
			->where([['style_fabrications.is_narrow', '=', 0]])
			->where([['style_fabrications.material_source_id', '=', 10]])

			->groupBy([
				'style_fabrications.id',
				'style_fabrications.material_source_id',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'style_fabrications.dyeing_type_id',
				'autoyarns.id',
				'item_accounts.item_description',
				'uoms.code',
				'constructions.name',
				'budget_fabrics.id',
				'budget_fabrics.gsm_weight',
				'budget_fabric_cons.fabric_color',
				'budget_fabric_cons.dia',
				'style_colors.id',
				'style_sizes.id',
				'colors.name',
				'sizes.name'
			])
			->orderBy('style_fabrications.id')
			->get()
			->map(function ($fabrics) use ($desDropdown, $materialsourcing, $fabricnature, $fabriclooks, $fabricshape, $dyetype) {
				$fabrics->fabric_description = isset($desDropdown[$fabrics->style_fabrication_id]) ? $desDropdown[$fabrics->style_fabrication_id] : '';
				$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
				$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
				$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
				$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
				$fabrics->dyetype = $dyetype[$fabrics->dyeing_type_id];
				return $fabrics;
			});

		$budget['cuttings'] = $cuttings;

		$trims = $this->budget
			->selectRaw(
				'budgets.id,
			itemclasses.name,
			itemclasses.trims_type_id,
			uoms.code,
			budget_trims.description,
			item_accounts.item_description,
			sum(budget_trim_cons.bom_trim) as bom_trim,
			avg(budget_trim_cons.cons) as cons,
			sum(sales_order_gmt_color_sizes.qty) as gmtqty
			'
			)

			->leftJoin('budget_trims', function ($join) {
				$join->on('budget_trims.budget_id', '=', 'budgets.id');
			})
			->leftJoin('itemclasses', function ($join) {
				$join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
			})
			->leftJoin('itemcategories', function ($join) {
				$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'budget_trims.uom_id');
			})
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
			})

			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id')
					->whereNull('sales_order_gmt_color_sizes.deleted_at');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})

			->leftJoin('budget_trim_cons', function ($join) {
				$join->on('budget_trims.id', '=', 'budget_trim_cons.budget_trim_id')
					->on('sales_order_gmt_color_sizes.id', '=', 'budget_trim_cons.sales_order_gmt_color_size_id')
					->whereNull('budget_trim_cons.deleted_at');
			})
			->groupBy([
				'budgets.id',
				'itemclasses.name',
				'itemclasses.trims_type_id',
				'uoms.code',
				'budget_trims.description',
				'item_accounts.item_description'
			])
			->where([['budgets.id', '=', $id]])
			->get();
		$sewingtrims = $trims->filter(function ($value) {
			if ($value->trims_type_id == 1 || $value->trims_type_id == 2) {
				return $value;
			}
		});

		$finishingtrims = $trims->filter(function ($value) {
			if ($value->trims_type_id == 3) {
				return $value;
			}
		});

		$budget['sewingtrims'] = $sewingtrims;
		$budget['finishingtrims'] = $finishingtrims;

		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

		$embs = $this->budget
			->selectRaw(
				'embelishments.name as embelishment_name,
		embelishment_types.name as embelishment_type,
		item_accounts.item_description,
		budgets.costing_unit_id,
		style_embelishments.embelishment_size_id,
		style_embelishments.gmtspart_id,
		gmtsparts.name as gmt_parts_name,
		production_processes.production_area_id,
		budget_embs.*'
			)
			->leftJoin('budget_embs', function ($join) {
				$join->on('budget_embs.budget_id', '=', 'budgets.id');
			})

			->join('style_embelishments', function ($join) {
				$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
			})
			->leftJoin('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
			})
			->leftJoin('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
			})
			->leftJoin('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_embelishments.style_gmt_id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->leftJoin('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
			})
			->leftJoin('production_processes', function ($join) {
				$join->on('production_processes.id', '=', 'embelishments.production_process_id');
			})
			->where([['budgets.id', '=', $id]])
			->get()
			->map(function ($embs) use ($embelishmentsize) {
				$embs->embelishment_size = $embelishmentsize[$embs->embelishment_size_id];
				return $embs;
			});

		$screenprint = $embs->filter(function ($value) {
			if ($value->production_area_id == 45) {
				return $value;
			}
		});

		$embroidary = $embs->filter(function ($value) {
			if ($value->embelishment_id == 50) {
				return $value;
			}
		});
		$spembroidary = $embs->filter(function ($value) {
			if ($value->embelishment_id == 51) {
				return $value;
			}
		});
		$gmtdyeing = $embs->filter(function ($value) {
			if ($value->embelishment_id == 58) {
				return $value;
			}
		});
		$gmtwashing = $embs->filter(function ($value) {
			if ($value->embelishment_id == 60) {
				return $value;
			}
		});

		$budget['screenprint'] = $screenprint;
		$budget['embroidary'] = $embroidary;
		$budget['spembroidary'] = $spembroidary;
		$budget['gmtdyeing'] = $gmtdyeing;
		$budget['gmtwashing'] = $gmtwashing;

		$sewingdata = $this->budget
			->selectRaw(
				'budgets.id,
			item_accounts.item_description,
			colors.name as gmt_color,
			sizes.name as gmt_size,
			style_gmts.smv,
			style_gmts.sewing_effi_per,
			sum(sales_order_gmt_color_sizes.qty) as gmtqty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id')
					->whereNull('sales_order_gmt_color_sizes.deleted_at');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})
			->groupBy([
				'budgets.id',
				'item_accounts.item_description',
				'colors.name',
				'sizes.name',
				'style_gmts.smv',
				'style_gmts.sewing_effi_per',
				'style_gmts.id',
				'style_colors.id',
				'style_sizes.id',
			])
			->where([['budgets.id', '=', $id]])
			->get();

		$budget['sewings'] = $sewingdata;

		$orderdetails = $this->budget
			->selectRaw(
				'
			jobs.job_no,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			sales_orders.ship_date,
			item_accounts.item_description,
			countries.name as country_name,
			companies.code as company_name,
			sales_order_countries.country_ship_date,
			sum(sales_order_gmt_color_sizes.qty) as gmtqty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id')
					->whereNull('sales_order_gmt_color_sizes.deleted_at');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'sales_order_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'sales_orders.produced_company_id');
			})
			->groupBy([
				'jobs.id',
				'jobs.job_no',
				'sales_orders.id',
				'sales_orders.sale_order_no',
				'sales_orders.ship_date',
				'sales_order_countries.id',
				'sales_order_countries.country_ship_date',
				'countries.name',
				'style_gmts.id',
				'item_accounts.item_description',
				'companies.code'
			])
			->where([['budgets.id', '=', $id]])
			->orderBy('sales_order_countries.country_ship_date')
			->get()
			->map(function ($orderdetails) {
				$orderdetails->country_ship_date = date('d-M-Y', strtotime($orderdetails->country_ship_date));
				$orderdetails->ship_date = date('d-M-Y', strtotime($orderdetails->ship_date));
				return $orderdetails;
			});
		$budget['orderdetails'] = $orderdetails;
		$shipDatePo = [];
		foreach ($orderdetails as $orderdetail) {
			$shipDatePo[$orderdetail->ship_date][$orderdetail->sales_order_id] = $orderdetail->sale_order_no;
		}
		//print_r($shipDatePo);

		$assortment = array_prepend(config('bprs.assortment'), '-Select-', '');
		$cartondetails = $this->budget
			->selectRaw(
				'
			style_pkgs.id,
			style_pkgs.spec,
			style_pkgs.packing_type,
			style_pkgs.assortment,
			style_pkgs.assortment_name,
			style_pkg_ratios.qty,
			item_accounts.item_description,
			colors.name as color_name,
			sizes.name as size_name
			'
			)
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'budgets.job_id');
			})
			->join('styles', function ($join) {
				$join->on('styles.id', '=', 'jobs.style_id');
			})
			->join('style_pkgs', function ($join) {
				$join->on('style_pkgs.style_id', '=', 'styles.id');
			})
			->join('style_pkg_ratios', function ($join) {
				$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
			})
			->join('style_gmt_color_sizes', function ($join) {
				$join->on('style_gmt_color_sizes.id', '=', 'style_pkg_ratios.style_gmt_color_size_id');
			})

			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->where([['budgets.id', '=', $id]])
			->get()
			->map(function ($cartondetails) use ($assortment) {
				$cartondetails->assortment = $assortment[$cartondetails->assortment];
				return $cartondetails;
			})
			->groupBy('id');

		$budget['cartondetails'] = $cartondetails;

		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->AddPage();
		$pdf->SetY(10);
		$txt = "Manufacturing Order Sheet";
		$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
		$pdf->SetY(5);
		$pdf->Write(0, 'Manufacturing Order Sheet', '', 0, 'C', true, 0, false, false, 0);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTitle('Manufacturing Order Sheet');

		$view = \View::make('Defult.Bom.MosByShipDatePdf', ['budget' => $budget, 'shipDatePo' => $shipDatePo]);
		$html_content = $view->render();
		$pdf->SetY(15);
		$pdf->WriteHtml($html_content, true, false, true, false, '');
		$filename = storage_path() . '/MosPdf.pdf';
		$pdf->output($filename);
	}
}
