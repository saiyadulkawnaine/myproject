
<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yrnisrcv">
    <div data-options="region:'center',border:true,title:'Yarn Receive Issue Report'" style="padding:2px">
        <table id="yarnissuereceiveTbl" style="width:1890px">
            <thead>
                
                <tr>
                    <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                    <th data-options="field:'trans_date',halign:'center'" width="80">Trans.Date</th>
                    <th data-options="field:'receive_no',halign:'center'" width="100">Recv.No</th>
                    <th data-options="field:'receive_basis'" width="80">Rcv<br/>Basis</th>
                    <th data-options="field:'receive_against',halign:'center'" width="100">Receive<br/>Against</th>
                    <th data-options="field:'lc_pi_no',halign:'center'" width="250">LC NO/<br/>PI NO</th>
                    <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                    <th data-options="field:'yarn_count',halign:'center'" width="50">Count</th>
                    <th data-options="field:'composition',halign:'center'" width="80">Composition</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="80">Type</th>
                    <th data-options="field:'yarn_color',halign:'center'" width="60">Color</th>
                    <th data-options="field:'yarn_lot',halign:'center'" width="80">Lot</th>
                    <th data-options="field:'yarn_brand',halign:'center'" width="80">Brand</th>
                    <th data-options="field:'supplier_code',halign:'center'" width="90">Supplier</th>
                    <th data-options="field:'uom_code',halign:'center'" width="40">UOM</th>
                    <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right">Receive<br/> Qty</th>
                    <th data-options="field:'currency_code',halign:'center'" width="80" align="right">PO <br/>Currency</th>
                    <th data-options="field:'po_rate',halign:'center'" width="80" align="right">PO Rate</th>
                    <th data-options="field:'po_amount',halign:'center'" width="80" align="right">Amount-<br/>PO Rate</th>
                    <th data-options="field:'exch_rate',halign:'center'" width="50" align="right">Exch <br/>Rate</th>
                    <th data-options="field:'store_amount',halign:'center'" width="80" align="right">Store Amount</th>
                    <th data-options="field:'issue_rtn_qty',halign:'center'" width="80" align="right">Issue Rtn<br/> Qty</th>
                    <th data-options="field:'issue_rtn_rate',halign:'center'" width="80" align="right">Avg Rate</th>
                    <th data-options="field:'issue_rtn_amount',halign:'center'" width="80" align="right">Rtn<br/> Amount</th>
                    <th data-options="field:'from_company_code',halign:'center'" width="40" align="right">Trnf<br/>from</th>
                    <th data-options="field:'trans_in_qty',halign:'center'" width="80" align="right" >Trnf In<br/> Qty</th>
                    <th data-options="field:'trans_amount',halign:'center'" width="80" align="right">Trnf Amount</th>
                    <th data-options="field:'other_issue_rtn_qty',halign:'center'" width="80" align="right">Issue Rtn<br/>Qty Other</th>
                    <th data-options="field:'other_issue_rtn_rate',halign:'center'" width="80" align="right">Avg Rate</th>
                    <th data-options="field:'other_issue_rtn_amount',halign:'center'" width="80" align="right">Rtn<br/> Amount</th>
                    <th data-options="field:'store_id',halign:'center'" width="120" align="right">Store</th>
                </tr>
            </thead>
        </table>        
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="yarnissuereceiveFrm">
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
                            <div class="col-sm-4 req-text">LC No</div>
                            <div class="col-sm-8">
                                <input type="text" name="lc_no" id="lc_no" ondblclick="MsYarnIssueReceive.openYarnImpLcWndow()" placeholder=" Double Click"/>
                                <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
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
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px;border-radius:1px;"  plain="true" id="save" onClick="MsYarnIssueReceive.getReceive()">Receive</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px;border-radius:1px;"  plain="true" id="save" onClick="MsYarnIssueReceive.showReceiveExcel()">Receive.XLS</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px;" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnIssueReceive.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px;border-radius:1px;"  plain="true" id="save" onClick="MsYarnIssueReceive.getIssue()">Issue</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.showExcel()">Issue.XLS</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.getIssRegular()">Consumption</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.showIssRegularExcel()">Cons.XLS</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.getIssTransfer()">T.out</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.showIssTransExcel()">T.XLS</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.getIssPurRtn()">Purchase Rtn</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px;"  plain="true" id="delete" onClick="MsYarnIssueReceive.showIssPoRtnExcel()">PoRtn.XLS</a>
            </div>
        </form>
    </div>
</div>

