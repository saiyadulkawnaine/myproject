<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <div class="easyui-accordion" data-options="multiple:false,fit:true" style="width:300px;">
            <div title="List" data-options="iconCls:'icon-ok'" style="overflow:auto;padding:2px;">
                <table id="impdocmaturityTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="60">ID</th>
                            <th data-options="field:'doc_maturity_date'" width="100">Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div title="Maturity List" data-options="iconCls:'icon-ok'" style="overflow:auto;padding:2px;">
                <table id="impdocmaturitydtlTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'lc_no'" width="110">LC NO.</th>
                            <th data-options="field:'invoice_no'" width="110">Invoice No</th>
                            <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
                            <th data-options="field:'shipment_date'" width="100">Shipment Date</th>
                            <th data-options="field:'company_accep_date'" width="100">Company Accept Date</th>
                            <th data-options="field:'bank_ref'" width="130">Bank Ref</th>
                            <th data-options="field:'loan_ref'" width="100">Loan Ref</th>
                            <th data-options="field:'doc_value'" width="100" align="right">Document Value</th>
                            <th data-options="field:'rate'" width="100" align="right">Interest Rate</th>
                            <th data-options="field:'commercial_head_id'" width="100">Means of Retirement</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Maturity Date'" style="width:310px; padding:2px">
        <div class="easyui-accordion" data-options="multiple:true" style="width:300px;">
            <div title="Doc Maturity" data-options="iconCls:'icon-ok',footer:'#ft2'" style="overflow:auto;padding:2px;">
                <form id="impdocmaturityFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Date</div>
                                    <div class="col-sm-8">
                                       <input type="text" name="doc_maturity_date" id="doc_maturity_date" class="datepicker" placeholder=" yy-mm-dd" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpDocMaturity.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impdocmaturityFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpDocMaturity.remove()" >Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpDocMaturity.mletter()">M.Letter</a>
                    </div>
                </form>
            </div>
            <div title="Maturity Details" data-options="iconCls:'icon-ok',footer:'#ft3'" style="overflow:auto;padding:2px;">
                <form id="impdocmaturitydtlFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="imp_doc_maturity_id" id="imp_doc_maturity_id" value="" />
                                 </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice No</div>
                                    <div class="col-sm-8">
                                       <input type="text" name="invoice_no" id="invoice_no" ondblclick="MsImpDocMaturityDtl.openMatureImpDocAcceptWindow()" placeholder=" Double Click" />
                                       <input type="hidden" name="imp_doc_accept_id" id="imp_doc_accept_id" value=""/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpDocMaturityDtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impdocmaturitydtlFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpDocMaturityDtl.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="impdocacceptWindow" class="easyui-window" title="Import Document Accept Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="impdocacceptsearchFrm">
                            <div class="row middle">
                               <div class="col-sm-4">Invoice No</div>
                               <div class="col-sm-8">
                                  <input type="text" name="invoice_no" id="invoice_no" value=""/>
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Invoice Date</div>
                               <div class="col-sm-8">
                                  <input type="text" name="invoice_date" id="invoice_date" value=""/>
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">LC No</div>
                               <div class="col-sm-8">
                                  <input type="text" name="lc_no" id="lc_no" value=""/>
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Bank Ref</div>
                               <div class="col-sm-8">
                                  <input type="text" name="bank_ref" id="bank_ref" value=""/>
                               </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                   <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsImpDocMaturityDtl.searchDocAcceptImpLc()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="impdocacceptsearchTbl" style="width:610px">
                <thead>
                   <tr>
                      <th data-options="field:'id'" width="40">ID</th>
                      <th data-options="field:'lc_no'" width="110">LC NO.</th>
                      <th data-options="field:'invoice_no'" width="110">Invoice No</th>
                      <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
                      <th data-options="field:'shipment_date'" width="100">Shipment Date</th>
                      <th data-options="field:'company_accep_date'" width="100">Company Accept Date</th>
                      <th data-options="field:'bank_ref'" width="130">Bank Ref</th>
                      <th data-options="field:'loan_ref'" width="100">Loan Ref</th>
                      <th data-options="field:'doc_value'" width="100" align="right">Document Value</th>
                      <th data-options="field:'rate'" width="100" align="right">Interest Rate</th>
                      <th data-options="field:'commercial_head_id'" width="100">Means of Retirement</th>
                   </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#impdocacceptWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
 </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Import/MsAllImpDocMaturityController.js"></script>
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
 
    })(jQuery);
    </script>