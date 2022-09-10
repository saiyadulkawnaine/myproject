<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invgreyfabtransouttabs">
    <div title="Grey Fabric Item Transfer Out" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invgreyfabtransoutTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'issue_no'" width="40">Issue No</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'to_company_name'" width="100">Transfer To</th>
                            <th data-options="field:'issue_date'" width="80">Issue Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Grey Fabric Transfer Out Reference',footer:'#invgreyfabtransoutFrmft'" style="width: 400px; padding:2px">
                <form id="invgreyfabtransoutFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Transfer No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_no" id="issue_no" placeholder=" Issue No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >To Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('to_company_id', $company,'',array('id'=>'to_company_id')) !!}
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver Name </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="driver_name" id="driver_name" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver Contact</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="driver_contact_no" id="driver_contact_no" placeholder="(+88) 01234567890" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver License No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="driver_license_no" id="driver_license_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lock
                                     No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lock_no" id="lock_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Truck
                                     No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="truck_no" id="truck_no" value="" placeholder=" write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Recipient</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="recipient" id="recipient" value="" placeholder=" Write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invgreyfabtransoutFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGreyFabTransOut.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabTransOut.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabTransOut.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGreyFabTransOut.showPdf()">PDF</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGreyFabTransOut.showPdfTwo()">Challan</a>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            
            <div data-options="region:'center',border:true,title:'List',footer:'#invgreyfabtransoutitemTblFt'" style="padding:2px">
                <table id="invgreyfabtransoutitemTbl" style="width:500px">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'rcv_qty'" width="80">Issue Qty</th>
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
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color'" width="80">Dyeing Color</th>

                          
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
                          <th data-options="field:'machine_no'" width="100">M/C No</th>
                          <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                          <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                          <th data-options="field:'shift_name'" width="80">Shift Name</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                        </tr>
                    </thead>
                </table>

                <div id="invgreyfabtransoutitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabTransOutItem.import()">Import</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="invgreyfabtransoutitemsearchwindow" class="easyui-window" title="Grey Fabric Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',footer:'#invgreyfabtransoutitemsearchFrmFt'" style="width:400px; padding:2px">
                <form id="invgreyfabtransoutitemsearchFrm" >
                    <div id="container">
                        <div id="body">
                            <code>  
                                <div class="row">
                                    <div class="col-sm-4 req-text">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="inv_grey_fab_rcv_item_id" id="inv_grey_fab_rcv_item_id" readonly />
                                        <input type="hidden" name="inv_grey_fab_item_id" id="inv_grey_fab_item_id" readonly />
                                        <input type="hidden" name="store_id" id="store_id" readonly />
                                    </div>
                                </div>                             
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" ondblclick="
                                        MsInvGreyFabTransOutItem.openStyleWindow()" placeholder=" Double Click" />
                                        <input type="hidden" name="style_id" id="style_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="
                                        MsInvGreyFabTransOutItem.openOrderWindow()" placeholder=" Double Click" />
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invgreyfabtransoutitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGreyFabTransOutItem.getItem()">Show</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgreyfabtransoutitemsearchFrm')">Reset</a>
                        
                    </div>
                </form>
            </div>
        <div data-options="region:'center'" style="padding:10px;">
            
            <table id="invgreyfabtransoutitemsearchTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'cdf'" width="100" formatter="MsInvGreyFabTransOutItem.formatsv">Action</th>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'rcv_qty'" width="80">Rcv. Qty</th>
                          <th data-options="field:'isu_qty'" width="80">Isu. Qty</th>
                          <th data-options="field:'bal_qty'" width="80"> Bal. Qty</th>
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
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color'" width="80">Dyeing Color</th>

                          
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
                          <th data-options="field:'machine_no'" width="100">M/C No</th>
                          <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                          <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                          <th data-options="field:'shift_name'" width="80">Shift Name</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                        </tr>
                    </thead>
                </table>
            
        </div>

    </div>
</div>

<div id="invgreyfabtransoutitemwindow" class="easyui-window" title="Save/Edit" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:450px;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#invgreyfabtransoutitemFrmFt'" style="padding:10px;">
            <form id="invgreyfabtransoutitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row" style="display: none;">
                                    <div class="col-sm-4 req-text"></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="inv_isu_id" id="inv_isu_id" value="" />
                                        <input type="hidden" name="inv_grey_fab_rcv_item_id" id="inv_grey_fab_rcv_item_id" readonly />
                                        <input type="hidden" name="inv_grey_fab_item_id" id="inv_grey_fab_item_id" readonly />
                                        <input type="hidden" name="store_id" id="store_id" readonly />
                                        <input type="hidden" name="row_index" id="row_index" readonly />
                                    </div>
                                </div>
                                                           
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Roll No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="roll_no" id="roll_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Custom No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="custom_no" id="custom_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Receive Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rcv_qty" id="rcv_qty" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Tot. Issue Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="isu_qty" id="isu_qty" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Balance Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bal_qty" id="bal_qty" class="number integer" readonly />
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
                    <div id="invgreyfabtransoutitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGreyFabTransOutItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgreyfabtransoutitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabTransOutItem.remove()">Delete</a>
                    </div>
                </form>
        </div>
    </div>
</div>
{{-- Style Filtering Search Window --}}
<div id="styleWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="stylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
                                <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                    <div class="col-sm-2 req-text">Style Ref. </div>
                                    <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvGreyFabTransOutItem.searchStyleGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="stylesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'receivedate'" width="80">Receive Date</th>
                        <th data-options="field:'style_ref'" width="120">Style Refference</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                        <th data-options="field:'productdepartment'" width="80">Product Department</th>
                        <th data-options="field:'season'" width="80">Season</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#styleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>  
{{-- Order window --}}
<div id="salesorderWindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="orderdlvprintsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvGreyFabTransOutItem.searchOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'sales_order_id'" width="40">ID</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'company_id'" width="120">Beneficiary</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'style_ref'" width="80">Style Ref</th>
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#salesorderWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>    


  
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GreyFabric/MsAllInvGreyFabTransOutController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
$('#invgreyfabtransoutitemsearchFrm [id="buyer_id"]').combobox();
</script>
    