<div id="issueWindow" class="easyui-window" title=" Date Wise Issue Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;padding:2px;">
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
    <table id="yarnissueTbl">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">1<br/>Item ID</th>
                <th data-options="field:'trans_date',halign:'center'" width="80">2<br/>Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="100">3<br/>Issue.No</th>
                <th data-options="field:'isu_basis'" width="100">4<br/>Issue<br/>Basis</th>
                <th data-options="field:'issue_against',halign:'center'" width="100">5<br/>Issue<br/>Against</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="60">6<br/>Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="70">7<br/>Item Class</th>
                {{-- <th data-options="field:'lc_pi_no',halign:'center'" width="250">LC NO/<br/>PI NO</th> --}}
                <th data-options="field:'yarn_count',halign:'center'" width="50">8<br/>Count</th>
                <th data-options="field:'composition',halign:'center'" width="80">9<br/>Composition</th>
                <th data-options="field:'yarn_type',halign:'center'" width="80">10<br/>Type</th>
                <th data-options="field:'color_name',halign:'center'" width="60">11<br/>Color</th>
                <th data-options="field:'lot',halign:'center'" width="80">12<br/>Lot</th>
                <th data-options="field:'brand',halign:'center'" width="80">13<br/>Brand</th>
                <th data-options="field:'supplier_name',halign:'center'" width="90">14<br/>Issue To</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">15<br/>UOM</th>
                <th data-options="field:'issue_qty',halign:'center'" width="70" align="right">16<br/>Issue<br/> Qty</th>
                <th data-options="field:'issue_rate',halign:'center'" width="80" align="right">17<br/>Issue Rate</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">18<br/>Amount</th>
                <th data-options="field:'to_company_code',halign:'center'" width="40" align="right">19<br/>Trnf<br/> To</th>
                <th data-options="field:'trans_out_qty',halign:'center'" width="70" align="right">20<br/>Trnf Out<br/> Qty</th>
                <th data-options="field:'trans_out_amount',halign:'center'" width="80" align="right">21<br/>Trnf Amount</th>
                <th data-options="field:'purchase_rtn_qty',halign:'center'" width="70" align="right">22<br/>Purchase <br/>Rtn Qty</th>
                <th data-options="field:'purchase_rtn_rate',halign:'center'" width="80" align="right">23<br/>Rate</th>
                <th data-options="field:'purchase_rtn_amount',halign:'center'" width="80" align="right">24<br/>Rtn Amount</th>
                <th data-options="field:'store_id',halign:'center'" width="120" align="right">25<br/>Store</th>
            </tr>
        </thead>
    </table>
    </div>
</div>
{{-- Yarn Issue Consumption(Regular) --}}
<div id="issregularWindow" class="easyui-window" title=" Date Wise Issue Report : Comsumption(Regular)" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;padding:2px;">
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
    <table id="issregularTbl">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">1<br/>Item ID</th>
                <th data-options="field:'issue_date',halign:'center'" width="80">2<br/>Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="100">3<br/>Issue.No</th>
                <th data-options="field:'issue_against',halign:'center'" width="100">4<br/>Issue<br/>Against</th>
                <th data-options="field:'company_name',halign:'center'" width="60">5<br/>Bnf<br/>Company</th>
                <th data-options="field:'buyer_name',halign:'center'" width="100">6<br/>Buyer</th>
                <th data-options="field:'style_ref',halign:'center'" width="100">7<br/>Style Ref</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="100">8<br/>Order No</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="60">9<br/>Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="70">10<br/>Item Class</th>
                <th data-options="field:'yarn_count',halign:'center'" width="50">11<br/>Count</th>
                <th data-options="field:'composition',halign:'center'" width="80">12<br/>Composition</th>
                <th data-options="field:'yarn_type',halign:'center'" width="80">13<br/>Type</th>
                <th data-options="field:'color_name',halign:'center'" width="60">14<br/>Color</th>
                <th data-options="field:'lot',halign:'center'" width="80">15<br/>Lot</th>
                <th data-options="field:'brand',halign:'center'" width="80">16<br/>Brand</th>
                <th data-options="field:'supplier_name',halign:'center'" width="90">17<br/>Issue To</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">18<br/>UOM</th>
                <th data-options="field:'issue_qty',halign:'center'" width="70" align="right" formatter="MsYarnIssueReceive.formatMrrPo">19<br/>Issue<br/> Qty</th>
                <th data-options="field:'issue_rate',halign:'center'" width="80" align="right">20<br/>Issue Rate</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">21<br/>Amount</th>
                <th data-options="field:'store_id',halign:'center'" width="120">22<br/>Store</th>
            </tr>
        </thead>
    </table>
    </div>
