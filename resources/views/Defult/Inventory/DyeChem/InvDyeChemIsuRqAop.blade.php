<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invdyechemisurqaoptabs">
    <div title="Dyes & Chem Requisition Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqaopTblFt'" style="padding:2px">
                <table id="invdyechemisurqaopTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'buyer_id'" width="100">Buyer</th>
                            <th data-options="field:'batch_no'" width="100">Batch No</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            <th data-options="field:'fabric_color'" width="80">Design  Color</th>
                            <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                            <th data-options="field:'design_no'" width="80">Design  No</th>
                            <th data-options="field:'paste_wgt'" width="80">Paste Wg</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wg</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                 <div id="invdyechemisurqaopTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    
                    Requi. Date: <input type="text" name="from_rq_date" id="from_rq_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_rq_date" id="to_rq_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsInvDyeChemIsuRqAop.searchRq()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invdyechemisurqaopFrmFt'" style="width: 400px; padding:2px">
                <form id="invdyechemisurqaopFrm">
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
                                        <input type="text" name="batch_no" id="batch_no" ondblclick="MsInvDyeChemIsuRqAop.openbatchWindow()" class="number integer" readonly/>
                                         <input type="hidden" name="prod_aop_batch_id" id="prod_aop_batch_id" value="" />
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
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px')) !!}
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
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Design No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="design_no" id="design_no" class="number integer" disabled />
                                    </div>
                                </div>

                                

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Paste Wgt</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paste_wgt" id="paste_wgt" class="number integer" readonly/>
                                    </div>
                                </div>

                                  <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Wgt</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" class="number integer" readonly />
                                    </div>
                                </div>
                                

                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px','disabled'=>'disabled')) !!}
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
                    <div id="invdyechemisurqaopFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqAop.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqaopFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqAop.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvDyeChemIsuRqAop.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invdyechemisurqitemaopFrmFt'" style="width:400px; padding:2px">
                <form id="invdyechemisurqitemaopFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                {{-- <div class="row">
                                    <div class="col-sm-4 req-text">Sales Orders</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" ondblclick="MsInvDyeChemIsuRqItemAop.openorderWindow()" readonly placeholder="Double Click"    />
                                        <input type="hidden" name="so_aop_id" id="so_aop_id"  readonly />
                                    </div>
                                </div> --}}
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item ID</div>
                                    <div class="col-sm-8">
                                        
                                        <input type="text" name="item_account_id" id="item_account_id" ondblclick="MsInvDyeChemIsuRqItemAop.openitemWindow()" readonly placeholder="Double Click"    />
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
                                    <div class="col-sm-4" >Print Type</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('print_type_id', $aoptype,'',array('id'=>'print_type_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="inv_dye_chem_isu_rq_id" id="inv_dye_chem_isu_rq_id" readonly />
                                    </div>
                                </div>
                                
                                
                                
                               <div class="row middle">
                                    <div class="col-sm-4 req-text">Ratio on Paste Wgt</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rto_on_paste_wgt" id="rto_on_paste_wgt" value="" class="number integer" onchange="MsInvDyeChemIsuRqItemAop.calculate_qty_form('per_on_batch_wgt')" />
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

                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-5">
                                        
                                        <input type="text" name="master_rq_no" id="master_rq_no" ondblclick="MsInvDyeChemIsuRqItemAop.openrequisitionWindow()" readonly placeholder="Double Click"    />
                                        <input type="hidden" name="master_rq_id" id="master_rq_id"  readonly />
                                    </div>
                                    <div class="col-sm-3">
                                        
                                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqItemAop.itemCopy()">Copy</a>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invdyechemisurqitemaopFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuRqItemAop.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemisurqitemaopFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAop.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqitemaopTblFt'" style="padding:2px">
                <table id="invdyechemisurqitemaopTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'print_type'" width="100">Print Type</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'rto_on_paste_wgt'" width="100" align="right">Ratio on Paste Wgt</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'sort_id'" width="100">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Old List" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemisurqaopoldTblFt'" style="padding:2px">
                <table id="invdyechemisurqaopoldTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'old_pdf_id'" width="60" formatter='MsInvDyeChemIsuRqAop.formatoldpdf'>PDF</th>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'buyer_id'" width="100">Buyer</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            <th data-options="field:'fabric_color'" width="80">Design  Color</th>
                            <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                            <th data-options="field:'design_no'" width="80">Design  No</th>
                            <th data-options="field:'paste_wgt'" width="80">Paste Wg</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wg</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>





<div id="invdyechemisurqitemaopsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqitemaopsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqitemaopsearchFrm">
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
                <div id="invdyechemisurqitemaopsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAop.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqitemaopsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqitemaopsearchTbl" style="width:100%">
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


<div id="invdyechemisurqorderaopsearchwindow" class="easyui-window" title=" Sales Order" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqorderaopsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqorderaopsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Order No</div>
                            <div class="col-sm-8">
                            <input type="text" name="order_no" id="order_no" />
                            </div>
                            </div>
                            
                        </form>
                    </code>
                </div>
                <div id="invdyechemisurqorderaopsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAop.serachOrder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invdyechemisurqorderaopsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'receive_date'" width="100">Rceive Date</th>
                            <th data-options="field:'currency_id'" width="70">Currency</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="invDyechemMasterRqAopSearchWindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invDyechemMasterRqAopSearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invDyechemMasterRqAopSearchFrm">
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
                <div id="invDyechemMasterRqAopSearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqItemAop.serachMasterRq()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invDyechemMasterRqAopSearchTbl" style="width:100%">
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
                            <th data-options="field:'design_no'" width="80">Design No</th>
                            <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                            <th data-options="field:'paste_wgt'" width="80">Past Wgt</th>
                            <th data-options="field:'liqure_ratio'" width="80">Liqure Ratio</th>
                            <th data-options="field:'liqure_wgt'" width="80">Liqure Wgt.</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="invdyechemisurqaopbatchsearchwindow" class="easyui-window" title="Batch" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemisurqbatchsearchFrmft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemisurqaopbatchsearchFrm">
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
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemIsuRqAop.serachBatch()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemisurqbatchsearchTblFt'" style="padding:10px;">
            <table id="invdyechemisurqaopbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'company_code'" width="80">Company</th>
                        <th data-options="field:'customer_name'" width="100">Customer</th>
                        <th data-options="field:'batch_no'" width="80">Batch No</th>
                        <th data-options="field:'batch_date'" width="100">Batch Date</th>
                        <th data-options="field:'batch_for'" width="100">Batch For</th>
                        <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                        <th data-options="field:'design_no'" width="80">Design No</th>
                        <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                        <th data-options="field:'paste_wgt'" width="80">Paste Wgt</th>
                        <th data-options="field:'remarks'" width="80">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqAopController.js"></script>
<script>
    $('#invdyechemisurqaopFrm [id="buyer_id"]').combobox();
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
