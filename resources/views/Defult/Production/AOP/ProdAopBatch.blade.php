<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodaopbatchtabs">
    <div title="Batch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchTblFt'" style="padding:2px">
                <table id="prodaopbatchTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'customer_name'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">AOP Order No</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'design_no'" width="80">Design No</th>
                            <th data-options="field:'fabric_wgt'" align="right" width="80">Fabric Wgt</th>
                            <th data-options="field:'paste_wgt'" align="right" width="80">Paste Wgt</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>

                        </tr>
                    </thead>
                </table>
                <div id="prodaopbatchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsProdAopBatch.searchBatch()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodaopbatchFrmft'" style="width: 400px; padding:2px">
                <form id="prodaopbatchFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row">
                                    <div class="col-sm-4">Batch ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="id" id="id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">AOP Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsProdAopBatch.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_aop_id" id="so_aop_id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_date" id="batch_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch For</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for')) !!}
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Design No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="design_no" id="design_no" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Target Load Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="target_load_date" id="target_load_date" class="datepicker" />
                                    </div>
                                </div>
                               
                               <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch/Fabric Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_color_id', $color,'',array('id'=>'batch_color_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                
                                 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Paste Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paste_wgt" id="paste_wgt"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodaopbatchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatch.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatch.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatch.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatch.pdf()">PDF</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatch.pdfRoll()">Roll</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchrollTblFt'" style="padding:2px">
                <table id="prodaopbatchrollTbl" style="width:100%">
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
                <div id="prodaopbatchrollTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">


                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchRoll.import()">Import</a>
                    </div>
            </div>
            
        </div>
    </div>
    <div title="Process" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchprocessTblFt'" style="padding:2px">
                <table id="prodaopbatchprocessTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'process_name'" width="80">Process Name</th>
                            <th data-options="field:'prod_date'" width="100">Process Date</th>
                            <th data-options="field:'machine_no'" width="80">Machine No</th>
                            <th data-options="field:'supervisor_name'" width="100">Supervisor</th>
                            <th data-options="field:'shift_name'" width="100">Shift</th>
                            <th data-options="field:'sort_id'" width="100">Sequence</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>

                        </tr>
                    </thead>
                </table>
                
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodaopbatchprocessFrmFt'" style="width: 400px; padding:2px">
                <form id="prodaopbatchprocessFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row">
                                    <div class="col-sm-4 req-text">Process Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('production_process_id', $process_name,'',array('id'=>'production_process_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="id" id="id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Process Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="prod_date" id="prod_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Machine No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="machine_no" id="machine_no" ondblclick="MsProdAopBatchProcess.machineWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id">
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
                                    <div class="col-sm-4 req-text">Shift</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shift_id', $shiftname,'',array('id'=>'shift_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Supervisor Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supervisor_name" id="supervisor_name" ondblclick="MsProdAopBatchProcess.supervisorWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="supervisor_id" id="supervisor_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text"> Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodaopbatchprocessFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchProcess.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchProcess.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchProcess.remove()">Delete</a>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    
</div>

<div id="prodaopbatchsoWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#prodaopbatchsoWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="prodaopbatchsosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="so_no" id="so_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="prodaopbatchsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsProdAopBatch.getaoporder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soaopfabricisusosearchTblFt'" style="padding:10px;">
            <table id="prodaopbatchsosearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'issue_no'" width="100">Issue No</th>
                        <th data-options="field:'issue_date'" width="100">Issue Date</th>

                    </tr>
                </thead>
            </table>
             <div id="soaopfabricisusosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchsoWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>



<div id="prodaopbatchrollsearchWindow" class="easyui-window" title="Roll Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#prodaopbatchrollsearchWindowFt'" style="padding:10px;">
        <table id="prodaopbatchrollsearchTbl" style="width:100%">
                    <thead>
                         <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Customer Name</th>
                          <th data-options="field:'rcv_qty'" width="80" align="right">Rcv. Qty</th>
                          <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
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
        <div id="prodaopbatchrollsearchWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            Total:<span id="prodaopbatch_selected_roll_total" style="padding-right: 150px">0</span>
        <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchRoll.selectAll()">Select All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchRoll.unselectAll()">Unselect All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchRoll.submitBatch()">Save</a>
        </div>
      </div>  
    </div>
</div>

<div id="prodaopbatchprocessmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodaopbatchprocessmachineWindowFt'" style="padding:2px">
            <table id="prodaopbatchprocessmachinesearchTbl" style="width:100%">
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
            <div id="prodaopbatchprocessmachineWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchprocessmachineWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodaopbatchprocessmachinesearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodaopbatchprocessmachinesearchFrm">
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
                <div id="prodaopbatchprocessmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdAopBatchProcess.searchMachine()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="prodaopbatchprocesssupervisorWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodaopbatchprocesssupervisorWindowFt'" style="padding:2px">
            <table id="prodaopbatchprocesssupervisorsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_name'" width="100">Designation</th>
                        <th data-options="field:'department_name'" width="100">Department</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
            <div id="prodaopbatchprocesssupervisorWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchprocesssupervisorWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodaopbatchprocesssupervisorsearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodaopbatchprocesssupervisorsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
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
                            
                        </code>
                    </div>
                </div>
                <div id="prodaopbatchprocesssupervisorsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdAopBatchProcess.getSupervisor()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Production/AOP/MsAllProdAopBatchController.js"></script>

<script>
    $('#prodaopbatchFrm [id="batch_color_id"]').combobox();
    $('#prodaopbatchprocessFrm [id="production_process_id"]').combobox();
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

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });


   })(jQuery);

</script>