</div>
{{-- Yarn Issue Consumption(Regular) --}}
<div id="isstransoutWindow" class="easyui-window" title=" Date Wise Issue Report : Transfer Out / Purchase Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;padding:2px;">
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
    <table id="isstransferTbl">
        <thead>
            <tr>
                <th data-options="field:'item_account_id',halign:'center'" width="80">1<br/>Item ID</th>
                <th data-options="field:'issue_date',halign:'center'" width="80">2<br/>Trans.Date</th>
                <th data-options="field:'issue_no',halign:'center'" width="100">3<br/>Issue.No</th>
                <th data-options="field:'company_name',halign:'center'" width="70">4<br/>Company</th>
                <th data-options="field:'itemcategory_name',halign:'center'" width="60">5<br/>Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" align="left" width="70">6<br/>Item Class</th>
                <th data-options="field:'yarn_count',halign:'center'" width="50">7<br/>Count</th>
                <th data-options="field:'composition',halign:'center'" width="80">8<br/>Composition</th>
                <th data-options="field:'yarn_type',halign:'center'" width="80">9<br/>Type</th>
                <th data-options="field:'color_name',halign:'center'" width="60">10<br/>Color</th>
                <th data-options="field:'lot',halign:'center'" width="80">11<br/>Lot</th>
                <th data-options="field:'brand',halign:'center'" width="80">12<br/>Brand</th>
                <th data-options="field:'supplier_name',halign:'center'" width="90">13<br/>Return To</th>
                <th data-options="field:'to_company_code',halign:'center'" width="50" align="right">14<br/>Trnf<br/> To</th>
                <th data-options="field:'uom_code',halign:'center'" width="40">15<br/>UOM</th>
                <th data-options="field:'qty',halign:'center'" width="70" align="right" formatter="MsYarnIssueReceive.formatMrrPo">16<br/><br/> Qty</th>
                <th data-options="field:'rate',halign:'center'" width="80" align="right">17<br/> Rate</th>
                <th data-options="field:'amount',halign:'center'" width="80" align="right">18<br/>Amount</th>
                <th data-options="field:'store_id',halign:'center'" width="120">19<br/>Store</th>
            </tr>
        </thead>
    </table>
    </div>
</div>
{{-- MRR PO POP UP WIMDOW --}}
<div id="detailmrrpoWindow" class="easyui-window" title=" Issue Details Window " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:80%;height:500px;padding:2px;margin:5px;">
    <div id="conWindow" style="width:100%;height:100%;padding:0px;">
        <table id="detailmrrpoTbl">
            <thead>
                <tr>
                    <th data-options="field:'receive_no',halign:'center'" width="80">1<br/>MRR NO</th>
                    <th data-options="field:'issue_qty',halign:'center'" width="80">2<br/>Issue Qty</th>
                    <th data-options="field:'store_rate',halign:'center'" width="100">3<br/>MRR Rate</th>
                    <th data-options="field:'store_amount',halign:'center'" width="100">4<br/>Store<br/>Amount</th>
                    <th data-options="field:'po_no',halign:'center'" width="100">5<br/>PO No</th>
                    <th data-options="field:'po_rate',halign:'center'" width="100">6<br/>PO Rate</th>
                    <th data-options="field:'po_amount',halign:'center'" width="100">7<br/>PO Amount</th>
                    <th data-options="field:'exch_rate',halign:'center'" width="100">8<br/>Exch Rate</th>
                    <th data-options="field:'pi_no',halign:'center'" width="200">9<br/>PI No</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
{{-- Import LC POP UP Window --}}
<div id="yarnImpLcWindow" class="easyui-window" title="Import LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="yarnimplcsearchFrm">
                            <div class="row middle">
                               <div class="col-sm-4">Company</div>
                               <div class="col-sm-8">
                                  {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Supplier</div>
                               <div class="col-sm-8">
                                  {!! Form::select('supplier_id',
                                  $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">LC Type</div>
                               <div class="col-sm-8">
                                  {!! Form::select('lc_type_id',$lctype,'',array('id'=>'lc_type_id')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">IssuingBank Branch</div>
                               <div class="col-sm-8">
                                  {!! Form::select('issuing_bank_branch_id',$bankbranch,'',array('id'=>'issuing_bank_branch_id')) !!}
                               </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsYarnIssueReceive.searchYarnImpLc()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="yarnimplcsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'lc_no'" width="100">Import LC No</th>
                        <th data-options="field:'supplier_id'" width="100">Supplier</th>
                        <th data-options="field:'pay_term_id'" width="100">Pay Term</th>
                        <th data-options="field:'company_id'" width="100">Importer</th>
                        <th data-options="field:'lc_type_id'" width="100">L/C Type</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#yarnImpLcWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnIssueReceiveController.js"></script>
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
    $('#yarnissuereceiveFrm [id="supplier_id"]').combobox();

})(jQuery);
</script>