<?php
namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentDocRepository;
use App\Repositories\Contracts\Util\TeamRepository;

use App\Library\Template;
use App\Http\Requests\Marketing\BuyerDevelopmentDocRequest;

class BuyerDevelopmentDocController extends Controller {

    private $buyerdevelopment;
    private $buyerdevelopmentdoc;
    private $company;
    private $buyer;
    private $team;

    public function __construct(
        BuyerDevelopmentRepository $buyerdevelopment, 
        BuyerDevelopmentDocRepository $buyerdevelopmentdoc, 
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team
    ) {
        $this->buyerdevelopment = $buyerdevelopment;
        $this->buyerdevelopmentdoc = $buyerdevelopmentdoc;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->team = $team;

        $this->middleware('auth');
        /*$this->middleware('permission:view.targettransfers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.targettransfers', ['only' => ['store']]);
        $this->middleware('permission:edit.targettransfers',   ['only' => ['update']]);
        $this->middleware('permission:delete.targettransfers', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() { 

        $rows=$this->buyerdevelopmentdoc
        
        ->where([['buyer_development_docs.buyer_development_id','=',request('buyer_development_id',0)]])
        ->orderBy('buyer_development_docs.id','desc')
        ->get([
            'buyer_development_docs.*',
        ])
        ->map(function($rows){
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
 
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerDevelopmentDocRequest $request) {
        if($request->id){
            $this->update($request, $request->id);
            return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
         }
         else{

            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            //$name =$request->file_src->getClientOriginalName().'.'.$request->file_src->extension();
             $request->file_src->move(public_path('images'), $name);
             $buyerdevelopmentdoc=$this->buyerdevelopmentdoc->create([
             'buyer_development_id'=>$request->buyer_development_id,
             'original_name'=>$request->original_name,
             'file_src'=> $name,
             //'file_type_id'=>$request->file_type_id
             ]);
             if ($buyerdevelopmentdoc) {
                return response()->json(array('success' => true, 'id' => $buyerdevelopmentdoc->id, 'message' => 'Save Successfully'), 200);
            }
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
        $buyerdevelopmentdoc = $this->buyerdevelopmentdoc->find($id);
        $row ['fromData'] = $buyerdevelopmentdoc;
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
    public function update(BuyerDevelopmentDocRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);   
        $buyerdevelopmentdoc=$this->buyerdevelopmentdoc->update($request->id,[
            'style_id'=>$request->style_id,
            'original_name'=>$request->original_name,
            'file_src'=>$name,
            //'file_type_id'=>$request->file_type_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->buyerdevelopmentintm->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}