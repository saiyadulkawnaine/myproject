<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comlocalexpinvoicetabs">
    <div title="Invoice And LC Ref." style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="localexpinvoiceTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'local_lc_no'" width="100">Local LC No</th>
                            <th data-options="field:'local_invoice_no'" width="100">Invoice No</th>
                            <th data-options="field:'beneficiary'" width="100">Beneficiary</th>
                            <th data-options="field:'local_invoice_date'" width="100">Invoice Date</th>
                            <th data-options="field:'local_invoice_value'" width="100">Invoice Value</th>
                            <th data-options="field:'actual_delivery_date'" width="100">Actual Delivery Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Invoice',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="localexpinvoiceFrm">
                    <div id="container">
                        <div id="body">
                            <code>  
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />    
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">LC/SC No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="local_lc_no" id="local_lc_no" ondblclick="MsLocalExpInvoice.openlocalExpInvoiceWindow()" placeholder=" Double Click">
                                        <input type="hidden" name="local_exp_lc_id" id="local_exp_lc_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="local_invoice_no" id="local_invoice_no" placeholder="invoice no" />
                                    </div>
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="local_invoice_date" id="local_invoice_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>                          
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice Value </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="local_invoice_value" id="local_invoice_value" class="number integer" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Actual Delv.Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="actual_delivery_date" id="actual_delivery_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>                          
                                <div class="row middle">
                                    <div class="col-sm-4">HS Code</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="hs_code" id="hs_code" placeholder="display" disabled />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_id" id="buyer_id" placeholder="display" disabled />
                                    </div>
                                </div>
                                {{-- <div class="row middle">
                                    <div class="col-sm-4">Applicant </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_id" id="buyer_id" placeholder="display" disabled />
                                    </div>
                                </div>   --}}                             
                                <div class="row middle">
                                    <div class="col-sm-4">Lien Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lien_date" id="lien_date" placeholder="display" disabled />
                                    </div>
                                </div>                  
                                <div class="row middle">
                                    <div class="col-sm-4">Beneficiary </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="beneficiary_id" id="beneficiary_id" placeholder="display" disabled />
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
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpInvoice.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('localexpinvoiceFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpInvoice.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!----------------->
    <div title="PI Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists',footer:'#depft'" style="padding:2px">
                <form id="localexpinvoiceorderFrm">
                    <code id="localexpinvoiceordermatrix"></code>
                    <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpInvoiceOrder.submit()">Save</a>
                    </div>
                </form>
            </div>
        </div>                           
    </div>
</div>

<div id="openinvoicesaleorderWindow" class="easyui-window" title="Color&Size Wise Invoice Qty Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Lists',footer:'#orderdtailft'" style="padding:2px">
            <form id="localexpinvoiceorderdtlFrm">
                <code id="localexpinvoiceorderdtlmatrix">
                    <div class="row">
                        {{-- <input type="hidden" name="exp_invoice_order_id" id="exp_invoice_order_id" /> --}}
                    </div>
                </code>
                <div id="orderdtailft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpInvoiceOrderDtl.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpInvoiceOrderDtl.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpInvoiceOrderDtl.remove()">Delete</a>
                </div>
            </form>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openinvoicesaleorderWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
   <!-------->
<div id="openlocallcwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
     <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
             <div class="easyui-layout" data-options="fit:true">
                 <div id="body">
                     <code>
                        <form id="localexplcsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">LC No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="local_lc_no" id="local_lc_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_date" id="lc_date" value="" class="datepicker" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsLocalExpInvoice.searchLocalExpLcGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="localexplcsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'local_lc_no'" width="100">LC No</th>
                        <th data-options="field:'beneficiary_id'" width="100">Beneficiary</th>
                        <th data-options="field:'buyer_id'" width="100">Buyer</th>
                        <th data-options="field:'lc_date'" width="100">LC Date</th>
                        <th data-options="field:'lc_value'" width="100" align="right">LC value</th>
                        <th data-options="field:'currency'" width="100">Currency</th>
                        <th data-options="field:'exch_rate'" width="100">Exch Rate</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openlocallcwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/LocalExport/MsAllLocalExpInvoiceController.js"></script>
<script>
$(".datepicker").datepicker({
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
 