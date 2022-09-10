<div class="easyui-layout animated rollIn"  data-options="fit:true" id="subconaoptargetpanel">
    <div data-options="region:'center',border:true,title:'AOP Party Wise Fabric Stock Report'" style="padding:2px">
        
        
                <table id="subconaoptargetTbl" style="width:1890px">
                    <thead>
                        <tr>
                            
                            <th data-options="field:'buyer_name'" width="180" formatter="MsSubconAopTarget.formatContact">Customer</th>
                             <th data-options="field:'team_leader_name'" width="120">Marketeer</th>
                            <th data-options="field:'company_name'" width="60">Company</th>
                            <th data-options="field:'execute_month'" width="100">Prod.month</th>
                            <th data-options="field:'qty'" width="80" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate/Unit</th>
                            <th data-options="field:'amount'" width="70" align="right">Amount</th>
                            <th data-options="field:'receive_qty'" width="80" align="right">Grey Received</th>
                            <th data-options="field:'bal_qty'" width="80" align="right">Balance Qty</th>
                            <th data-options="field:'receive_per'" width="80" align="right">Receive %</th>
                            <th data-options="field:'fin_qty'" width="80" align="right">Dlv. Qty</th>
                            <th data-options="field:'grey_used_qty'" width="80" align="right">Grey Used Qty</th>
                            <th data-options="field:'grey_used_amount'" width="80" align="right">Bill Amount</th>

                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#subconaoptargetFrmFt'" style="width:350px; padding:2px">
        <form id="subconaoptargetFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Date Range</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" value="{{$from}}" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" value="{{$to}}"/>
                        </div>
                    </div>
                      
                    
                              
                </code>
            </div>
            </div>
            <div id="subconaoptargetFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubconAopTarget.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubconAopTarget.resetForm('subconaoptargetFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
<div id="subconaoptargetWindow" class="easyui-window" title="Buyer Contact" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subconaoptargetcontactTbl">
            <thead>
                <tr>
                    <th data-options="field:'buyer_name',halign:'center'" width="150" >Buyer</th>
                    <th data-options="field:'contact_person',halign:'center'" width="150" >Contact Person</th>
                    <th data-options="field:'designation',halign:'center'" width="120" >Designation</th>
                    <th data-options="field:'email',halign:'center'" width="150" >Email</th>
                    <th data-options="field:'cell_no',halign:'center'" width="150" >Cell No</th>
                    <th data-options="field:'address',halign:'center'" width="200" >Address</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/AOP/MsSubconAopTargetController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>