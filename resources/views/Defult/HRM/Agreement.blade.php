<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="agreementtabs">
    <div title="Agreement" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="agreementTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'supplier_id'" width="120">Supplier</th>
                            <th data-options="field:'purpose'" width="100">Purpose</th>
                            <th data-options="field:'accept_date'" width="100">Date</th>
                            <th data-options="field:'remarks'" width="180">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Agreement Reference',footer:'#ft2'" style="width: 380px; padding:2px">
                <form id="agreementFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Supplier </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="accept_date" id="accept_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Purpose </div>
                                    <div class="col-sm-8">
                                        <textarea name="purpose" id="purpose"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAgreement.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('agreementFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAgreement.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-----===============File Upload=============----------->
    <div title="File Upload" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Upload File',iconCls:'icon-more',footer:'#agreementfileft'" style="width:450px; padding:2px">
                <form id="agreementfileFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="agreement_id" id="agreement_id" value=""/>
                                                                      
                                </div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Name</div>
                                   <div class="col-sm-8">
                                    	<input type="text" id="original_name" name="original_name" value="" />
                                   </div>
                            	</div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Upload</div>
                                   <div class="col-sm-8">
                                    	<input type="file" id="file_upload" name="file_upload" value="" />            	
                                   </div>
                            	</div>                                
                            </code>
                        </div>
                    </div>
                    <div id="agreementfileft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAgreementFile.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('agreementfileFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAgreementFile.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="agreementfileTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'original_name'" width="120">Original Name</th>
                            <th data-options="field:'file_src'" width="80" formatter="MsAgreementFile.formatfile">Upload Files</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-----=============== PO =============----------->
    <div title="PO" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Purchase Order',iconCls:'icon-more',footer:'#agreementpoft'" style="width:350px; padding:2px">
                <form id="agreementpoFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="agreement_id" id="agreement_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">PO Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('menu_id', $menu,'',array('id'=>'menu_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">PO No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="purchase_order_id" id="purchase_order_id" onDblClick="MsAgreementPo.openPurchaseOrderWindow()" placeholder=" double click"/>
                                    </div>
                                </div>                     
                            </code>
                        </div>
                    </div>
                    <div id="agreementpoft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAgreementPo.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('agreementpoFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAgreementPo.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="agreementpoTbl" style="width:100%">
                    <thead>
                        <tr>
                        	<th data-options="field:'pdf'" formatter="MsAgreementPo.formatpdf" width="60" align="center"></th>
                            <th data-options="field:'id'" width="40">ID</th> 
                            <th data-options="field:'po_no'" width="100px">PO No</th>
                            <th data-options="field:'po_amount'" width="80px" align="right">Amount</th>
                            <th data-options="field:'item'" width="150px">PO Type</th>
                            <!-- <th data-options="field:'purchase_order_id'" width="100px">PO ID</th> -->
                            <th data-options="field:'delete_po'" formatter="MsAgreementPo.deleteButton" width="60" align="center"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- ==============Purchase Order Import =============== --}}
<div id="importpoWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#ft11'" style="padding:2px">
          <table id="posearchTbl" style="width:1250px">
            <thead>                  
                <th field="ck" checkbox="true"></th>
                <th data-options="field:'purchase_order_id'" width="40px">ID</th>
                <th data-options="field:'po_no'" width="80px">PO No</th>
                <th data-options="field:'company_name'" width="70px">Company</th>
                <th data-options="field:'supplier_name'" width="150px">Supplier</th>
                <th data-options="field:'supplier_contact'" width="80px">Suplier Address</th>
                <th data-options="field:'po_date'" width="80px">PO Date</th>
                <th data-options="field:'pi_no'" width="80px">PI No</th>
                <th data-options="field:'currency_name'" width="60px">Currency</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>                 
            </thead>
          </table>
          <div id="ft11" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsAgreementPo.submitAndClose()" >Next</a>
          </div>
        </div>
        <div data-options="region:'west',border:true" style="padding:2px; width:350px">
            <form id="posearchFrm">
                <div id="container">
                <div id="body">
                    <code>
                    <div class="row middle">
                        <div class="col-sm-4">PO No</div>
                        <div class="col-sm-8">
                        <input type="text" name="po_no" id="po_no" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Supplier</div>
                        <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','disabled'=>'disabled')) !!}</div>
                    </div>
                    </code>
                </div>
                </div>
                <div id="ft21" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsAgreementPo.searchPo()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllAgreementController.js"></script>

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
        $('#agreementFrm [id="supplier_id"]').combobox();

    })(jQuery);
</script>

</script>
