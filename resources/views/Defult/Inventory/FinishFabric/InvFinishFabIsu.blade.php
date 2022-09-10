<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invfinishfabisutabs">
    <div title="Finish Fabric Issue Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invfinishfabisuTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'issue_no'" width="100">Challan No</th>
                            <th data-options="field:'supplier_name'" width="100">Issue To</th>
                            <th data-options="field:'isu_basis_id'" width="100">Issue Basis</th>
                            <th data-options="field:'issue_date'" width="80">Issue Date</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="invfinishfabisuFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_no" id="issue_no" placeholder=" Challan No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Basis </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('isu_basis_id', $invissuebasis,'',array('id'=>'isu_basis_id')) !!}
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue To </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Customer </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Location </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px')) !!}
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
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabIsu.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabIsu.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabIsu.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvFinishFabIsu.showPdf()">PDF</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvFinishFabIsu.showPdfTwo()">Challan</a>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#invfinishfabisuitemTblFt'" style="padding:2px">
                <table id="invfinishfabisuitemTbl" style="width:500px">
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
                          <th data-options="field:'batch_color_name'" width="80">Fabric Color</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Knit. Ref</th>
                          <th data-options="field:'dyeing_batch_no'" width="70">Dyeing Batch</th>
                          <th data-options="field:'aop_batch_no'" width="70">AOP Batch</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                        </tr>
                    </thead>
                </table>

                <div id="invfinishfabisuitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabIsuItem.import()">Import</a>
                </div>
            </div>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvFinishFabIsuItem.searchStyleGrid()">Search</a>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvFinishFabIsuItem.searchOrderGrid()">Search</a>
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
<div id="invfinishfabisuitemsearchwindow" class="easyui-window" title="Finish Fabric Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',footer:'#invfinishfabisuitemsearchFrmFt'" style="width:400px; padding:2px">
                <form id="invfinishfabisuitemsearchFrm" >
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Roll Type</div>
                                    <div class="col-sm-8">
                                        <select name="receive_against_id" id="receive_against_id">
                                            <option value="">Select</option>
                                            <option value="285">Dyeing Roll</option>
                                            <option value="287">AOP Roll</option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="inv_finish_fab_rcv_item_id" id="inv_finish_fab_rcv_item_id" readonly />
                                        <input type="hidden" name="inv_finish_fab_item_id" id="inv_finish_fab_item_id" readonly />
                                        <input type="hidden" name="store_id" id="store_id" readonly />
                                    </div>
                                </div>                             
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" ondblclick="
                                        MsInvFinishFabIsuItem.openStyleWindow()" placeholder=" Double Click" />
                                        <input type="hidden" name="style_id" id="style_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="
                                        MsInvFinishFabIsuItem.openOrderWindow()" placeholder=" Double Click" />
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Total</div>
                                    <div class="col-sm-8" id="invfinishfabisuitemsearchFrmTotal">
                                        
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invfinishfabisuitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabIsuItem.getItem()">Show</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabisuitemsearchFrm')">Reset</a>
                        
                    </div>
                </form>
            </div>
        <div data-options="region:'center'" style="padding:10px;">
            
            <table id="invfinishfabisuitemsearchTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'cdf'" width="100" formatter="MsInvFinishFabIsuItem.formatsv">Action</th>
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
                          <th data-options="field:'batch_color_name'" width="80">Fabric Color</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>

                          
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Knit. Ref</th>
                          <th data-options="field:'dyeing_batch_no'" width="70">Dyeing Batch</th>
                          <th data-options="field:'aop_batch_no'" width="70">AOP Batch</th>
                          
                          
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                        </tr>
                    </thead>
                </table>
            
        </div>

    </div>
</div>
<div id="invfinishfabisuitemwindow" class="easyui-window" title="Save/Edit" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:450px;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#invfinishfabisuitemFrmFt'" style="padding:10px;">
            <form id="invfinishfabisuitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row" style="display: none;">
                                    <div class="col-sm-4 req-text"></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="inv_isu_id" id="inv_isu_id" value="" />
                                        <input type="hidden" name="inv_finish_fab_rcv_item_id" id="inv_finish_fab_rcv_item_id" readonly />
                                        <input type="hidden" name="inv_finish_fab_item_id" id="inv_finish_fab_item_id" readonly />
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
                    <div id="invfinishfabisuitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabIsuItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabisuitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabIsuItem.remove()">Delete</a>
                    </div>
                </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/FinishFabric/MsAllInvFinishFabIsuController.js"></script>
<script>
    $('#invfinishfabisuFrm [id="supplier_id"]').combobox();
    $('#invfinishfabisuFrm [id="buyer_id"]').combobox();
    $('#invfinishfabisuitemsearchFrm [id="buyer_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
