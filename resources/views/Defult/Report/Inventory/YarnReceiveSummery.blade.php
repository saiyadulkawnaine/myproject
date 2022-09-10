<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yarnreceivesummeryPanel">
    <div data-options="region:'center',border:true,title:'Yarn Receive Summery Report'" style="padding:2px">
                <table id="yarnreceivesummeryTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'company_name',halign:'center'" width="60">Company</th>
                            <th data-options="field:'receive_no',halign:'center'" width="80" align="center">MRR No</th>
                            <th data-options="field:'supplier_name',halign:'center'" width="150">Supplier</th>
                            
                            <th data-options="field:'receive_date',halign:'center'" width="80">Receive Date</th>
                            <th data-options="field:'challan_no',halign:'center'" width="80">Challan No</th>
                            <th data-options="field:'count_name',halign:'center'" align="center" width="70">Count</th>
                            <th data-options="field:'composition',halign:'center'" align="left" width="180">Yarn Description.</th>
                            <th data-options="field:'yarn_type',halign:'center'" width="60">Type</th>
                            <th data-options="field:'lot',halign:'center'" width="70">Lot</th>
                            <th data-options="field:'brand',halign:'center'" width="70">Barnd</th>
                            <th data-options="field:'yarn_color',halign:'center'" width="70">Color</th>
                            <th data-options="field:'qty',halign:'center'" width="80" align="right"> Qty</th>
                            <th data-options="field:'uom',halign:'center'" width="50" align="center">UOM</th>
                            <th data-options="field:'rate',halign:'center'" width="60" align="right">Rate</th>
                            <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                            <th data-options="field:'exch_rate',halign:'center'" width="60" align="right">Exch. Rate</th>
                            <th data-options="field:'store_rate',halign:'center'" width="60" align="right">Store Rate</th>
                            <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
                            <th data-options="field:'no_of_bag',halign:'center'" width="60" align="right">No Of Bag</th>
                            <th data-options="field:'wgt_per_bag',halign:'center'" width="60" align="right">Wgt/Bag</th>
                            <th data-options="field:'remarks',halign:'center'" width="80" align="right">Remarks</th>
                        </tr>
                    </thead>
                </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="yarnreceivesummeryFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}                        
                    </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Supplier</div>
                        <div class="col-sm-8">
                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}                        
                    </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Date Range</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                        </div>

                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">MRR No</div>
                        <div class="col-sm-8">
                            <input type="text" name="receive_no" id="receive_no" placeholder="Double Click" ondblclick="MsYarnReceiveSummery.openMrrWindow()" />
                            <input type="hidden" name="id" id="id" readonly />
                        </div>
                    </div>
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnReceiveSummery.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnReceiveSummery.resetForm('yarnreceivesummeryFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="msApp.toExcel('yarnreceivesummeryTbl','')">Excel</a>
            </div>
      </form>
    </div>
</div>

<div id="yarnreceivesummerymrrWindow" class="easyui-window" title="MRR" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'MRR',footer:'#yarnreceivesummerymrrTblFT'" style="padding:2px">
<table id="yarnreceivesummerymrrTbl">
<thead>
<tr>
<th data-options="field:'id'" width="40">ID</th>
<th data-options="field:'inv_yarn_rcv_id'" width="40">Yarn Rcv. ID</th>
<th data-options="field:'company_id'" width="100">Company</th>
<th data-options="field:'receive_no'" width="100">Receive No</th>
<th data-options="field:'receive_basis_id'" width="100">Receive Basis</th>
<th data-options="field:'challan_no'" width="80">Challan No</th>
<th data-options="field:'receive_date'" width="80">Receive Date</th>
<th data-options="field:'supplier_id'" width="120">Supplier</th>
<th data-options="field:'po_no'" width="80">PO NO</th>
<th data-options="field:'pi_no'" width="150">PI NO</th>
<th data-options="field:'lc_no'" width="120">LC NO</th>
</tr>
</thead>
</table>
<div id="yarnreceivesummerymrrTblFT" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnReceiveSummery.closeMrrWindow()">Close</a>

</div>
</div>
</div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnReceiveSummeryController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('#yarnreceivesummeryFrm [id="supplier_id"]').combobox();
</script>