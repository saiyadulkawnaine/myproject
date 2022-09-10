<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invdyechemisurqloantabs">
    <div title="Dyes & Chem Requisition Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqloanTblFt'" style="padding:2px">
                <table id="invdyechemisurqloanTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'supplier_id'" width="100">Supplier</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="invdyechemisurqloanTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    
                    Requi. Date: <input type="text" name="from_rq_date" id="from_rq_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_rq_date" id="to_rq_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsInvDyeChemIsuRqLoan.searchRq()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invdyechemisurqloanFrmFt'" style="width: 400px; padding:2px">
                <form id="invdyechemisurqloanFrm">
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
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Req. For</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('rq_basis_id', $dyechemrequisitionbasis,'',array('id'=>'rq_basis_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4" >Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
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
                            </code>
                        </div>
                    </div>
                    <div id="invdyechemisurqloanFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqLoan.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqloanFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqLoan.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvDyeChemIsuRqLoan.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invdyechemisurqitemloanFrmFt'" style="width:400px; padding:2px">
                <form id="invdyechemisurqitemloanFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                
                                 
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item ID</div>
                                    <div class="col-sm-8">
                                        
                                        <input type="text" name="item_account_id" id="item_account_id" ondblclick="MsInvDyeChemIsuRqItemLoan.openitemWindow()" readonly placeholder="Double Click"    />
                                        <input type="hidden" name="id" id="id" readonly/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 ">Item Description</div>
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
                                    <div class="col-sm-4">M/C No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="custom_no" id="custom_no" readonly  ondblclick="MsInvDyeChemIsuRqItemLoan.openmachineWindow()" placeholder="Double Click" />
                                        <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id" readonly/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" 
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
                    <div id="invdyechemisurqitemloanFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqItemLoan.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqitemloanFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemLoan.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqitemloanTblFt'" style="padding:2px">
                <table id="invdyechemisurqitemloanTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'custom_no'" width="80">M/C No</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'sort_id'" width="100">Sequence</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="invdyechemisurqitemloansearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqitemloansearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqitemloansearchFrm">
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
                <div id="invdyechemisurqitemloansearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemLoan.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqitemloansearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqitemloansearchTbl" style="width:100%">
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

<div id="invdyechemisurqmachineloansearchwindow" class="easyui-window" title=" Sales Order" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqmachineloansearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqmachineloansearchFrm">
                            <div class="row ">
                            <div class="col-sm-4 req-text">Dia/Width</div>
                            <div class="col-sm-8"> <input type="text" name="dia_width" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Feeder</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="no_of_feeder" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invdyechemisurqmachineloansearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemLoan.serachMachine()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invdyechemisurqmachineloansearchTbl" style="width:100%">
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
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqLoanController.js"></script>
<script>
    $('#invdyechemisurqloanFrm [id="supplier_id"]').combobox();

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