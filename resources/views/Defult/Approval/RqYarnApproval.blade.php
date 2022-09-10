<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="rqyarnapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="rqyarnapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="100" formatter='MsRqYarnApproval.approveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'rq_no'" width="100">Req. No</th>
                            <th data-options="field:'pdf'" width="60" formatter='MsRqYarnApproval.rqyarnButton'>PDF</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'basis_name'" width="100">Basis</th>
                            <th data-options="field:'supplier_name'" width="100">Knit Company</th>
                            <th data-options="field:'rq_date'" width="100">Req. Date</th>
                            <th data-options="field:'yarn_qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#rqyarnapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="rqyarnapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Knit Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width:100%;border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Requisition Date</div>
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
                <div id="rqyarnapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRqYarnApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('rqyarnapprovalFrm')">Reset</a>
                    
                </div>
            </div>
        </div>
    </div>

    <div title="Approved List" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="rqyarnapprovedTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="100" formatter='MsRqYarnApproval.unapproveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'rq_no'" width="100">Req. No</th>
                            <th data-options="field:'pdf'" width="60" formatter='MsRqYarnApproval.rqyarnButton'>PDF</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'basis_name'" width="100">Basis</th>
                            <th data-options="field:'supplier_name'" width="100">Knit Company</th>
                            <th data-options="field:'rq_date'" width="100">Req. Date</th>
                            <th data-options="field:'yarn_qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#rqyarnapprovedFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="rqyarnapprovedFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Knit Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width:100%;border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Requisition Date</div>
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
                <div id="rqyarnapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRqYarnApproval.getApp()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('rqyarnapprovedFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsRqYarnApprovalController.js"></script>
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
  $('#rqyarnapprovalFrm [id="supplier_id"]').combobox();
  $('#rqyarnapprovedFrm [id="supplier_id"]').combobox();

</script>
    