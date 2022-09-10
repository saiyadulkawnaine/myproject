<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodbatchrdtabs">
    <div title="Batch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodbatchrdTblFt'" style="padding:2px">
                <table id="prodbatchrdTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            <th data-options="field:'machine_no'" width="80">Machine No</th>
                            
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'color_name'" width="80">Roll Color</th>
                            <th data-options="field:'color_range_name'" width="80">Color Range</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                            <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                            <th data-options="field:'root_batch_no'" width="80">Org. Batch No</th>
                            <th data-options="field:'root_batch_id'" width="80">Org. Batch ID</th>
                            
                        </tr>
                    </thead>
                    <div id="prodbatchrdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsProdBatchRd.searchBatch()">Show</a>
                </div>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchrdFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchrdFrm">
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
                                    <div class="col-sm-4 req-text">Org. Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="root_batch_no" id="root_batch_no" ondblclick="MsProdBatchRd.rootbatchWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="root_batch_id" id="root_batch_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','disabled'=>'disabled')) !!}
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
                                        {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Machine No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="machine_no" id="machine_no" ondblclick="MsProdBatchRd.machineWindow()" placeholder=" Double Click"/>
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
                                    <div class="col-sm-4 req-text">Batch Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_color_id', $color,'',array('id'=>'batch_color_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Roll Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_color_id', $color,'',array('id'=>'fabric_color_id','disabled'=>'disabled','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">lab Dip No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lap_dip_no" id="lap_dip_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Target Load Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="target_load_date" id="target_load_date" class="datepicker" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Fabric Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Batch Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_wgt" id="batch_wgt" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchrdFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRd.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRd.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRd.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRd.pdf()">Pdf</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRd.pdfRoll()">Roll</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodbatchrdrollTblFt'" style="padding:2px">
                <table id="prodbatchrdrollTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'root_roll_qty',halign:'center'" width="80" align="right">Org. <br/>Batch Qty</th>
                          <th data-options="field:'batch_qty',halign:'center'" width="80" align="right">Re-Dyeing <br/>Batch Qty</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                          <th data-options="field:'dyeing_color'" width="80">Roll Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          
                          <th data-options="field:'dyetype'" width="80">Dyeing Type</th>
                          
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          <th data-options="field:'yarndtl'" width="300">Yarn</th>

                        </tr>
                    </thead>
                </table>
                <div id="prodbatchrdrollTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdRoll.import()">Import</a>
                    </div>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchrdrollFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchrdrollFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row" style="display: none">
                                    <div class="col-sm-4">Roll No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="roll_no" id="roll_no" readonly />
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="so_dyeing_fabric_rcv_rol_id" id="so_dyeing_fabric_rcv_rol_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" class="number integer"/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Org. Batch Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="root_roll_qty" id="root_roll_qty" readonly class="number integer"/>
                                    </div>
                                </div>
                                
                                
                                
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchrdrollFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRdRoll.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdRoll.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdRoll.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Trim Weight" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodbatchrdtrimTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'itemclass_name'" width="200">Trims Name</th>
                            <th data-options="field:'qty'" width="80" align="right">Qty</th>
                            <th data-options="field:'uom_code'" width="60">UOM</th>
                            <th data-options="field:'wgt_per_unit'" width="80" align="right">Wgt./Unit</th>
                            <th data-options="field:'wgt_qty'" width="80" align="right">Weight</th>
                            
                            <th data-options="field:'remarks'" width="250">Remarks</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchrdtrimFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchrdtrimFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row ">
                                    <div class="col-sm-4 req-text">Trims Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="itemclass_name" id="itemclass_name" ondblclick="MsProdBatchRdTrim.rdtrimWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="itemclass_id" id="itemclass_id">
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="root_batch_trim_id" id="root_batch_trim_id" readonly />
                                    </div>
                                </div>
                                 
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" class="number integer" onchange="MsProdBatchRdTrim.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">UOM</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text"> Wgt./Unit</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="wgt_per_unit" id="wgt_per_unit" class="number integer"  onchange="MsProdBatchRdTrim.calculate()"/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text"> Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="wgt_qty" id="wgt_qty" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchrdtrimFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRdTrim.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdTrim.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdTrim.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Processes" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodbatchrdprocessTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'process_name'" width="200">Process Name</th>
                            <th data-options="field:'sort_id'" width="80" align="right">Sequence</th>
                            <th data-options="field:'remarks'" width="250">Remarks</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchrdprocessFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchrdprocessFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Process Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('production_process_id', $process_name,'',array('id'=>'production_process_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="id" id="id" readonly />
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
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchrdprocessFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRdProcess.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdProcess.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRdProcess.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>

<div id="prodbatchrdrootbatchWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodbatchrdrootbatchsearchTblFt'" style="padding:2px">
            <table id="prodbatchrdrootbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'company_code'" width="80">Company</th>
                    <th data-options="field:'batch_no'" width="80">Batch No</th>
                    <th data-options="field:'batch_date'" width="100">Batch Date</th>
                    <th data-options="field:'batchfor'" width="100">Batch For</th>
                    <th data-options="field:'machine_no'" width="80">Machine No</th>
                    <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                    <th data-options="field:'color_name'" width="80">Roll Color</th>
                    <th data-options="field:'color_range_name'" width="80">Color Range</th>
                    <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                    <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                    </tr>
                </thead>
            </table>
            <div id="prodbatchrdrootbatchsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchrdrootbatchWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodbatchrdrootbatchsearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodbatchrdrootbatchsearchFrm">
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
                <div id="prodbatchrdrootbatchsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdBatchRd.searchRootbatch()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>



<div id="prodbatchrdmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodbatchrdmachinesearchTblFt'" style="padding:2px">
            <table id="prodbatchrdmachinesearchTbl" style="width:100%">
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
            <div id="prodbatchrdmachinesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchrdmachineWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodbatchrdmachinesearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodbatchrdmachinesearchFrm">
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
                <div id="prodbatchrdmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdBatchRd.searchMachine()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="prodbatchrdrollsearchWindow" class="easyui-window" title="Roll Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#prodbatchrdrollsearchWindowFt'" style="padding:10px;">
        <table id="prodbatchrdrollsearchTbl" style="width:100%">
                    <thead>
                         <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <!-- <th data-options="field:'rcv_qty'" width="80">Rcv. Qty</th> -->
                          <th data-options="field:'batch_qty'" width="80" align="right"> Org. Batch Qty</th>
                          <!-- <th data-options="field:'bal_qty'" width="80">Balance Qty</th> -->
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>

                          <th data-options="field:'dyeing_color'" width="80">Dyeing Color</th>
                          <th data-options="field:'dyetype'" width="80">Dyeing Type</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          

                          
                          
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'machine_no'" width="70">Kniting M/C</th>

                          
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          <th data-options="field:'yarndtl'" width="300">Yarn</th>
                        </tr>
                    </thead>
                </table>
        <div id="prodbatchrdrollsearchWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        Total:<span id="prodbatchrd_selected_roll_total" style="padding-right: 150px">0</span>
        <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRdRoll.selectAll()">Select All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRdRoll.unselectAll()">Unselect All</a>

        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRdRoll.submitBatch()">Save</a>
        </div>
      </div>  
    </div>
</div>

<div id="prodbatchrdtrimWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodbatchrdtrimsearchTblFt'" style="padding:2px">
            <table id="prodbatchrdtrimsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'name'" width="100">Name</th>
                        <th data-options="field:'itemcategory'" width="100">Category</th>
                        <th data-options="field:'itemnature'" width="100">Item Nature</th>
                        <th data-options="field:'uom'" width="100">Uom</th>
                    </tr>
                </thead>
            </table>
            <div id="prodbatchrdtrimsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchrdtrimWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsAllProdBatchRdController.js"></script>

<script>
    $('#prodbatchrdFrm [id="fabric_color_id"]').combobox();
    $('#prodbatchrdprocessFrm [id="production_process_id"]').combobox();
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