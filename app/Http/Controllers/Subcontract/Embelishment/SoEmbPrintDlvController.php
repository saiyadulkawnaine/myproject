<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintDlvRequest;

class SoEmbPrintDlvController extends Controller
{
	private $soembprintdlv;
	private $company;
	private $buyer;
	private $supplier;
	private $store;
	private $gmtspart;
	private $itemaccount;
	private $location;
	private $currency;
	private $designation;

	public function __construct(
		SoEmbPrintDlvRepository $soembprintdlv,
		CompanyRepository $company,
		BuyerRepository $buyer,
		LocationRepository $location,
		SupplierRepository $supplier,
		StoreRepository $store,
		GmtspartRepository $gmtspart,
		ItemAccountRepository $itemaccount,
		CurrencyRepository $currency,
		DesignationRepository $designation
	) {
		$this->soembprintdlv = $soembprintdlv;
		$this->company = $company;
		$this->buyer = $buyer;
		$this->location = $location;
		$this->supplier = $supplier;
		$this->store = $store;
		$this->gmtspart = $gmtspart;
		$this->itemaccount = $itemaccount;
		$this->currency = $currency;
		$this->designation = $designation;
		$this->middleware('auth');

		/*$this->middleware('permission:view.soembprintdlvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soembprintdlvs', ['only' => ['store']]);
        $this->middleware('permission:edit.soembprintdlvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.soembprintdlvs', ['only' => ['destroy']]); */
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');
		$rows = $this->soembprintdlv
			->leftJoin('companies', function ($join) {
				$join->on('so_emb_print_dlvs.company_id', '=', 'companies.id');
			})
			->leftJoin('buyers', function ($join) {
				$join->on('so_emb_print_dlvs.buyer_id', '=', 'buyers.id');
			})
			->leftJoin('currencies', function ($join) {
				$join->on('so_emb_print_dlvs.currency_id', '=', 'currencies.id');
			})
			->where([['so_emb_print_dlvs.is_self', '=', 1]])
			->orderBy('so_emb_print_dlvs.id', 'desc')
			->get([
				'so_emb_print_dlvs.*',
				'companies.name as company_name',
				'buyers.name as buyer_name',
				'currencies.name as currency_name'
			])->map(function ($rows) use ($productionarea) {
				$rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));
				$rows->production_area_id = isset($productionarea[$rows->production_area_id]) ? $productionarea[$rows->production_area_id] : '';
				return $rows;
			});
		return response()->json($rows);
		//echo json_encode($rows);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
		$buyer = array_prepend(array_pluck($this->buyer->whereNull('buyers.company_id')->get(), 'name', 'id'), '-Select-', '');
		$supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
		$store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
		$location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
		$productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');
		$currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
		return Template::loadView('Subcontract.Embelishment.SoEmbPrintDlv', [
			'company' => $company,
			'buyer' => $buyer,
			'location' => $location,
			'supplier' => $supplier,
			'store' => $store,
			'productionarea' => $productionarea,
			'currency' => $currency
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(SoEmbPrintDlvRequest $request)
	{
		$max = $this->soembprintdlv->max('dlv_no');
		$dlv_no = $max + 1;
		$soembprintdlv = $this->soembprintdlv->create([
			'dlv_no' => $dlv_no,
			'company_id' => $request->company_id,
			'buyer_id' => $request->buyer_id,
			'production_area_id' => $request->production_area_id,
			'currency_id' => $request->currency_id,
			'dlv_date' => $request->dlv_date,
			'driver_name' => $request->driver_name,
			'driver_contact_no' => $request->driver_contact_no,
			'driver_license_no' => $request->driver_license_no,
			'lock_no' => $request->lock_no,
			'truck_no' => $request->truck_no,
			'remarks' => $request->remarks,
			'is_self' => 1
		]);
		if ($soembprintdlv) {
			return response()->json(array('success' => true, 'id' =>  $soembprintdlv->id, 'message' => 'Save Successfully'), 200);
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
		$soembprintdlv = $this->soembprintdlv->find($id);
		$soembprintdlv->dlv_date = date('Y-m-d', strtotime($soembprintdlv->dlv_date));
		$row['fromData'] = $soembprintdlv;
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
	public function update(SoEmbPrintDlvRequest $request, $id)
	{
		$soembprintdlv = $this->soembprintdlv->update($id, [
			'company_id' => $request->company_id,
			'buyer_id' => $request->buyer_id,
			'production_area_id' => $request->production_area_id,
			'currency_id' => $request->currency_id,
			'dlv_date' => $request->dlv_date,
			'driver_name' => $request->driver_name,
			'driver_contact_no' => $request->driver_contact_no,
			'driver_license_no' => $request->driver_license_no,
			'lock_no' => $request->lock_no,
			'truck_no' => $request->truck_no,
			'remarks' => $request->remarks,
		]);
		if ($soembprintdlv) {
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
		if ($this->soembprintdlv->delete($id)) {
			return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
		}
	}

	public function getBillPdf()
	{
		$id = request('id', 0);

		$designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');
		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

		$rows = $this->soembprintdlv
			->join('so_emb_print_dlv_items', function ($join) {
				$join->on('so_emb_print_dlvs.id', '=', 'so_emb_print_dlv_items.so_emb_print_dlv_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'so_emb_print_dlvs.company_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'so_emb_print_dlvs.buyer_id');
			})
			->leftJoin('buyer_branches', function ($join) {
				$join->on('buyer_branches.buyer_id', '=', 'buyers.id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'so_emb_print_dlvs.currency_id');
			})
			->leftJoin('users as createdby_user', function ($join) {
				$join->on('createdby_user.id', '=', 'so_emb_print_dlvs.created_by');
			})
			->leftJoin('employee_h_rs as createdby_employee', function ($join) {
				$join->on('createdby_user.id', '=', 'createdby_employee.user_id');
			})
			->leftJoin('users as approvedby_user', function ($join) {
				$join->on('approvedby_user.id', '=', 'so_emb_print_dlvs.approved_by');
			})
			->leftJoin('employee_h_rs as approvedby_employee', function ($join) {
				$join->on('approvedby_user.id', '=', 'approvedby_employee.user_id');
			})
			->where([['so_emb_print_dlvs.id', '=', $id]])
			->get([
				'so_emb_print_dlvs.*',
				'companies.name as company_name',
				'companies.logo as logo',
				'companies.address as company_address',
				'buyers.name as buyer_name',
				'buyer_branches.address as buyer_address',
				'currencies.code as currency_code',
				'currencies.hundreds_name',

				'createdby_user.signature_file as createdby_signature',
				'createdby_employee.name as createdby_user_name',
				'createdby_employee.contact as createdby_contact',
				'createdby_employee.designation_id as createdby_designation_id',

				'approvedby_user.signature_file as approvedby_signature',
				'approvedby_employee.name as approvedby_user_name',
				'approvedby_employee.contact as approvedby_contact',
				'approvedby_employee.designation_id as approvedby_designation_id',
			])
			->map(function ($rows) use ($designation) {
				$rows->dlv_date = date('d-M-y', strtotime($rows->dlv_date));
				$rows->createdby_designation = $rows->createdby_designation_id ? $designation[$rows->createdby_designation_id] : null;
				$rows->createdby_signature = $rows->createdby_signature ? 'images/signature/' . $rows->createdby_signature : null;
				$rows->approvedby_designation = $rows->approvedby_designation_id ? $designation[$rows->approvedby_designation_id] : null;
				$rows->approvedby_signature = $rows->approvedby_signature ? 'images/signature/' . $rows->approvedby_signature : null;
				return $rows;
			})
			->first();

		$soembprintdlvitem = $this->soembprintdlv
			->join('so_emb_print_dlv_items', function ($join) {
				$join->on('so_emb_print_dlvs.id', '=', 'so_emb_print_dlv_items.so_emb_print_dlv_id');
			})
			->join('so_emb_cutpanel_rcv_qties', function ($join) {
				$join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_dlv_items.so_emb_cutpanel_rcv_qty_id');
			})
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
			})
			->join('so_embs', function ($join) {
				$join->on('so_embs.id', '=', 'so_emb_refs.so_emb_id');
			})
			->join('so_emb_items', function ($join) {
				$join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
			})
			->join('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'so_emb_items.embelishment_id');
			})
			->join('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'so_emb_items.embelishment_type_id');
			})
			->leftJoin('buyers', function ($join) {
				$join->on('buyers.id', '=', 'so_emb_items.gmt_buyer');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'so_emb_items.uom_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'so_emb_items.color_id');
			})
			->leftJoin('sizes', function ($join) {
				$join->on('sizes.id', '=', 'so_emb_items.size_id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
			})
			->where([['so_emb_print_dlvs.id', '=', $id]])
			->orderBy('so_emb_print_dlv_items.id', 'desc')
			->get([
				'so_emb_print_dlv_items.*',
				'so_emb_cutpanel_rcv_qties.design_no',
				'embelishments.name as emb_name',
				'embelishment_types.name as emb_type',
				'gmtsparts.name as gmtspart',
				'so_emb_items.embelishment_size_id',
				'so_emb_items.rate',
				'so_emb_items.gmt_style_ref as style_ref',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'buyers.name as buyer_name',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'sizes.name as gmt_size'
			])
			->map(function ($rows) use ($embelishmentsize) {
				$rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
				$rows->uom_name = 'Pcs';
				$rows->qty = number_format($rows->qty, 0, '.', '');
				$rows->rate = number_format($rows->rate, 4, '.', '');
				$rows->amount = number_format($rows->amount, 2, '.', '');
				return $rows;
			});

		$amount = $soembprintdlvitem->sum('amount');
		$inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, $rows->hundreds_name);
		$rows->inword = $inword;

		$data['master']    = $rows;
		$data['details']   = $soembprintdlvitem;

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
		$pdf->SetFont('helvetica', '', 10);
		$pdf->AddPage();
		$image_file = 'images/logo/' . $data['master']->logo;
		$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
		$pdf->SetY(12);
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->Cell(0, 40, $data['master']->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$pdf->SetFont('helvetica', 'N', 8);
		$barcodestyle = array(
			'position' => '',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 4
		);

		$challan = str_pad($id, 10, 0, STR_PAD_LEFT);
		$pdf->SetY(5);
		$pdf->SetX(150);
		$pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
		$pdf->SetY(18);
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->Cell(0, 40, 'Screen Printing Bill', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$pdf->SetY(45);
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->SetTitle('Screen Printing Bill');
		$view = \View::make('Defult.Subcontract.Embelishment.SoEmbPrintDlvBillPdf', ['data' => $data]);
		$html_content = $view->render();
		//$pdf->SetY(55);
		$pdf->WriteHtml($html_content, true, false, true, false, '');
		$pdf->SetFont('helvetica', '', 8);
		$filename = storage_path() . '/SoEmbPrintDlvBillPdf.pdf';
		$pdf->output($filename, 'I');
		exit();
	}

	public function getChallan()
	{
		$id = request('id', 0);

		$designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');
		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

		$rows = $this->soembprintdlv
			->join('so_emb_print_dlv_items', function ($join) {
				$join->on('so_emb_print_dlvs.id', '=', 'so_emb_print_dlv_items.so_emb_print_dlv_id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'so_emb_print_dlvs.company_id');
			})
			->join('buyers', function ($join) {
				$join->on('buyers.id', '=', 'so_emb_print_dlvs.buyer_id');
			})
			->leftJoin('buyer_branches', function ($join) {
				$join->on('buyer_branches.buyer_id', '=', 'buyers.id');
			})
			->join('currencies', function ($join) {
				$join->on('currencies.id', '=', 'so_emb_print_dlvs.currency_id');
			})
			->leftJoin('users as createdby_user', function ($join) {
				$join->on('createdby_user.id', '=', 'so_emb_print_dlvs.created_by');
			})
			->leftJoin('employee_h_rs as createdby_employee', function ($join) {
				$join->on('createdby_user.id', '=', 'createdby_employee.user_id');
			})
			->leftJoin('users as approvedby_user', function ($join) {
				$join->on('approvedby_user.id', '=', 'so_emb_print_dlvs.approved_by');
			})
			->leftJoin('employee_h_rs as approvedby_employee', function ($join) {
				$join->on('approvedby_user.id', '=', 'approvedby_employee.user_id');
			})
			->where([['so_emb_print_dlvs.id', '=', $id]])
			->get([
				'so_emb_print_dlvs.*',
				'companies.name as company_name',
				'companies.logo as logo',
				'companies.address as company_address',
				'buyers.name as buyer_name',
				'buyer_branches.address as buyer_address',
				'currencies.code as currency_code',
				'currencies.hundreds_name',

				'createdby_user.signature_file as createdby_signature',
				'createdby_employee.name as createdby_user_name',
				'createdby_employee.contact as createdby_contact',
				'createdby_employee.designation_id as createdby_designation_id',
			])
			->map(function ($rows) use ($designation) {
				$rows->dlv_date = date('d-M-y', strtotime($rows->dlv_date));
				$rows->createdby_designation = $rows->createdby_designation_id ? $designation[$rows->createdby_designation_id] : null;
				$rows->createdby_signature = $rows->createdby_signature ? 'images/signature/' . $rows->createdby_signature : null;
				$rows->approvedby_designation = $rows->approvedby_designation_id ? $designation[$rows->approvedby_designation_id] : null;
				$rows->approvedby_signature = $rows->approvedby_signature ? 'images/signature/' . $rows->approvedby_signature : null;
				return $rows;
			})
			->first();

		$soembprintdlvitem = $this->soembprintdlv
			->join('so_emb_print_dlv_items', function ($join) {
				$join->on('so_emb_print_dlvs.id', '=', 'so_emb_print_dlv_items.so_emb_print_dlv_id');
			})
			->join('so_emb_cutpanel_rcv_qties', function ($join) {
				$join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_dlv_items.so_emb_cutpanel_rcv_qty_id');
			})
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
			})
			->join('so_embs', function ($join) {
				$join->on('so_embs.id', '=', 'so_emb_refs.so_emb_id');
			})
			->join('so_emb_items', function ($join) {
				$join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
			})
			->join('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'so_emb_items.embelishment_id');
			})
			->join('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'so_emb_items.embelishment_type_id');
			})
			->leftJoin('buyers', function ($join) {
				$join->on('buyers.id', '=', 'so_emb_items.gmt_buyer');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'so_emb_items.uom_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'so_emb_items.color_id');
			})
			->leftJoin('sizes', function ($join) {
				$join->on('sizes.id', '=', 'so_emb_items.size_id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
			})
			->where([['so_emb_print_dlvs.id', '=', $id]])
			->orderBy('so_emb_print_dlv_items.id', 'desc')
			->get([
				'so_emb_print_dlv_items.*',
				'so_emb_cutpanel_rcv_qties.design_no',
				'embelishments.name as emb_name',
				'embelishment_types.name as emb_type',
				'gmtsparts.name as gmtspart',
				'so_emb_items.embelishment_size_id',
				'so_emb_items.rate',
				'so_emb_items.gmt_style_ref as style_ref',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'buyers.name as buyer_name',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'sizes.name as gmt_size'
			])
			->map(function ($rows) use ($embelishmentsize) {
				$rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
				$rows->uom_name = 'Pcs';
				$rows->qty = number_format($rows->qty, 0, '.', '');
				$rows->rate = number_format($rows->rate, 4, '.', '');
				$rows->amount = number_format($rows->amount, 2, '.', '');
				return $rows;
			});

		$amount = $soembprintdlvitem->sum('amount');
		$inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, $rows->hundreds_name);
		$rows->inword = $inword;

		$data['master']    = $rows;
		$data['details']   = $soembprintdlvitem;

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
		$pdf->SetFont('helvetica', '', 10);
		$pdf->AddPage();
		$image_file = 'images/logo/' . $data['master']->logo;
		$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
		$pdf->SetY(12);
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->Cell(0, 40, $data['master']->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$pdf->SetFont('helvetica', 'N', 8);
		$barcodestyle = array(
			'position' => '',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 4
		);

		$challan = str_pad($id, 10, 0, STR_PAD_LEFT);
		$pdf->SetY(5);
		$pdf->SetX(150);
		$pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
		$pdf->SetY(18);
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->Cell(0, 40, 'Screen Printing Challan', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$pdf->SetY(45);
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->SetTitle('Screen Printing Challan');
		$view = \View::make('Defult.Subcontract.Embelishment.SoEmbPrintDlvChallanPdf', ['data' => $data]);
		$html_content = $view->render();
		//$pdf->SetY(55);
		$pdf->WriteHtml($html_content, true, false, true, false, '');
		$pdf->SetFont('helvetica', '', 8);
		$filename = storage_path() . '/SoEmbPrintDlvChallanPdf.pdf';
		$pdf->output($filename, 'I');
		exit();
	}
}
