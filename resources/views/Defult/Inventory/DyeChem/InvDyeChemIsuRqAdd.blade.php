<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invdyechemisurqaddtabs">
    <div title="Dyes & Chem Requisition Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqaddTblFt'" style="padding:2px">
                <table id="invdyechemisurqaddTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'root_rq_no'" width="100">Initial Requis. No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
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
                <div id="invdyechemisurqaddTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    
                    Requi. Date: <input type="text" name="from_rq_date" id="from_rq_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_rq_date" id="to_rq_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsInvDyeChemIsuRqAdd.searchRq()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invdyechemisurqaddFrmFt'" style="width: 400px; padding:2px">
                <form id="invdyechemisurqaddFrm">
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
                                    <div class="col-sm-4 req-text">Initial Requi.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="root_rq_no" id="root_rq_no" placeholder=" Initial Requisition " readonly ondblclick="MsInvDyeChemIsuRqAdd.openrequisitionWindow()"  />
                                        <input type="hidden" name="root_id" id="root_id" readonly/>

                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_date" id="rq_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
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
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Des</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_desc" id="fabric_desc" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_color" id="fabric_color" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" class="number integer" disabled/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Lap Dip No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lap_dip_no" id="lap_dip_no" class="number integer" disabled/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_wgt" id="batch_wgt" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Ratio.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_ratio" id="liqure_ratio" class="number integer" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_wgt" id="liqure_wgt" class="number integer" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                               
                                                                
                               
                            </code>
                        </div>
                    </div>
                    <div id="invdyechemisurqaddFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqAdd.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqaddFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqAdd.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvDyeChemIsuRqAdd.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqitemaddTblFt'" style="padding:2px">
                <form id="invdyechemisurqitemaddFrm" >
                    <div id="container">
                        <div id="body">
                            <code id="invdyechemisurqitemaddmatrix">                               
                            </code>
                        </div>
                    </div>
                   
                </form>
            </div>
             <div id="invdyechemisurqitemaddTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqItemAdd.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqitemaddFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAdd.remove()">Delete</a>
                    </div>
        </div>
    </div>
</div>


<div id="invdyechemisurqaddrequisitionsearchwindow" class="easyui-window" title="Fabric Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqaddrequisitionsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqaddrequisitionsearchFrm">
                            <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_no" id="rq_no" placeholder=" Requisition No" />
                                    </div>
                            </div>
                            <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                            </div>
                            <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" />
                                    </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invdyechemisurqaddrequisitionsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqAdd.serachRequisition()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqaddrequisitionsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqaddrequisitionsearchTbl" style="width:100%">
                <thead>
                    <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'location_name'" width="100">Location</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            {{-- <th data-options="field:'fabric_desc'" width="80">Fabrication</th> --}}
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


<div id="invdyechemisurqadditemsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqadditemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqadditemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >PO No</div>
                            <div class="col-sm-8">
                            <input type="text" name="po_no" id="po_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">PI NO</div>
                            <div class="col-sm-8">
                            <input type="text" name="pi_no" id="pi_no" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invdyechemisurqadditemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAdd.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqadditemsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqadditemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                    </tr>
                </thead>
            </table>
            <div id="invdyechemisurqadditemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAdd.closeinvyarnisurqadditemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqAddController.js"></script>
<script>
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
</script>
