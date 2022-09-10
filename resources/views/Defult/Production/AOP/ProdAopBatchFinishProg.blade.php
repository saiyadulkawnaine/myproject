<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodaopbatchfinishprogtabs">
    <div title="Batch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchfinishprogTblFt'" style="padding:2px">
                <table id="prodaopbatchfinishprogTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'shiftname'" width="80">Shift No</th>
                            <th data-options="field:'process_name'" width="80">Process</th>
                            <th data-options="field:'machine_no'" width="80">Machine No</th>
                            <th data-options="field:'incharge_name'" width="80">Incharge</th>
                            <th data-options="field:'operator_name'" width="80">Operator</th>
                            <th data-options="field:'load_date'" width="80">Start Date</th>
                            <th data-options="field:'load_time'" width="80">Start Time</th>
                            <th data-options="field:'unload_date'" width="80">End Date</th>
                            <th data-options="field:'unload_time'" width="80">End Time</th>
                            <th data-options="field:'posting_date'" width="80">Prod.Date</th>
                            <th data-options="field:'temparature'" width="80">Temparature</th>
                            <th data-options="field:'stretch'" width="80">Stretch</th>
                            <th data-options="field:'over_feed'" width="80">Over Feed</th>
                            <th data-options="field:'feed_in'" width="80">Feed In</th>
                            <th data-options="field:'pinning'" width="80">Pinning</th>
                            
                            <th data-options="field:'speed'" width="80">Speed</th>
                            <th data-options="field:'spirality'" width="80">Spirality</th>

                            <th data-options="field:'remarks'" width="80">Remarks</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                            <th data-options="field:'paste_wgt'" width="80">Paste Wgt</th>
                        </tr>
                    </thead>
                    <div id="prodaopbatchfinishprogTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                     Posting Date: <input type="text" name="from_load_posting_date" id="from_load_posting_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_load_posting_date" id="to_load_posting_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsProdAopBatchFinishProg.searchList()">Show</a>
                </div>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodaopbatchfinishprogFrmft'" style="width: 400px; padding:2px">
                <form id="prodaopbatchfinishprogFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Shift</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shift_id', $shiftname,'',array('id'=>'shift_id')) !!}
                                        <input type="hidden" name="id" id="id"  readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Process</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('production_process_id', $process_name,'',array('id'=>'production_process_id')) !!}
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" ondblclick="MsProdAopBatchFinishProg.batchWindow()" placeholder=" Double Click"readonly="" />
                                        <input type="hidden" name="prod_aop_batch_id" id="prod_aop_batch_id"  readonly />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Machine No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="machine_no" id="machine_no" ondblclick="MsProdAopBatchFinishProg.machineWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="machine_id" id="machine_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Capacity</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="prod_capacity" id="prod_capacity" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Start Date Time</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="load_date" id="load_date" value="{{ date('Y-m-d') }}" placeholder="date" class="datepicker"/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="load_time" id="load_time" value="" placeholder="time"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">End Date Time</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="unload_date" id="unload_date" value="{{ date('Y-m-d') }}" placeholder="date" class="datepicker"/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="unload_time" id="unload_time" value="" placeholder="time"/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Prod.Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="posting_date" id="posting_date" value="{{-- {{ date('Y-m-d') }} --}}" class="datepicker" />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Operator</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="operator_name" id="operator_name" ondblclick="MsProdAopBatchFinishProg.opratorWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="operator_id" id="operator_id">
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Incharge</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="incharge_name" id="incharge_name" ondblclick="MsProdAopBatchFinishProg.inchargeWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="incharge_id" id="incharge_id">
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Temparature</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="temparature" id="temparature" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Stretch</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="stretch" id="stretch"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Over Feed</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="over_feed" id="over_feed"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Feed In</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="feed_in" id="feed_in"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Pinning</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="pinning" id="pinning"/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Speed</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="speed" id="speed"/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Spirality</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="spirality" id="spirality"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_date" id="batch_date" class="datepicker" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch For</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_color_id', $color,'',array('id'=>'batch_color_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Design No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="design_no" id="design_no" disabled />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Fabric Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Paste Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paste_wgt" id="paste_wgt" disabled />
                                    </div>
                                </div> 
                            </code>
                        </div>
                    </div>
                    <div id="prodaopbatchfinishprogFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishProg.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishProg.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishProg.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchfinishprogrollTblFt'" style="padding:2px">
                <table id="prodaopbatchfinishprogrollTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'rcv_qty'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'dyeing_gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dyeing_dia_width'" width="40">Dia</th>
                          <th data-options="field:'prod_no'" width="70">Knit Prod. Ref</th>
                          
                          

                        </tr>
                    </thead>
                </table>
                <div id="prodaopbatchfinishprogrollTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishProgRoll.openrollWindow()">Import</a>
                
                </div>
            </div>
        </div>
    </div>
    <div title="Chemical Used" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodaopbatchfinishprogchemTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'item_desc'" width="70">Item Desc.</th>
                          <th data-options="field:'qty'" width="100" align="right">Used Qty</th>
                          <th data-options="field:'specification'" width="100">Specification</th>
                          <th data-options="field:'item_class'" width="100">Item Class</th>
                          <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                          <th data-options="field:'item_category'" width="100">Item Category</th>
                          <th data-options="field:'remarks'" width="100">Item Remarks</th>
                         

                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodaopbatchfinishprogchemFrmft'" style="width: 400px; padding:2px">
                <form id="prodaopbatchfinishprogchemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Desc.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" ondblclick="MsProdAopBatchFinishProgChem.openitemWindow()" placeholder=" Double Click"readonly="" />
                                        <input type="hidden" name="item_account_id" id="item_account_id"  readonly />
                                        <input type="hidden" name="id" id="id"  readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Specification</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="specification" id="specification" disabled />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Used Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_class" id="item_class" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sub Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sub_class_name" id="sub_class_name" disabled />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Item Category</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_category" id="item_category" disabled />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodaopbatchfinishprogchemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishProgChem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishProgChem.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishProgChem.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="prodaopbatchfinishprogbatchWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodaopbatchfinishprogbatchsearchTblFt'" style="padding:2px">
            <table id="prodaopbatchfinishprogbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'company_code'" width="80">Company</th>
                    <th data-options="field:'batch_no'" width="80">Batch No</th>
                    <th data-options="field:'batch_date'" width="100">Batch Date</th>
                    <th data-options="field:'batchfor'" width="100">Batch For</th>
                    <th data-options="field:'machine_no'" width="80">Machine No</th>
                    <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                    <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                    <th data-options="field:'paste_wgt'" width="80">Paste Wgt</th>
                    </tr>
                </thead>
            </table>
            <div id="prodaopbatchfinishprogbatchsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchfinishprogbatchWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodaopbatchfinishprogbatchsearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodaopbatchfinishprogbatchsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Company</div>
                            <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch No</div>
                            <div class="col-sm-8">
                            <input type="text" name="batch_no" id="batch_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch For</div>
                            <div class="col-sm-8">
                            {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for')) !!}
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="prodaopbatchfinishprogbatchsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdAopBatchFinishProg.getBatch()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="prodaopbatchfinishprogmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodaopbatchfinishprogmachinesearchTblFt'" style="padding:2px">
            <table id="prodaopbatchfinishprogmachinesearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'custom_no'" width="100">Machine No</th>
                    <th data-options="field:'asset_name'" width="100">Asset Name</th>
                    <th data-options="field:'origin'" width="100">Origin</th>
                    <th data-options="field:'brand'" width="100">Brand</th>
                    <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
                    <th data-options="field:'dia_width'" width="60">Dia/Width</th>
                    <th data-options="field:'gauge'" width="60">Gauge</th>
                    <th data-options="field:'extra_cylinder'" width="60">Extra Cylinder</th>
                    <th data-options="field:'no_of_feeder'" width="60">Feeder</th>
                    </tr>
                </thead>
            </table>
            <div id="prodaopbatchfinishprogmachinesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchfinishprogmachineWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodaopbatchfinishprogmachinesearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodaopbatchfinishprogmachinesearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4">Machine No</div>
                            <div class="col-sm-8"> <input type="text" name="machine_no" id="machine_no" /> </div>
                            </div>
                            <div class="row middle ">
                            <div class="col-sm-4">Brand</div>
                            <div class="col-sm-8"> <input type="text" name="brand" id="brand" /> </div>
                            </div>
                            
                            
                        </code>
                    </div>
                </div>
                <div id="prodaopbatchfinishprogmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsProdAopBatchFinishProg.searchMachine()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="prodaopbatchfinishprogoperatorwindow" class="easyui-window" title="Operator Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="prodaopbatchfinishprogoperatorFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Designation</div>
                                <div class="col-sm-8">
                                    {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Department </div>
                                <div class="col-sm-8">
                                    {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdAopBatchFinishProg.searchEmpOperator()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="prodaopbatchfinishprogoperatorTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'employee_name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchfinishprogoperatorwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<div id="prodaopbatchfinishproginchargewindow" class="easyui-window" title="Operator Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="prodaopbatchfinishproginchargeFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Designation</div>
                                <div class="col-sm-8">
                                    {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Department </div>
                                <div class="col-sm-8">
                                    {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdAopBatchFinishProg.searchEmpIncharge()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="prodaopbatchfinishproginchargeTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'employee_name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchfinishproginchargewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<div id="prodaopbatchfinishprogchemitemsearchwindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#prodaopbatchfinishprogchemitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="prodaopbatchfinishprogchemitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Item Category</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_category" id="item_category" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Item Class</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_class" id="item_class" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="prodaopbatchfinishprogchemitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishProgChem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="prodaopbatchfinishprogchemitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'stock_qty'" width="100" align="right">Stock Qty</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="prodaopbatchfinishprogrollsearchwindow" class="easyui-window" title="Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#prodaopbatchfinishprogrollsearchTblFt'" style="padding:10px;">
            <table id="prodaopbatchfinishprogrollsearchTbl" style="width:100%">
                <thead>
                    <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'rcv_qty'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'dyeing_gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dyeing_dia_width'" width="40">Dia</th>
                          <th data-options="field:'prod_no'" width="70">Knit Prod. Ref</th>
                    </tr>
                </thead>
            </table>
            <div id="prodaopbatchfinishprogrollsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishProgRoll.submit()">Save</a>
                
                </div>
        </div>
    </div>
</div>









<script type="text/javascript" src="<?php echo url('/');?>/js/Production/AOP/MsAllProdAopBatchFinishProgController.js"></script>

<script>
    (function(){
      
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
      });

      $('#load_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    $('#unload_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });


   })(jQuery);

</script>