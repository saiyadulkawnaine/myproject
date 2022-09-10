<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="poyarnapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="poyarnapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsPoYarnApproval.approveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'po_no'" width="80">PO No</th>
                            <th data-options="field:'po_date'" width="80">PO Date</th>
                            <th data-options="field:'company_code'" width="60"  align="center">Company</th>
                            <th data-options="field:'supplier_code'" width="160">Supplier</th>
                            <th data-options="field:'pi_no'" width="160">PI No</th>
                            <th data-options="field:'source'" width="70" align="center">Source</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'delv_start_date'" width="100" align="center">Delivery Start</th>
                            <th data-options="field:'delv_end_date'" width="100" align="center">Delivery End</th>
                            <th data-options="field:'paymode'" width="120">Pay Mode</th>
                            <th data-options="field:'pdf'" width="100" formatter='MsPoYarnApproval.poyarnButton'></th>
                             <th data-options="field:'summerypdf'" width="100" formatter='MsPoYarnApproval.poyarnSummeryButton'></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#poyarnapprovalFrmFt'" style="width:300px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="poyarnapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="poyarnapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poyarnapprovalFrm')">Reset</a>
                    
                </div>
            </div>
        </div>
    </div>

    <div title="Approved List" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="poyarnapprovedTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsPoYarnApproval.unapproveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'po_no'" width="80">PO No</th>
                            <th data-options="field:'po_date'" width="80">PO Date</th>
                            <th data-options="field:'company_code'" width="60"  align="center">Company</th>
                            <th data-options="field:'supplier_code'" width="150">Supplier</th>
                            <th data-options="field:'pi_no'" width="100">PI No</th>
                            <th data-options="field:'source'" width="70">Source</th>
                            <th data-options="field:'po_qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'delv_start_date'" width="80">Delivery Start</th>
                            <th data-options="field:'delv_end_date'" width="80">Delivery End</th>
                            <th data-options="field:'paymode'" width="100">Pay Mode</th>
                            <th data-options="field:'lc_no'" width="200">LC No & LC Date</th>
                            <th data-options="field:'rcv_qty'" align="right" width="70" formatter='MsPoYarnApproval.formatRcvNo'>Receive Qty</th>
                            <th data-options="field:'pdf'" width="70" formatter='MsPoYarnApproval.poyarnButton'></th>
                            <th data-options="field:'summerypdf'" width="100" formatter='MsPoYarnApproval.poyarnSummeryButton'></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#poyarnapprovedFrmFt'" style="width:300px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="poyarnapprovedFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="app_date_from" id="app_date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="app_date_to" id="app_date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="poyarnapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnApproval.getApp()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poyarnapprovedFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="yarnrcvdetailWindow" class="easyui-window" title="Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="yarnrcvdetailTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'po_item_id',halign:'center'" width="100" >Po Item ID</th>
            <th data-options="field:'inv_rcv_item_id',halign:'center'" align="left" width="100">Rcv Item ID</th>
            <th data-options="field:'receive_no'" width="100px">MRR No</th>
            <th data-options="field:'receive_date'" width="100px">Receive Date</th>
            <th data-options="field:'challan_no'" width="100px">Challan No</th>
            <th data-options="field:'qty'" width="100px">Rcv Qty</th>
            <th data-options="field:'rate'" width="100px">Rcv Rate</th>
            <th data-options="field:'amount'" width="100px">Rcv Amount</th>
            <th data-options="field:'store_qty'" width="100px">Store Qty</th>
            <th data-options="field:'store_amount'" width="100px">Store Amount</th>
            <th data-options="field:'remarks'" width="150px">Remarks</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsPoYarnApprovalController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }
  });
  $('#poyarnFrm [id="supplier_id"]').combobox();

</script>
    