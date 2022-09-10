<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ProductdepartmentRepository;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Library\Template;
use App\Http\Requests\StyleRequest;

class StyleImageController extends Controller {

    private $style;
    private $buyer;
    private $productdepartment;
    private $season;
    private $uom;
    private $team;
    private $teammember;
    private $itemaccount;
    private $embelishment;
    private $embelishmenttype;
    private $color;
    private $size;
  	private $stylegmts;
    private $gmtspart;
    private $autoyarn;
    private $yarncount;

    public function __construct(StyleRepository $style, BuyerRepository $buyer, ProductdepartmentRepository $productdepartment,SeasonRepository $season,UomRepository $uom,TeamRepository $team,TeammemberRepository $teammember,ItemAccountRepository $itemaccount,EmbelishmentRepository $embelishment,EmbelishmentTypeRepository $embelishmenttype,ColorRepository $color,SizeRepository $size,StyleGmtsRepository $stylegmts,GmtspartRepository $gmtspart,AutoyarnRepository $autoyarn, YarncountRepository $yarncount) {
        $this->style = $style;
        $this->buyer = $buyer;
        $this->productdepartment = $productdepartment;
        $this->season = $season;
        $this->uom = $uom;
        $this->team = $team;
        $this->teammember = $teammember;
        $this->itemaccount = $itemaccount;
        $this->embelishment = $embelishment;
        $this->embelishmenttype = $embelishmenttype;
        $this->color = $color;
        $this->size = $size;
        $this->stylegmts = $stylegmts;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->yarncount = $yarncount;

        $this->middleware('auth');
        $this->middleware('permission:view.styleimages',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.styleimages', ['only' => ['store']]);
        $this->middleware('permission:edit.styleimages',   ['only' => ['update']]);
        $this->middleware('permission:delete.styleimages', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-','');
      $productdepartment=array_prepend(array_pluck($this->productdepartment->get(),'departent_name','id'),'-Select-','');
      $season=array_prepend(array_pluck($this->season->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
      $teammember=array_prepend(array_pluck($this->teammember->get(),'name','id'),'-Select-','');


      $styles=array();
	    $rows=$this->style->get();
  		foreach($rows as $row){
        $style['id']=	$row->id;
        $style['receivedate']=	$row->receive_date;
        $style['styleref']=	$row->style_ref;
        $style['buyer']=	$buyer[$row->buyer_id];
        $style['deptcategory']=	$deptcategory[$row->dept_category_id];
        $style['season']=	$season[$row->season_id];
        $style['uom']=	$uom[$row->uom_id];
        $style['team']=	$team[$row->team_id];
        $style['teammember']=	$teammember[$row->teammember_id];
        $style['productdepartment']=	$productdepartment[$row->productdepartment_id];
  		   array_push($styles,$style);
  		}
        echo json_encode($styles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
      $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-',0);
      $productdepartment=array_prepend(array_pluck($this->productdepartment->get(),' <div title="Style" style="padding:1px" data-options="selected:true">','id'),'-Select-',0);
      $season=array_prepend(array_pluck($this->season->get(),'name','id'),'-Select-',0);
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-',0);
      $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
	  $itemaccount=array_prepend(array_pluck($this->itemaccount->get(),'item_description','id'),'-Select-','');
	  $itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');

	  $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
	  $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
	  $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join) use ($request) {
		$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
			'teammembers.id',
			'users.name',
		]),'name','id'),'-Select-',0);

	  $style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');

	  $stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join) use ($request) {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->get([
			'style_gmts.id',
			'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);


      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $autoyarn=array_prepend(array_pluck($this->autoyarn->get(),'name','id'),'-Select-','');
      $yarncount=array_prepend(array_pluck($this->yarncount->get(),'count','id'),'-Select-','');
      $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');

        return Template::loadView('Marketing.Style', ['buyer'=>$buyer,'deptcategory'=>$deptcategory,'productdepartment'=>$productdepartment,'season'=>$season,'uom'=>$uom,'team'=>$team,'teammember'=>$teammember,'itemaccount'=>$itemaccount,'itemcomplexity'=>$itemcomplexity,'embelishment'=>$embelishment,'embelishmenttype'=>$embelishmenttype,'color'=>$color,'size'=>$size,'style'=>$style,'stylegmts'=>$stylegmts,'gmtspart'=>$gmtspart,'autoyarn'=>$autoyarn,'yarncount'=>$yarncount,'materialsourcing'=>$materialsourcing,'fabricnature'=>$fabricnature,'fabriclooks'=>$fabriclooks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleRequest $request) {
		/*if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
		$sourcePath = $_FILES['userImage']['tmp_name'];
		$targetPath = "images/".$_FILES['userImage']['name'];
		if(move_uploaded_file($sourcePath,$targetPath)) {
		?>
		<img src="<?php echo $targetPath; ?>" width="200px" height="200px" class="upload-preview" />
		<?php
		}
		}*/
		$file = $request->file('userImage');
            $name = time() . '.' . $file->getClientOriginalExtension();

            $request->file('userImage')->move("images", $name);
        //$style = $this->style->create($request->except(['id']));
        //if ($style) {
            //return response()->json(array('success' => true, 'id' => $style->id, 'message' => 'Save Successfully'), 200);
        //}
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
        $style = $this->style->find($id);
        $row ['fromData'] = $style;
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
    public function update(StyleRequest $request, $id) {
        $style = $this->style->update($id, $request->except(['id']));
        if ($style) {
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
        if ($this->style->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
