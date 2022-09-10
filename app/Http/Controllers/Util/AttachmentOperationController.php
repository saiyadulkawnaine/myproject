<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AttachmentOperationRepository;
use App\Repositories\Contracts\Util\AttachmentRepository;
use App\Repositories\Contracts\Util\OperationRepository;

use App\Library\Template;
use App\Http\Requests\AttachmentOperationRequest;

class AttachmentOperationController extends Controller {

    private $attachmentoperation;
	private $attachment;
    private $operation;

    public function __construct(AttachmentOperationRepository $attachmentoperation, OperationRepository $operation,AttachmentRepository $attachment) {
        $this->attachmentoperation = $attachmentoperation;
		$this->attachment = $attachment;
        $this->operation = $operation;
        $this->middleware('auth');
        // $this->middleware('permission:view.attachmentoperations',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.attachmentoperations', ['only' => ['store']]);
        // $this->middleware('permission:edit.attachmentoperations',   ['only' => ['update']]);
        // $this->middleware('permission:delete.attachmentoperations', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $operation=array_prepend(array_pluck($this->operation->get(),'name','id'),'-Select-','');
        $attachmentoperations=array();
        $rows=$this->attachmentoperation->get();
        foreach ($rows as $row) {
          $attachmentoperation['id']=$row->id;
          $attachmentoperation['name']=$row->name;
          $attachmentoperation['code']=$row->code;
          $attachmentoperation['operation']=$operation[$row->operation_id];
          array_push($attachmentoperations,$attachmentoperation);
        }
        echo json_encode($attachmentoperations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$attachment=$this->attachment
		->leftJoin('attachment_operations', function($join)  {
			$join->on('attachment_operations.attachment_id', '=', 'attachments.id');
			$join->where('attachment_operations.operation_id', '=', request('operation_id',0));
			$join->whereNull('attachment_operations.deleted_at');
		})
		->get([
		    'attachments.id',
		    'attachments.name',
		    'attachment_operations.id as attachment_operation_id'
		]);
		$saved = $attachment->filter(function ($value) {
			if($value->attachment_operation_id){
				return $value;
			}
		})->values();
		
		$new = $attachment->filter(function ($value) {
			if(!$value->attachment_operation_id){
				return $value;
			}
		})->values();
		$row ['unsaved'] = $new;
		$row ['saved'] = $saved;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttachmentOperationRequest $request) {
		foreach($request->attachment_id as $index=>$val){
            $attachmentoperation = $this->attachmentoperation->updateOrCreate(
				['operation_id' => $request->operation_id, 'attachment_id' => $request->attachment_id[$index]]);
		}
        if ($attachmentoperation) {
            return response()->json(array('success' => true, 'id' => $attachmentoperation->id, 'message' => 'Save Successfully'), 200);
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
        $attachmentoperation = $this->attachmentoperation->find($id);
        $row ['fromData'] = $attachmentoperation;
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
    public function update(AttachmentOperationRequest $request, $id) {
        $attachmentoperation = $this->attachmentoperation->update($id, $request->except(['id']));
        if ($attachmentoperation) {
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
        if ($this->attachmentoperation->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
