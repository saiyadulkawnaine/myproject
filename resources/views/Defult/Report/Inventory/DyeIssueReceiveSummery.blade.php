
<div class="easyui-layout animated rollIn"  data-options="fit:true" id="issuesummery">
    <div data-options="region:'center',border:true,title:'Dyes & Chemical Issue Summery Report'" style="padding:2px">
        <table id="dyeissueTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th width="80">1</th>
                    <th width="100" >2</th>
                    <th  width="100">3</th>
                    <th width="150">4</th>
                    <th width="70">5</th>
                    <th width="80">6</th>
                    <th width="80">7</th>
                    <th width="70">8</th>
                    <th width="80">9</th>
                    <th width="70">10</th>
                    <th width="80">11</th>
                    <th width="80">12</th>
                    <th width="70">13</th>
                    <th width="80">14</th>
                    <th width="80">15</th>
                </tr>
                <tr>
                    <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                    <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                    <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                    <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                    <th data-options="field:'consumed_qty',halign:'center'" width="70" align="right" formatter="MsDyeIssueReceiveSummery.formatregulardtl">Consumed<br/> Qty</th>
                    <th data-options="field:'po_rate',halign:'center'" width="80" align="right">PO Rate</th>
                    <th data-options="field:'po_amount',halign:'center'" width="80" align="right">Amount</th>
                    <th data-options="field:'trans_out_qty',halign:'center'" width="70" align="right" formatter="MsDyeIssueReceiveSummery.formattransdtl">Trnf Out<br/> Qty</th>
                    <th data-options="field:'trans_amount',halign:'center'" width="80" align="right">Trnf Amount</th>
                    <th data-options="field:'purchase_rtn_qty',halign:'center'" width="70" align="right" formatter="MsDyeIssueReceiveSummery.formatrcvrtndtl">Purchase <br/>Rtn Qty</th>
                    <th data-options="field:'purchase_rtn_rate',halign:'center'" width="80" align="right">Rate</th>
                    <th data-options="field:'purchase_rtn_amount',halign:'center'" width="80" align="right">Rtn Amount</th>
                    <th data-options="field:'loan_qty',halign:'center'" width="70" align="right" formatter="MsDyeIssueReceiveSummery.formatloandtl">Loan&Others<br/> Qty</th>
                    <th data-options="field:'loan_rate',halign:'center'" width="80" align="right">Rate</th>
                    <th data-options="field:'loan_amount',halign:'center'" width="80" align="right">Amount</th>
                </tr>
            </thead>
        </table>        
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="dyeissuereceivesummeryFrm">
            <div id="container">
                <div id="body">
                    <code>
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
                            <div class="col-sm-4 req-text">Item</div>
                            <div class="col-sm-8">
                                {!! Form::select('identity', $identity,'',array('id'=>'identity')) !!}                        
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Store</div>
                            <div class="col-sm-8">
                                {!! Form::select('store_id', $store,'',array('id'=>'store_id')) !!}                        
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; "  plain="true" id="save" onClick="MsDyeIssueReceiveSummery.getReceive()">Receive</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; "  plain="true" id="save" onClick="MsDyeIssueReceiveSummery.showReceiveExcel()">Receive.XLS</a>
                {{-- <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; "   plain="true" id="save" onClick="MsDyeIssueReceiveSummery.getpdf()">R.PDF</a> --}}
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px;border-radius:1px "  plain="true" id="save" onClick="MsDyeIssueReceiveSummery.getIssue()">Issue</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDyeIssueReceiveSummery.showExcel()">Issue.XLS</a>
            </div>
        </form>
    </div>
</div>

<div id="detailwindow" class="easyui-window" title=" Loan & Other" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="detailTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'issue_date',halign:'center'" width="100">Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                <th data-options="field:'supplier_name',halign:'center'" width="80">Loan To</th>
            </tr>
        </thead>
    </table>
</div>

<div id="transwindow" class="easyui-window" title="Transfer Out" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="transTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'issue_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                <th data-options="field:'to_company',halign:'center'" width="80">To Company</th>
            </tr>
        </thead>
    </table>
</div>

<div id="regularwindow" class="easyui-window" title=" Issues" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="regularTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'issue_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                
            </tr>
        </thead>
    </table>
</div>

<div id="returnwindow" class="easyui-window" title=" Receive Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="returnTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'issue_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Returned<br/>To</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                <th data-options="field:'supplier_name',halign:'center'" width="150">Supplier</th>
            </tr>
        </thead>
    </table>
</div>

<div id="receiveWindow" class="easyui-window" title=" Date Wise Receive Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;" id="rcvsummery">
    <table id="dyereceivesummeryTbl" style="width:1890px">
        <thead>
            <tr>
                <th width="80">1</th>
                <th  width="100" >2</th>
                <th width="100">3</th>
                <th width="150">4</th>
                {{-- <th width="80">5</th>
                <th width="80">6</th>
                <th width="100">7</th>
                <th width="90">8</th> --}}
                <th width="40">5</th>
                <th width="80">6</th>
                {{-- <th width="80">11</th>
                <th width="80" >12</th> --}}
                <th width="80" >7</th>
                {{-- <th width="50">14</th> --}}
                <th width="80" >8</th>
                {{-- <th width="40">16</th> --}}
                <th width="80" >9</th>
                <th  width="80">10</th>
                <th  width="80">11</th>
                {{-- <th  width="80">20</th> --}}
                <th  width="80">12</th>
                <th width="80" >13</th>
                <th width="80">14</th>
                <th width="80">15</th>
            </tr>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                {{-- <th data-options="field:'ref_type'" width="80">Ref. Type</th> --}}
                {{-- <th data-options="field:'trans_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'receive_no',halign:'center'" width="100">Recv.No</th>
                <th data-options="field:'supplier_code',halign:'center'" width="90">Supplier</th> --}}
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right" formatter="MsDyeIssueReceiveSummery.formatrcvregular">Receive<br/> Qty</th>
                <th data-options="field:'rcv_rate',halign:'center'" width="80" align="right">Rate</th>
                {{-- <th data-options="field:'po_currency',halign:'center'" width="80" align="right">PO <br/>Currency</th> --}}
                <th data-options="field:'rcv_amount',halign:'center'" width="80" align="right">Receive<br/>Amount</th>
                {{-- <th data-options="field:'exch_rate',halign:'center'" width="50" align="right">Exch <br/>Rate</th> --}}
                <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
                {{-- <th data-options="field:'from_company_code',halign:'center'" width="40" align="right">Trnf<br/>from</th> --}}
                <th data-options="field:'trans_in_qty',halign:'center'" width="80" align="right" formatter="MsDyeIssueReceiveSummery.formatrcvtransin">Trnf In<br/> Qty</th>
                <th data-options="field:'trans_in_amount',halign:'center'" width="80" align="right">Trnf Amount</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right" formatter="MsDyeIssueReceiveSummery.formatisurtn">Issue Rtn<br/> Qty</th>
                {{-- <th data-options="field:'issue_rtn_rate',halign:'center'" width="80" align="right">Avg Rate</th> --}}
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue <br/> Amount</th>
                <th data-options="field:'loan_qty',halign:'center'" width="80" align="right" formatter="MsDyeIssueReceiveSummery.formatrcvloan">Loan Rcv/<br/>Rtn Qty</th>
                <th data-options="field:'loan_amount',halign:'center'" width="80" align="right">Loan Rcv/<br/>Rtn Amount</th>
            </tr>
        </thead>
    </table> 
</div>


<div id="rcvregularwindow" class="easyui-window" title=" Regular" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="rcvregularTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'receive_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'receive_no',halign:'center'" width="80" align="center">Receive No</th>
                <th data-options="field:'supplier_name',halign:'center'" width="150">Supplier</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Rcv Qty</th>
                <th data-options="field:'rate',halign:'center'" width="70" align="right">Po/Rq Rate</th>
                <th data-options="field:'exch_rate',halign:'center'" width="70" align="right">Exch Rate</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Rcv Amount</th>
                <th data-options="field:'po_no',halign:'center'" width="80" align="center">Po No</th>
                <th data-options="field:'po_qty',halign:'center'" width="70" align="right">Po Qty</th>
                <th data-options="field:'po_amount',halign:'center'" width="70" align="right">Po Amount</th>
                <th data-options="field:'requisition_no',halign:'center'" width="80" align="center">Requisition No</th>
                <th data-options="field:'req_qty',halign:'center'" width="70" align="right">Requisition Qty</th>
                <th data-options="field:'req_amount',halign:'center'" width="70" align="right">Requisition Amount</th>
                <th data-options="field:'store_rate',halign:'center'" width="80" align="right">Store Rate</th>
                <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="rcvtransinwindow" class="easyui-window" title=" Transfer In" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="rcvtransinTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'receive_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'receive_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'from_company',halign:'center'" width="150">From Company</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="isurtnwindow" class="easyui-window" title=" Issue Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="isurtnTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'receive_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'receive_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="rcvloanwindow" class="easyui-window" title=" Loan Receive/Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="rcvloanTbl" style="width:600px">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                <th data-options="field:'receive_date',halign:'center'" width="80">Trans.Date</th>
                <th data-options="field:'receive_no',halign:'center'" width="80" align="center">Issue.No</th>
                <th data-options="field:'supplier_name',halign:'center'" width="150">Supplier</th>
                <th data-options="field:'company_code',halign:'center'" width="70">Company</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">Amount</th>
                <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsDyeIssueReceiveSummeryController.js"></script>
<script>
    (function(){
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
    $('#dyeissuereceiveFrm [id="supplier_id"]').combobox();

})(jQuery);
</script>
