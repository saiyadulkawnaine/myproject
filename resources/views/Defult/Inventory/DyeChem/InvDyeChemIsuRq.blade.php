<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invdyechemisurqtabs">
    <div title="Dyes & Chem Requisition Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqTblFt'" style="padding:2px">
                <table id="invdyechemisurqTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            <th data-options="field:'fabric_color'" width="80">Roll Color</th>
                            <th data-options="field:'batch_color'" width="80">Batch Color</th>
                            <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                            <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                            <th data-options="field:'liqure_ratio'" width="80">Liqure Ratio</th>
                            <th data-options="field:'liqure_wgt'" width="80">Liqure Wgt.</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="invdyechemisurqTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                    Requi. Date: <input type="text" name="from_rq_date" id="from_rq_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_rq_date" id="to_rq_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsInvDyeChemIsuRq.searchRq()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invdyechemisurqFrmFt'" style="width: 400px; padding:2px">
                <form id="invdyechemisurqFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_no" id="rq_no" placeholder=" Requisition No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" ondblclick="MsInvDyeChemIsuRq.openbatchWindow()" class="number integer" readonly/>
                                         <input type="hidden" name="prod_batch_id" id="prod_batch_id"  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_date" id="rq_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                               {{-- <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Des.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_desc" id="fabric_desc" readonly   ondblclick="MsInvDyeChemIsuRq.openfabricWindow()" placeholder=" Double Click for Fabric"/>
                                        <input type="hidden" name="fabrication_id" id="fabrication_id" readonly/>
                                    </div>
                                </div>--}}
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_color_name" id="batch_color_name" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Roll Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_color" id="fabric_color" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','style'=>'width: 100%; border-radius:2px','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Lap Dip No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lap_dip_no" id="lap_dip_no" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_wgt" id="batch_wgt" class="number integer" onchange="MsInvDyeChemIsuRq.calculate_liqure_wgt()" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Ratio.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_ratio" id="liqure_ratio" class="number integer" onchange="MsInvDyeChemIsuRq.calculate_liqure_wgt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_wgt" id="liqure_wgt" class="number integer" readonly />
                                    </div>
                                </div>

                               {{-- <div class="row middle">
                                    <div class="col-sm-4 req-text" >Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>--}}
                               
                                                                
                                <div class="row middle">
                                    <div class="col-sm-4">Operator</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="operator_name" id="operator_name"  placeholder=" Double Click To Select" ondblclick="MsInvDyeChemIsuRq.openEmpOperatorWindow()" />
                                        <input type="hidden" name="operator_id" id="operator_id" value="" />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">In Charge</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="incharge_name" id="incharge_name"  placeholder=" Double Click To Select" ondblclick="MsInvDyeChemIsuRq.openInchargeWindow()" />
                                        <input type="hidden" name="incharge_id" id="incharge_id" value="" />
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
                    <div id="invdyechemisurqFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRq.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRq.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvDyeChemIsuRq.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Item',iconCls:'icon-more',footer:'#invdyechemisurqitemFrmFt'" style="width:400px; padding:2px">
                <form id="invdyechemisurqitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                
                                 <div class="row">
                                    <div class="col-sm-4 req-text" >Sub Process</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('sub_process_id', $dyeingsubprocess,'',array('id'=>'sub_process_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="inv_dye_chem_isu_rq_id" id="inv_dye_chem_isu_rq_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item ID</div>
                                    <div class="col-sm-8">
                                        
                                        <input type="text" name="item_account_id" id="item_account_id" ondblclick="MsInvDyeChemIsuRqItem.openitemWindow()" readonly placeholder="Double Click"    />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Specification</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="specification" id="specification" disabled/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value=""  disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Catg.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_category" id="item_category" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_class" id="item_class" disabled/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                
                                
                                
                               <div class="row middle">
                                    <div class="col-sm-4">% on Batch Wgt</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="per_on_batch_wgt" id="per_on_batch_wgt" value="" class="number integer" onchange="MsInvDyeChemIsuRqItem.calculate_qty_form('per_on_batch_wgt')" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Gram/L. Liqure</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gram_per_ltr_liqure" id="gram_per_ltr_liqure" value="" class="number integer" onchange="MsInvDyeChemIsuRqItem.calculate_qty_form('gram_per_ltr_liqure')" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer"  readonly
                                         />
                                    </div>
                                </div>
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" value="" class="number integer"/>
                                    </div>
                                </div>
                                
                               
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" value="" />
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>

                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-5">
                                        
                                        <input type="text" name="master_rq_no" id="master_rq_no" ondblclick="MsInvDyeChemIsuRqItem.openrequisitionWindow()" readonly placeholder="Double Click"    />
                                        <input type="hidden" name="master_rq_id" id="master_rq_id"  readonly />
                                    </div>
                                    <div class="col-sm-3">
                                        
                                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqItem.itemCopy()">Copy</a>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invdyechemisurqitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqitemTblFt'" style="padding:2px">
                <table id="invdyechemisurqitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'per_on_batch_wgt'" width="100" align="right">% on Batch Wgt</th>
                        <th data-options="field:'gram_per_ltr_liqure'" width="100">Gram/L. Liqure</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'sub_process_name'" width="100" align="Left">Sub Process</th>

                        <th data-options="field:'sort_id'" width="100">Sequence</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="invdyechemisurqbatchsearchwindow" class="easyui-window" title="Batch" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqbatchsearchFrmft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqbatchsearchFrm">
                            <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Date</div>
                                    <div class="col-sm-4">
                                        <input type="text" name="batch_date_from" id="batch_date_from" class="batchpopupdatepicker" style="margin-right: 0px" />
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="batch_date_to" id="batch_date_to" class="batchpopupdatepicker" style="margin-left: 0px" />
                                    </div>
                                </div>
                        </form>
                    </code>
                </div>
                <div id="invdyechemisurqbatchsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRq.serachBatch()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqbatchsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            <th data-options="field:'machine_no'" width="80">Machine No</th>
                            
                            <th data-options="field:'color_name'" width="80">Roll Color</th>
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'color_range_name'" width="80">Color Range</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                            <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                    </tr>
                </thead>
            </table>
            
        </div>
    </div>
</div>


<div id="invdyechemisurqfabricsearchwindow" class="easyui-window" title="Fabric Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqfabricsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqfabricsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Construction</div>
                            <div class="col-sm-8">
                            <input type="text" name="construction_name" id="construction_name" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Composition</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="composition_name" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invdyechemisurqfabricsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRq.serachFabric()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqfabricsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqfabricsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'name'" width="250">Construction</th>
                        <th data-options="field:'composition_name'" width="250">Composition</th>
                    </tr>
                </thead>
            </table>
            
        </div>
    </div>
</div>


<div id="invdyechemisurqitemsearchwindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqitemsearchFrm">
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
                <div id="invdyechemisurqitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqitemsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqitemsearchTbl" style="width:100%">
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
            <div id="invdyechemisurqitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItem.closeinvyarnisurqitemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>

<div id="invDyechemMasterRqSearchWindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invDyechemMasterRqSearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invDyechemMasterRqSearchFrm">
                            <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_color" id="fabric_color" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                        </form>
                    </code>
                </div>
                <div id="invDyechemMasterRqSearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItem.serachMasterRq()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invDyechemMasterRqSearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'buyer_id'" width="100">Buyer</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            <th data-options="field:'fabric_desc'" width="80">Fabrication</th>
                            <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                            <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                            <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                            <th data-options="field:'liqure_ratio'" width="80">Liqure Ratio</th>
                            <th data-options="field:'liqure_wgt'" width="80">Liqure Wgt.</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!--------------------Employee HR Search-Window Operator------------------>
<div id="openempoperatorwindow" class="easyui-window" title="Operator Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="empoperatorFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvDyeChemIsuRq.searchEmpOperator()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="empoperatorTbl" style="width:700px">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openempoperatorwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Employee Incharge Search-Window Start------------------>
<div id="openinchargewindow" class="easyui-window" title="Incharge Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="inchargesearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvDyeChemIsuRq.searchIncharge()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="inchargesearchTbl" style="width:700px">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openinchargewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqController.js"></script>
<script>
    $('#invdyechemisurqFrm [id="buyer_id"]').combobox();
    
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });

    $(".batchpopupdatepicker" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    });
</script>
