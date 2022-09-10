<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comexppartyaccepttabs">
    <div title="LC Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="localexpdocsubacceptTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'local_lc_no'" width="150">LC</th>
                            <th data-options="field:'submission_date'" width="80">Submission<br/> Date</th>
                            <th data-options="field:'buyer_name'" width="150">Buyer</th>
                            <th data-options="field:'courier_recpt_no'" width="100">Courier <br/>Receipt No</th>
                            <th data-options="field:'courier_company'" width="100">Courier <br/>Company</th>
                            <th data-options="field:'accept_receive_date'" width="80">Acceptance <br/>Rcv.Date</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                            <th data-options="field:'beneficiary'" width="120">Beneficiary</th>
                            <th data-options="field:'buyers_bank'" width="180">Buyer Bank</th>
                            <th data-options="field:'currency_code'" width="70">Currency</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Doc Submission for acceptance',footer:'#ft2'" style="width: 370px; padding:2px">
                <form id="localexpdocsubacceptFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5">LC</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="local_lc_no" id="local_lc_no" ondblclick="MsLocalExpDocSubAccept.openDocSubAcceptWindow()" placeholder=" Double Click">
                                        <input type="hidden" name="local_exp_lc_id" id="local_exp_lc_id" value="" />
                                        <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Submission Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="submission_date" id="submission_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>                           
                                <div class="row middle">
                                    <div class="col-sm-5">Submitted By </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="submitted_by" id="submitted_by" placeholder=" write" />
                                    </div>
                                </div>                           
                                <div class="row middle">
                                    <div class="col-sm-5">Courier Receipt No. </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="courier_recpt_no" id="courier_recpt_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Courier Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="courier_company" id="courier_company" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Acceptance Rcv.Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="accept_receive_date" id="accept_receive_date"  class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Lien Bank</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="buyers_bank" id="buyers_bank" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buyer</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="buyer_id" id="buyer_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Beneficiary</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="beneficiary_id" id="beneficiary_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">LC Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="currency_id" id="currency_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsLocalExpDocSubAccept.openCI()">CI & DC</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsLocalExpDocSubAccept.boepdf()">BOE</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsLocalExpDocSubAccept.plpdf()">PL</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsLocalExpDocSubAccept.plshortpdf()">PL-S</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsLocalExpDocSubAccept.coepdf()">CO</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsLocalExpDocSubAccept.bcpdf()">BC</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsLocalExpDocSubAccept.forwardletter()">Forwarding Letter</a>
                        
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpDocSubAccept.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('localexpdocsubacceptFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpDocSubAccept.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Invoice Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="localexpdocsubinvoiceTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'local_exp_invoice_id'" width-="40" align="center">Invoice ID</th>
                            <th data-options="field:'local_invoice_no'"  width="80" align="right">Invoice No</th>
                            <th data-options="field:'local_invoice_date'" width="80" align="right">Invoice Date</th>
                            <th data-options="field:'local_lc_no'" width="80" align="center">LC No</th>                        
                            <th data-options="field:'local_invoice_value'" width="80" align="right">Invoice Value</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'north',border:true,title:' Details',footer:'#ft3'" style="height:300px; padding:2px"> 
                <table id="localexpdocsubinvoiceTbl2" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'local_exp_invoice_id'" width="40" align="center">Invoice ID</th>
                            <th data-options="field:'local_invoice_no'" width="80">Invoice No</th>
                            <th data-options="field:'local_invoice_date'" width="80">Invoice Date</th>
                            <th data-options="field:'local_lc_no'" width="80">LC No</th>
                            <th data-options="field:'local_invoice_value'" width="80" align="right">Invoice Value</th>
                        </tr>
                    </thead>
                </table>       
                <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpDocSubInvoice.submit()">Save</a>
                </div>             
            </div>
        </div>
    </div>
</div>

<div id="docsubacceptwindow" class="easyui-window" title="Document Submission For Acceptance /LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="localexplcdocsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="local_lc_no" id="local_lc_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Contract Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_date" id="lc_date" value="" class="datepicker" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsLocalExpDocSubAccept.searchLocalExportLc()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="localexplcdocsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'local_lc_no'" width="150">LC No</th>
                        <th data-options="field:'lc_value'" width="100">LC Value</th>
                        <th data-options="field:'lc_date'" width="100"> LC Date</th> 
                        <th data-options="field:'last_delivery_date'" width="100" align="right"> Last Delivery Date</th>
                        <th data-options="field:'local_exp_invoice_id'" width="80">Invoice ID</th>
                        <th data-options="field:'local_invoice_no'" width="150">Invoice No</th> 
                        <th data-options="field:'local_invoice_value'" width="80">Invoice Value</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#docsubacceptwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<div id="invoiceWindow" class="easyui-window" title="Commercial Invoice Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:600px;height:300px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invoiceSearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'pdf'" width="50" formatter="MsLocalExpDocSubAccept.formatCIPdf"></th>
                        <th data-options="field:'pdf1'" width="50" formatter="MsLocalExpDocSubAccept.formatDCPdf"></th>
                        <th data-options="field:'pdfshortci'" width="50" formatter="MsLocalExpDocSubAccept.formatCIShortPdf"></th>
                        <th data-options="field:'pdfshortdc'" width="50" formatter="MsLocalExpDocSubAccept.formatDCShortPdf"></th>
                        <th data-options="field:'local_exp_doc_sub_invoice_id'" width="80">Invoice ID</th>
                        <th data-options="field:'local_invoice_no'" width="80">Invoice No</th> 
                        <th data-options="field:'local_invoice_value'" width="80">Invoice Value</th>
                        <th data-options="field:'local_invoice_date'" width="80" align="right">Invoice Date</th>
                    </tr>

                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#invoiceWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
 
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/LocalExport/MsAllLocalExpDocSubAcceptController.js"></script>
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
            changeYear: true,
        });
    })(jQuery);
</script>
 