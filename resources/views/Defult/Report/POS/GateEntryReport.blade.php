<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Gate In Entry Report - Goods Only'" style="padding:2px;height:100%">
        <table id="gateentryreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'itemcategory_name',halign:'center'" width="100">Item Category</th>
                    <th data-options="field:'itemclass_name',halign:'center'" width="100">Item Class</th>
                    <th data-options="field:'entry_date',halign:'center'" width="80">Entry Date</th>
                    <th data-options="field:'create_user_name',halign:'center'" width="100">Entered By</th>
                    <th data-options="field:'company_code',halign:'center'" width="60">Company</th>
                    <th data-options="field:'po_pr_no',halign:'center'" width="100">PO/PR No</th>
                    <th data-options="field:'barcode_no_id',halign:'center'" width="100">Barcode No</th>
                    <th data-options="field:'challan_no',halign:'center'" width="100">Challan No</th>
                    <th data-options="field:'item_description',halign:'center'" width="180">Description</th>
                    <th data-options="field:'uom_code',halign:'center'" width="60">UOM</th>
                    <th data-options="field:'qty',halign:'center'" width="100" align="right">Qty</th>
                    <th data-options="field:'amount',halign:'center'" width="100" align="right">Amount</th>
                    <th data-options="field:'supplier_name',halign:'center'" width="180">Supplier</th>
                    <th data-options="field:'remarks',halign:'center'" width="100">Remarks</th>
                    <th data-options="field:'receive_no',halign:'center'" width="100">MRR No</th>
                    <th data-options="field:'receive_date',halign:'center'" width="100">Rcv Date</th>
                    <th data-options="field:'rcv_qty',halign:'center'" width="100" align="right">Rcv Qty</th>
                </tr>
            </thead>
        </table>
    </div>    
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="gateentryreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Purchase Through</div>
                            <div class="col-sm-7">
                                {!! Form::select('menu_id', $menu,'',array('id'=>'menu_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Date Range</div>
                            <div class="col-sm-3" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Company</div>
                            <div class="col-sm-7">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Supplier</div>
                            <div class="col-sm-7">
                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">PO/PR No</div>
                            <div class="col-sm-7">
                                <input type="text" name="po_pr_no" id="po_pr_no" ondblclick="MsGateEntryReport.openPoPrWindow()" placeholder=" Double Click">
                                <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="" />
                            </div>
                        </div>        
                        {{-- <div class="row middle">
                            <div class="col-sm-4">PR No</div>
                            <div class="col-sm-8">
                                <input type="text" name="requisition_no" id="requisition_no" placeholder="Write"/>
                            </div>
                        </div> --}}
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGateEntryReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGateEntryReport.resetForm('gateentryreportFrm')">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGateEntryReport.showExcel()">Excel</a>
            </div>
        </form>
    </div>
</div>

<div id="poWindow" class="easyui-window" title="PO/PR Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="poprsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">PR/PO No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="pr_po_no" id="pr_po_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">PR/PO Date</div>
                                <div class="col-sm-8">
                                   <input type="text" name="po_pr_date" id="po_pr_date" value="" class="datepicker"/>
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsGateEntryReport.searchPoGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="prposearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'purchase_order_id'" width="70">ID</th>
                        <th data-options="field:'po_pr_no'" width="100">Pr/PO</th>
                        <th data-options="field:'po_pr_date'" width="100">PO/Requisition Date</th>
                         <th data-options="field:'company_name'" width="100">Company</th>
                         <th data-options="field:'supplier_name'" width="100">Supplier</th>
                        <th data-options="field:'master_remarks'" width="200">Remarks</th>    
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#poWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
   
<script type="text/javascript" src="<?php echo url('/');?>/js/report/POS/MsGateEntryReportController.js"></script>
<script>
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

        $('#gateentryreportFrm [id="supplier_id"]').combobox();

   })(jQuery);
</script>