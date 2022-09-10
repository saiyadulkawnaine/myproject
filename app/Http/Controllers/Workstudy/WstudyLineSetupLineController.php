<?php

namespace App\Http\Controllers\Workstudy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupLineRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Library\Template;
use App\Http\Requests\Workstudy\WstudyLineSetupLineRequest;

class WstudyLineSetupLineController extends Controller {

    private $linesetupline;
    private $subsection;
    private $lineresourcesetup;

    public function __construct(WstudyLineSetupLineRepository $linesetupline,WstudyLineSetupRepository $lineresourcesetup,SubsectionRepository $subsection) {
        $this->linesetupline = $linesetupline;
        $this->lineresourcesetup = $lineresourcesetup;
		$this->subsection = $subsection;
        $this->middleware('auth');
        $this->middleware('permission:view.wstudylinesetuplines',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.wstudylinesetuplines', ['only' => ['store']]);
        $this->middleware('permission:edit.wstudylinesetuplines',   ['only' => ['update']]);
        $this->middleware('permission:delete.wstudylinesetuplines', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $linesetuplines=array();
        $rows=$this->linesetupline
        ->where([['wstudy_line_setup_lines.wstudy_line_setup_id', '=', request('wstudy_line_setup_id',0)]])
        ->get();
        foreach ($rows as $row) {
          $linesetupline['id']=$row->id;
          $linesetupline['name']=$row->name;
          $linesetupline['code']=$row->code;
          $linesetupline['wstudy_line_setup_id']=$row->wstudy_line_setup_id;
          array_push($linesetuplines,$linesetupline);
        }
        echo json_encode($linesetuplines);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $lineresourcesetup= $this->lineresourcesetup->find(request('wstudy_line_setup_id',0));
		$subsection=$this->subsection
        ->join('company_subsections', function($join)  {
            $join->on('company_subsections.subsection_id', '=', 'subsections.id');
        })
        ->leftJoin('floors', function($join){
            $join->on('floors.id','=','subsections.floor_id');
        })
		->leftJoin('wstudy_line_setup_lines', function($join)  {
			$join->on('wstudy_line_setup_lines.subsection_id', '=', 'subsections.id');
			$join->where('wstudy_line_setup_lines.wstudy_line_setup_id', '=', request('wstudy_line_setup_id',0));
			$join->whereNull('wstudy_line_setup_lines.deleted_at');
        })
        ->where([['subsections.is_treat_sewing_line','=',1]])
        ->where([['company_subsections.company_id','=',$lineresourcesetup->company_id]])
        ->orderBy('subsections.floor_id')
        ->orderBy('subsections.sort_id')
		->get([
		'subsections.id',
        'subsections.name',
        'subsections.code',
        'subsections.sort_id',
        'subsections.floor_id',
        'floors.name as floor_id',
		'wstudy_line_setup_lines.id as wstudy_line_setup_line_id'
		]);
		$saved = $subsection->filter(function ($value) {
			if($value->wstudy_line_setup_line_id){
				return $value;
			}
		})->values();
		
		$new = $subsection->filter(function ($value) {
			if(!$value->wstudy_line_setup_line_id){
				return $value;
			}
		})->values();
		$row ['unsaved'] = $new;
		$row ['saved'] = $saved;
        $row ['line_merged_id'] = $lineresourcesetup->line_merged_id;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WstudyLineSetupLineRequest $request) {
        $lineresourcesetup=$this->lineresourcesetup->where([['sub_section_id','=',$request->subsection_id_string]])->first();

        if($lineresourcesetup)
        {
            return response()->json(array('success' => false, 'id' => '', 'message' => 'These line combination found in another resource'), 200);

        }

		foreach($request->subsection_id as $index=>$val){
				$linesetupline = $this->linesetupline->updateOrCreate(
				['wstudy_line_setup_id' => $request->wstudy_line_setup_id, 'subsection_id' => $request->subsection_id[$index]]);
		}

        $lineresource = $this->lineresourcesetup->update($request->wstudy_line_setup_id, ['sub_section_id'=>$request->subsection_id_string]);

        if ($linesetupline) {
            return response()->json(array('success' => true, 'id' => $linesetupline->id, 'message' => 'Save Successfully'), 200);
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
        $linesetupline = $this->linesetupline->find($id);
        $row ['fromData'] = $linesetupline;
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
    public function update(WstudyLineSetupLineRequest $request, $id) {
        /*$linesetupline = $this->linesetupline->update($id, $request->except(['id']));
        if ($linesetupline) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $lineresourcesetup=$this->lineresourcesetup->where([['sub_section_id','=',request('subsection_id_string',0)]])->first();
        if($lineresourcesetup)
        {
            return response()->json(array('success' => false, 'id' => '', 'message' => 'These line combination found in another resource'), 200);
        }

        if ($this->linesetupline->delete($id)) {
            $lineresource = $this->lineresourcesetup->update( request('wstudy_line_setup_id',0), ['sub_section_id'=>request('subsection_id_string',0)]);
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }


    }

}