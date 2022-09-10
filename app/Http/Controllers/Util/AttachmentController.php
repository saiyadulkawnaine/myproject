<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AttachmentRepository;
use App\Repositories\Contracts\Util\ResourceRepository;
use App\Library\Template;
use App\Http\Requests\AttachmentRequest;

class AttachmentController extends Controller {

    private $attachment;
    private $resource;

    public function __construct(AttachmentRepository $attachment, ResourceRepository $resource) {
        $this->attachment = $attachment;
        $this->resource = $resource;
        $this->middleware('auth');
        $this->middleware('permission:view.attachments',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.attachments', ['only' => ['store']]);
        $this->middleware('permission:edit.attachments',   ['only' => ['update']]);
        $this->middleware('permission:delete.attachments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $resource=array_prepend(array_pluck($this->resource->get(),'name','id'),'-Select-','');
        $attachments=array();
		    $rows=$this->attachment->get();
    		foreach($rows as $row){
          $attachment['id']=	$row->id;
          $attachment['name']=	$row->name;
          $attachment['code']=	$row->code;
          $attachment['resource']=	$resource[$row->resource_id];
    		   array_push($attachments,$attachment);
    		}
        echo json_encode($attachments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $resource=array_prepend(array_pluck($this->resource->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.Attachment", ["resource"=> $resource]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttachmentRequest $request) {
        $attachment = $this->attachment->create($request->except(['id']));
        if ($attachment) {
            return response()->json(array('success' => true, 'id' => $attachment->id, 'message' => 'Save Successfully'), 200);
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
        $attachment = $this->attachment->find($id);
        $row ['fromData'] = $attachment;
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
    public function update(AttachmentRequest $request, $id) {
        $attachment = $this->attachment->update($id, $request->except(['id']));
        if ($attachment) {
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
        if ($this->attachment->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
