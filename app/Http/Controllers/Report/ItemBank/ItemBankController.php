<?php

namespace App\Http\Controllers\Report\ItemBank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
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
use App\Repositories\Contracts\Util\CountryRepository;

class ItemBankController extends Controller
{
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

    public function __construct(ItemAccountRepository $itemaccount,ItemcategoryRepository $itemcategory,ItemclassRepository $itemclass,YarncountRepository $yarncount,YarntypeRepository $yarntype,CompositionRepository $composition,ColorRepository $color,SizeRepository $size,UomRepository $uom,SupplierRepository $supplier,CountryRepository $country ) {
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
        $this->country      = $country;
		$this->middleware('auth');

		//$this->middleware('permission:view.itembankreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
        $itemcategory=array_prepend(array_pluck($this->itemcategory->orderBy('name','asc')->get(),'name','id'),'-Select-','');
      return Template::loadView('Report.ItemBank.ItemBank',['itemcategory'=>$itemcategory]);
	 }
	 
	public function reportData() {

        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        //$feature=array_prepend(config('bprs.supplyfeature'),'-Select-','');

        $item=array_prepend(array_pluck($this->itemaccount->orderBy('item_description','asc')->get(),'item_description','id'),'-Select-','');

        $feature=$this->itemaccount
        ->join('item_account_suppliers', function($join)  {
            $join->on('item_account_suppliers.item_account_id', '=', 'item_accounts.id');
        })
        ->join('item_account_supplier_feats', function($join)  {
            $join->on('item_account_supplier_feats.item_account_supplier_id', '=', 'item_account_suppliers.id');
        })
        
        
        ->when(request('itemcategory_id'), function ($q) {
            return $q->where('item_accounts.itemcategory_id', '=', request('itemcategory_id', 0));
        })
        ->get([
            'item_account_suppliers.id',
            'item_account_supplier_feats.feature_point_id',
            'item_account_supplier_feats.available_id',
            'item_account_supplier_feats.mandatory_id',
            'item_account_supplier_feats.values',
        ]);
        $featurearr=[];
        foreach($feature as $row){
            $featurearr[$row->id][$row->feature_point_id]['available_id']=$yesno[$row->available_id];
            $featurearr[$row->id][$row->feature_point_id]['mandatory_id']=$yesno[$row->mandatory_id];
            $featurearr[$row->id][$row->feature_point_id]['values']=$row->values;
        }



        $itemaccount=$this->itemaccount
        ->join('item_account_suppliers', function($join)  {
            $join->on('item_account_suppliers.item_account_id', '=', 'item_accounts.id');
        })
        ->leftJoin('item_account_supplier_rates', function($join)  {
            $join->on('item_account_supplier_rates.item_account_supplier_id', '=', 'item_account_suppliers.id');
            $join->whereNull('item_account_supplier_rates.deleted_at');
        })
        /* ->join('item_account_supplier_feats', function($join)  {
            $join->on('item_account_supplier_feats.item_account_supplier_id', '=', 'item_account_suppliers.id');
        }) */
        ->leftJoin('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'item_account_suppliers.supplier_id');
        })
        ->leftJoin('uoms', function($join)  {
            $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('currencies.id', '=', 'item_account_supplier_rates.dom_currency_id');
        })
        ->leftJoin('currencies as foreign_currency', function($join)  {
            $join->on('foreign_currency.id', '=', 'item_account_supplier_rates.foreign_currency_id');
        })
        ->when(request('itemcategory_id'), function ($q) {
            return $q->where('item_accounts.itemcategory_id', '=', request('itemcategory_id', 0));
        })
        ->get([
            'item_accounts.id as item_account_id',
            'item_accounts.item_description',
            'item_account_suppliers.id as item_account_supplier_id', 
            'item_account_suppliers.supplier_id',
            'suppliers.name as supplier_name',
            'item_account_suppliers.custom_name',
            'item_account_suppliers.country_id',
            'item_account_suppliers.supplier_point_id',
            'item_account_suppliers.prod_dosage',
            'item_account_supplier_rates.dom_rate' ,
            'item_account_supplier_rates.dom_currency_id' ,
            'item_account_supplier_rates.foreign_rate' ,
            'item_account_supplier_rates.foreign_currency_id' ,
            'item_account_supplier_rates.date_from',
            'item_account_supplier_rates.date_to',
            'uoms.code as uom_code',
            'currencies.code as dom_currency_name',
            'foreign_currency.code as foreign_currency_name',
        ])
        ->map(function($itemaccount) use($yesno,$country,$featurearr){
            $itemaccount->date_from=date('d-M-Y',strtotime($itemaccount->date_from));
            $itemaccount->date_to=date('d-M-Y',strtotime($itemaccount->date_to));
            $itemaccount->country_id =$country[$itemaccount->country_id];
            $itemaccount->import_rate_bdt =$itemaccount->foreign_rate*82;
            $itemaccount->supplier_point_id =$country[$itemaccount->supplier_point_id];

            $itemaccount->available_id_1=isset($featurearr[$itemaccount->item_account_supplier_id][1]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][1]['available_id']:'';
            $itemaccount->mandatory_id_1=isset($featurearr[$itemaccount->item_account_supplier_id][1]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][1]['mandatory_id']:'';
            $itemaccount->values_1=isset($featurearr[$itemaccount->item_account_supplier_id][1]['values'])?$featurearr[$itemaccount->item_account_supplier_id][1]['values']:'';

            $itemaccount->available_id_2=isset($featurearr[$itemaccount->item_account_supplier_id][2]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][2]['available_id']:'';
            $itemaccount->mandatory_id_2=isset($featurearr[$itemaccount->item_account_supplier_id][2]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][2]['mandatory_id']:'';
            $itemaccount->values_2=isset($featurearr[$itemaccount->item_account_supplier_id][2]['values'])?$featurearr[$itemaccount->item_account_supplier_id][2]['values']:'';


            $itemaccount->available_id_3=isset($featurearr[$itemaccount->item_account_supplier_id][3]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][3]['available_id']:'';
            $itemaccount->mandatory_id_3=isset($featurearr[$itemaccount->item_account_supplier_id][3]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][3]['mandatory_id']:'';
            $itemaccount->values_3=isset($featurearr[$itemaccount->item_account_supplier_id][3]['values'])?$featurearr[$itemaccount->item_account_supplier_id][3]['values']:'';

            $itemaccount->available_id_4=isset($featurearr[$itemaccount->item_account_supplier_id][4]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][4]['available_id']:'';
            $itemaccount->mandatory_id_4=isset($featurearr[$itemaccount->item_account_supplier_id][4]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][4]['mandatory_id']:'';
            $itemaccount->values_4=isset($featurearr[$itemaccount->item_account_supplier_id][4]['values'])?$featurearr[$itemaccount->item_account_supplier_id][4]['values']:'';

            $itemaccount->available_id_5=isset($featurearr[$itemaccount->item_account_supplier_id][5]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][5]['available_id']:'';
            $itemaccount->mandatory_id_5=isset($featurearr[$itemaccount->item_account_supplier_id][5]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][5]['mandatory_id']:'';
            $itemaccount->values_5=isset($featurearr[$itemaccount->item_account_supplier_id][5]['values'])?$featurearr[$itemaccount->item_account_supplier_id][5]['values']:'';


            $itemaccount->available_id_6=isset($featurearr[$itemaccount->item_account_supplier_id][6]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][6]['available_id']:'';
            $itemaccount->mandatory_id_6=isset($featurearr[$itemaccount->item_account_supplier_id][6]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][6]['mandatory_id']:'';
            $itemaccount->values_6=isset($featurearr[$itemaccount->item_account_supplier_id][6]['values'])?$featurearr[$itemaccount->item_account_supplier_id][6]['values']:'';


            $itemaccount->available_id_7=isset($featurearr[$itemaccount->item_account_supplier_id][7]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][7]['available_id']:'';
            $itemaccount->mandatory_id_7=isset($featurearr[$itemaccount->item_account_supplier_id][7]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][7]['mandatory_id']:'';
            $itemaccount->values_7=isset($featurearr[$itemaccount->item_account_supplier_id][7]['values'])?$featurearr[$itemaccount->item_account_supplier_id][7]['values']:'';

            $itemaccount->available_id_8=isset($featurearr[$itemaccount->item_account_supplier_id][8]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][8]['available_id']:'';
            $itemaccount->mandatory_id_8=isset($featurearr[$itemaccount->item_account_supplier_id][8]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][8]['mandatory_id']:'';
            $itemaccount->values_8=isset($featurearr[$itemaccount->item_account_supplier_id][8]['values'])?$featurearr[$itemaccount->item_account_supplier_id][8]['values']:'';

            $itemaccount->available_id_9=isset($featurearr[$itemaccount->item_account_supplier_id][9]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][9]['available_id']:'';
            $itemaccount->mandatory_id_9=isset($featurearr[$itemaccount->item_account_supplier_id][9]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][9]['mandatory_id']:'';
            $itemaccount->values_9=isset($featurearr[$itemaccount->item_account_supplier_id][9]['values'])?$featurearr[$itemaccount->item_account_supplier_id][9]['values']:'';

            $itemaccount->available_id_10=isset($featurearr[$itemaccount->item_account_supplier_id][10]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][10]['available_id']:'';
            $itemaccount->mandatory_id_10=isset($featurearr[$itemaccount->item_account_supplier_id][10]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][10]['mandatory_id']:'';
            $itemaccount->values_10=isset($featurearr[$itemaccount->item_account_supplier_id][10]['values'])?$featurearr[$itemaccount->item_account_supplier_id][10]['values']:'';


            $itemaccount->available_id_11=isset($featurearr[$itemaccount->item_account_supplier_id][11]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][11]['available_id']:'';
            $itemaccount->mandatory_id_11=isset($featurearr[$itemaccount->item_account_supplier_id][11]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][11]['mandatory_id']:'';
            $itemaccount->values_11=isset($featurearr[$itemaccount->item_account_supplier_id][11]['values'])?$featurearr[$itemaccount->item_account_supplier_id][11]['values']:'';

            $itemaccount->available_id_12=isset($featurearr[$itemaccount->item_account_supplier_id][12]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][12]['available_id']:'';
            $itemaccount->mandatory_id_12=isset($featurearr[$itemaccount->item_account_supplier_id][12]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][12]['mandatory_id']:'';
            $itemaccount->values_12=isset($featurearr[$itemaccount->item_account_supplier_id][12]['values'])?$featurearr[$itemaccount->item_account_supplier_id][12]['values']:'';

            
            $itemaccount->available_id_13=isset($featurearr[$itemaccount->item_account_supplier_id][13]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][13]['available_id']:'';
            $itemaccount->mandatory_id_13=isset($featurearr[$itemaccount->item_account_supplier_id][13]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][13]['mandatory_id']:'';
            $itemaccount->values_13=isset($featurearr[$itemaccount->item_account_supplier_id][13]['values'])?$featurearr[$itemaccount->item_account_supplier_id][13]['values']:'';

            $itemaccount->available_id_14=isset($featurearr[$itemaccount->item_account_supplier_id][14]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][14]['available_id']:'';
            $itemaccount->mandatory_id_14=isset($featurearr[$itemaccount->item_account_supplier_id][14]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][14]['mandatory_id']:'';
            $itemaccount->values_14=isset($featurearr[$itemaccount->item_account_supplier_id][14]['values'])?$featurearr[$itemaccount->item_account_supplier_id][14]['values']:'';

            $itemaccount->available_id_15=isset($featurearr[$itemaccount->item_account_supplier_id][15]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][15]['available_id']:'';
            $itemaccount->mandatory_id_15=isset($featurearr[$itemaccount->item_account_supplier_id][15]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][15]['mandatory_id']:'';
            $itemaccount->values_15=isset($featurearr[$itemaccount->item_account_supplier_id][15]['values'])?$featurearr[$itemaccount->item_account_supplier_id][15]['values']:'';

            $itemaccount->available_id_16=isset($featurearr[$itemaccount->item_account_supplier_id][16]['available_id'])?$featurearr[$itemaccount->item_account_supplier_id][16]['available_id']:'';
            $itemaccount->mandatory_id_16=isset($featurearr[$itemaccount->item_account_supplier_id][16]['mandatory_id'])?$featurearr[$itemaccount->item_account_supplier_id][16]['mandatory_id']:'';
            $itemaccount->values_16=isset($featurearr[$itemaccount->item_account_supplier_id][16]['values'])?$featurearr[$itemaccount->item_account_supplier_id][16]['values']:'';


            return $itemaccount;
        })->groupBy('item_account_id');
        $datas=array();

        foreach($itemaccount as $key=>$rows){
            $subTot = collect(['supplier_name'=>'Item:','custom_name'=>$item[$key]]);
            array_push($datas,$subTot);
            foreach($rows as $result){
                array_push($datas,$result);
            }
            
        }
        echo json_encode($datas);
	 
	 }
	 
	public function getpdf(){

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        $pdf->SetY(10);
        $itemaccount = $this->reportData();

        $txt = "Lithe Group Employee";
        //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetY(5);
        $pdf->Text(90, 5, $txt);
        $pdf->SetY(10);
        $pdf->SetFont('helvetica', 'N', 10);
        //$pdf->Text(60, 10, $data['company']->address);
        $pdf->SetFont('helvetica', '', 8);
        $id=request('id',0);



        $view= \View::make('Defult.Report.ItemBank.ItemBankPdf',['itemaccount'=>$itemaccount]);
        $html_content=$view->render();
        $pdf->SetY(15);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/ItemBankPdf.pdf';
        //echo $html_content;
        //$pdf->output($filename);
        $pdf->output($filename,'I');
        exit();
        //$pdf->output($filename,'F');
        //return response()->download($filename);
	}
}
