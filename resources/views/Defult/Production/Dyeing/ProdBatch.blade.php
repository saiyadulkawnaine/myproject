<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodbatchtabs">
    <div title="Batch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodbatchTblFt'" style="padding:2px">
                <table id="prodbatchTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'location_name'" width="100">Location</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            <th data-options="field:'machine_no'" width="80">Machine No</th>
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'color_name'" width="80">Roll Color</th>
                            <th data-options="field:'color_range_name'" width="80">Color Range</th>
                            <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                            <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>

                        </tr>
                    </thead>
                </table>
                <div id="prodbatchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsProdBatch.searchBatch()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchFrm">
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
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
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
                                    <div class="col-sm-4 req-text">Lap Dip No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lap_dip_no" id="lap_dip_no" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Target Load Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="target_load_date" id="target_load_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Machine No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="machine_no" id="machine_no" ondblclick="MsProdBatch.machineWindow()" placeholder=" Double Click"/>
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
                                        {!! Form::select('batch_color_id', $color,'',array('id'=>'batch_color_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Roll Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_color_id', $color,'',array('id'=>'fabric_color_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Fabric Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Batch Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_wgt" id="batch_wgt" readonly />
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
                    <div id="prodbatchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatch.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatch.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatch.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatch.pdf()">PDF</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatch.pdfRoll()">Roll</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodbatchrollTblFt'" style="padding:2px">
                <table id="prodbatchrollTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'rcv_qty'" width="80" align="right">Receive Qty</th>
                          <th data-options="field:'batch_qty'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'bal_qty'" width="80" align="right">Balance Qty</th>
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
                <div id="prodbatchrollTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">


                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRoll.import()">Import</a>
                    </div>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchrollFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchrollFrm">
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
                                    <div class="col-sm-4 req-text">Blance Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bal_qty" id="bal_qty" readonly class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rcv Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rcv_qty" id="rcv_qty" readonly class="number integer"/>
                                    </div>
                                </div>
                                
                                
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchrollFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRoll.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodbatchrollFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchRoll.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Trim Weight" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodbatchtrimTbl" style="width:100%">
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
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchtrimFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchtrimFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row ">
                                    <div class="col-sm-4 req-text">Trims Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="itemclass_name" id="itemclass_name" ondblclick="MsProdBatchTrim.trimWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="itemclass_id" id="itemclass_id">
                                        <input type="hidden" name="id" id="id" readonly />
                                    </div>
                                </div>
                                 
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" class="number integer" onchange="MsProdBatchTrim.calculate()" />
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
                                        <input type="text" name="wgt_per_unit" id="wgt_per_unit" class="number integer"  onchange="MsProdBatchTrim.calculate()"/>
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
                    <div id="prodbatchtrimFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchTrim.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchTrim.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchTrim.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Processes" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodbatchprocessTbl" style="width:100%">
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
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchprocessFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchprocessFrm">
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
                    <div id="prodbatchprocessFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchProcess.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchProcess.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchProcess.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
<div id="prodbatchmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodbatchmachinesearchTblFt'" style="padding:2px">
            <table id="prodbatchmachinesearchTbl" style="width:100%">
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
            <div id="prodbatchmachinesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchmachineWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodbatchmachinesearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodbatchmachinesearchFrm">
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
                <div id="prodbatchmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsProdBatch.searchMachine()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="prodbatchrollsearchWindow" class="easyui-window" title="Roll Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:true,title:'Search',footer:'#prodbatchrollsearchFrmFt'" style="width: 400px; padding:2px">
            <form id="prodbatchrollsearchFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row">
                                    <div class="col-sm-4">Issue No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_no" id="issue_no"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref"  />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Buyer Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchrollsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRoll.search()">Show</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodbatchrollsearchFrm')">Reset</a>
                    </div>
                </form>
        </div>
        <div data-options="region:'center',footer:'#prodbatchrollsearchWindowFt'" style="padding:10px;">
        <table id="prodbatchrollsearchTbl" style="width:100%">
                    <thead>
                         <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'rcv_qty'" width="80" align="right">Rcv. Qty</th>
                          <th data-options="field:'batch_qty'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'bal_qty'" width="80" align="right">Balance Qty</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>

                          <th data-options="field:'dyeing_color'" width="80">Dyeing Color</th>
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
                          <th data-options="field:'machine_no'" width="70">Kniting M/C</th>

                          
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          <th data-options="field:'yarndtl'" width="300">Yarn</th>
                        </tr>
                    </thead>
                </table>
        <div id="prodbatchrollsearchWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            Total:<span id="prodbatch_selected_roll_total" style="padding-right: 150px">0</span>
        <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRoll.selectAll()">Select All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRoll.unselectAll()">Unselect All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchRoll.submitBatch()">Save</a>
        </div>
      </div>  
    </div>
</div>

<div id="prodbatchtrimWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodbatchtrimsearchTblFt'" style="padding:2px">
            <table id="prodbatchtrimsearchTbl" style="width:100%">
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
            <div id="prodbatchtrimsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchtrimWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsAllProdBatchController.js"></script>

<script>
    $('#prodbatchFrm [id="fabric_color_id"]').combobox();
    $('#prodbatchFrm [id="batch_color_id"]').combobox();
    $('#prodbatchprocessFrm [id="production_process_id"]').combobox();
    $('#prodbatchrollsearchFrm [id="buyer_id"]').combobox();
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