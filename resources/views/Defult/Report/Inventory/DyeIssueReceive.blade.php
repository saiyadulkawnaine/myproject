
<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yystck">
    <div data-options="region:'center',border:true,title:'Dyes & Chemical Receive Issue Report'" style="padding:2px">
        <table id="dyeissuereceiveTbl" style="width:1890px">
            <thead>
                <tr>
                    <th width="80">1</th>
                    <th  width="100" >2</th>
                    <th width="100">3</th>
                    <th width="150">4</th>
                    <th width="80">5</th>
                    <th width="80">6</th>
                    <th width="100">7</th>
                    <th width="120">8</th>
                    <th width="40">9</th>
                    <th width="80">10</th>
                    <th width="80">11</th>
                    <th width="80">12</th>
                    <th width="80">13</th>
                    <th width="50">14</th>
                    <th width="80">15</th>
                    <th width="40">16</th>
                    <th width="80">17</th>
                    <th  width="80">18</th>
                    <th  width="80">19</th>
                    <th  width="80">20</th>
                    <th  width="80">21</th>
                </tr>
                <tr>
                    <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                    <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                    <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                    <th data-options="field:'item_desc',halign:'center'" width="150">Item Description</th>
                    <th data-options="field:'ref_type'" width="80">Ref. Type</th>
                    <th data-options="field:'trans_date',halign:'center'" width="80">Trans.Date</th>
                    <th data-options="field:'receive_no',halign:'center'" width="100">Recv.No</th>
                    <th data-options="field:'supplier_code',halign:'center'" width="120">Supplier</th>
                    <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                    <th data-options="field:'pur_qty',halign:'center'" width="80" align="right">Purchase<br/> Qty</th>
                    <th data-options="field:'po_currency',halign:'center'" width="80" align="right">PO <br/>Currency</th>
                    <th data-options="field:'po_rate',halign:'center'" width="80" align="right">PO Rate</th>
                    <th data-options="field:'po_amount',halign:'center'" width="80" align="right">Amount-<br/>PO Rate</th>
                    <th data-options="field:'exch_rate',halign:'center'" width="50" align="right">Exch <br/>Rate</th>
                    <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
                    <th data-options="field:'from_company_code',halign:'center'" width="40" align="right">Trnf<br/>from</th>
                    <th data-options="field:'trans_in_qty',halign:'center'" width="80" align="right" >Trnf In<br/> Qty</th>
                    <th data-options="field:'trans_amount',halign:'center'" width="80" align="right">Trnf Amount</th>
                    <th data-options="field:'issue_rtn_qty',halign:'center'" width="80" align="right">Issue Rtn<br/> Qty</th>
                    <th data-options="field:'issue_rtn_rate',halign:'center'" width="80" align="right">Avg Rate</th>
                    <th data-options="field:'issue_rtn_amount',halign:'center'" width="80" align="right">Issue Rtn<br/> Amount</th>
                </tr>
            </thead>
        </table>        
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="dyeissuereceiveFrm">
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
                            <div class="col-sm-4">Store</div>
                            <div class="col-sm-8">
                                {!! Form::select('store_id', $store,'',array('id'=>'store_id')) !!}                        
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; "  plain="true" id="save" onClick="MsDyeIssueReceive.getReceive()">Receive</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; "  plain="true" id="save" onClick="MsDyeIssueReceive.showReceiveExcel()">Receive.XLS</a>
                {{-- <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; "   plain="true" id="save" onClick="MsDyeIssueReceive.getpdf()">R.PDF</a> --}}
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; "  plain="true" id="save" onClick="MsDyeIssueReceive.getIssue()">Issue</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;"  plain="true" id="delete" onClick="MsDyeIssueReceive.showExcel()">Issue.XLS</a>
            </div>
        </form>
    </div>
</div>

<div id="issueWindow" class="easyui-window" title=" Date Wise Issue Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;">
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="dyeissueTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th width="80">1</th>
                    <th width="120">2</th>
                    <th width="80">3</th>
                    <th width="100">4</th>
                    <th width="150">5</th>
                    <th width="80">6</th>
                    <th width="80">7</th>
                    <th width="40">8</th>
                    <th width="70">9</th>
                    <th width="80">10</th>
                    <th width="80">11</th>
                    <th width="40">12</th>
                    <th width="70">13</th>
                    <th width="80">14</th>
                    <th width="70">15</th>
                    <th width="80">16</th>
                    <th width="80">17</th>
                    <th width="70">18</th>
                    <th width="80">19</th>
                    <th width="80">20</th>
                    <th width="70">21</th>
                    <th width="80">22</th>
                    <th width="80">23</th>
                    <th width="70">24</th>
                    <th width="80">25</th>
                    <th width="80">26</th>
                </tr>
                <tr>
                    <th data-options="field:'item_account_id'" width="80">Item ID</th>
                    <th data-options="field:'issue_type'" width="120">Issue Type</th>
                    <th data-options="field:'itemcategory_name'" width="80" >Category</th>
                    <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                    <th data-options="field:'item_desc'" width="150">Item Description</th>
                    <th data-options="field:'trans_date'" width="80">Trans.Date</th>
                    <th data-options="field:'issue_no'" width="80">Issue.No</th>
                    <th data-options="field:'uom_code'" width="40">UOM</th>
                    <th data-options="field:'consumed_qty'" width="70" align="right">Consumed<br/> Qty</th>
                    <th data-options="field:'po_rate'" width="80" align="right">PO Rate</th>
                    <th data-options="field:'po_amount'" width="80" align="right">Amount</th>
                    <th data-options="field:'to_company_code'" width="40" align="right">Trnf<br/> To</th>
                    <th data-options="field:'trans_out_qty'" width="70" align="right">Trnf Out<br/> Qty</th>
                    <th data-options="field:'trans_amount'" width="80" align="right">Trnf Amount</th>
                    <th data-options="field:'purchase_rtn_qty'" width="70" align="right">Purchase <br/>Rtn Qty</th>
                    <th data-options="field:'purchase_rtn_rate'" width="80" align="right">Rate</th>
                    <th data-options="field:'purchase_rtn_amount'" width="80" align="right">Rtn Amount</th>
                    <th data-options="field:'loan_qty'" width="70" align="right">Loan<br/> Qty</th>
                    <th data-options="field:'loan_rate'" width="80" align="right">Rate</th>
                    <th data-options="field:'loan_amount'" width="80" align="right">Amount</th>
                    <th data-options="field:'other_loan_qty'" width="70" align="right">Others<br/> Qty</th>
                    <th data-options="field:'other_loan_rate'" width="80" align="right">Rate</th>
                    <th data-options="field:'other_loan_amount'" width="80" align="right">Amount</th>
                    <th data-options="field:'machine_wash_qty'" width="70" align="right">Machine <br/>Wash Qty</th>
                    <th data-options="field:'machine_wash_rate'" width="80" align="right">Rate</th>
                    <th data-options="field:'machine_wash_amount'" width="80" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsDyeIssueReceiveController.js"></script>
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