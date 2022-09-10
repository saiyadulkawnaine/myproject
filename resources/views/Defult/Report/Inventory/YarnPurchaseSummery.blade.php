<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yarnpurchasesummeryPanel">
    <div data-options="region:'center',border:true,title:'Yarn Purchase Summery Report'" style="padding:2px">
                <table id="yarnpurchasesummeryTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                            <th data-options="field:'count_name',halign:'center'" align="center" width="70">Count</th>
                            <th data-options="field:'composition',halign:'center'" align="left" width="250">Yarn Description.</th>
                            <th data-options="field:'yarn_type',halign:'center'" width="100">Type</th>
                            <th data-options="field:'qty',halign:'center'" width="80" align="right" formatter="MsYarnPurchaseSummery.formatRcvQty"> Qty</th>
                            <th data-options="field:'uom',halign:'center'" width="80" align="center">UOM</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right">Avg. Rate</th>
                            <th data-options="field:'amount',halign:'center'" width="100" align="right">Amount (BDT)</th>
                            <th data-options="field:'no_of_bag',halign:'center'" width="80" align="right">No Of Bag</th>
                        </tr>
                    </thead>
                </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="yarnpurchasesummeryFrm">
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
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnPurchaseSummery.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnPurchaseSummery.resetForm('yarnpurchasesummeryFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>

<div id="yarnpursummeryrcvqtydtlWindow" class="easyui-window" title="Receive Qty Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="yarnpursummeryrcvqtydtlTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'supplier_name'" width="180px">Supplier</th>
            <th data-options="field:'yarn_count'" width="70px">Count</th>
            <th data-options="field:'composition'" width="150px">Composition</th>
            <th data-options="field:'yarn_type'" width="80px">Type</th>
            <th data-options="field:'qty'" width="100px" align="right">Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'amount'" width="100px" align="right">Amount</th>
            <th data-options="field:'no_of_bag'" width="80px" align="right">No Of Bag</th>
            </tr>
        </thead>
    </table>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnPurchaseSummeryController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('#yarnpurchasesummeryFrm [id="supplier_id"]').combobox();
</script>