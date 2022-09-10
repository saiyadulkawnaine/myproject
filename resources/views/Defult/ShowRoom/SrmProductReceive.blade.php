<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="srmproductreceivetabs">
    <div title="Order Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true"> 
            <div data-options="region:'west',border:true" style="width:400px; padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'" style="height:300px; padding:2px">
                        <form id="srmproductreceiveFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row middle" style="display:none">
                                            <input type="hidden" name="id" id="id" value="" />
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Receive Date</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="receive_date" id="receive_date" class="datepicker" placeholder=" yyyy-mm-dd" value="{{date('Y-m-d')}}" />
                                            </div>
                                        </div> 
                                        <div class="row middle">
                                            <div class="col-sm-4">Invoice No</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="invoice_no" id="invoice_no" value="" ondblclick="
                                                MsSrmProductReceive.openInvoiceOrderWindow()
                                                 " placeholder=" Double Click" readonly />
                                                <input type="hidden" name="exp_invoice_id" id="exp_invoice_id" value="" />
                                            </div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                            <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSrmProductReceive.submit()">Save</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSrmProductReceive.resetForm()">Reset</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSrmProductReceive.remove()">Delete</a>
                            </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                        <table id="srmproductreceiveTbl" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="40">Id</th>
                                    <th data-options="field:'invoice_no'" width="100">Invoice No</th>
                                    <th data-options="field:'receive_date'" width="100">Receive Date</th> 
                                </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Item Details',footer:'#ftdtl'" style="padding:2px">    
                <form id="srmproductreceivedtlFrm">
                    {{-- <input type="hidden" name="id" id="id" value=""/> --}}
                    <code id="receivedtalicosi">
                    </code>
                    <div id="ftdtl" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSrmProductReceiveDtl.bercodePdf()">BARCODE</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSrmProductReceiveDtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('srmproductreceivedtlFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSrmProductReceiveDtl.remove()" >Delete</a>
                    </div>
                </form>        
            </div>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="openexpinvoicewindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="expinvoicesearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Buyer </div>
                                <div class="col-sm-8">
                                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border:2')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Invoice No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="invoice_no" id="invoice_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Date</div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from"  placeholder=" From" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" placeholder=" To" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">LC No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_sc_no" id="lc_sc_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsSrmProductReceive.searchInvoiceOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="expinvoicesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'lc_sc_no'" width="100">LC/SC No</th>
                        <th data-options="field:'invoice_no'" width="100">Invoice No</th>
                        <th data-options="field:'invoice_date'" width="100">Invoice Date</th>
                        <th data-options="field:'exp_form_no'" width="100">Exp Form No</th>
                        <th data-options="field:'exp_form_date'" width="100">Exp Form Date</th>
                        <th data-options="field:'actual_ship_date'" width="100">Actual Ship Date</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openexpinvoicewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/ShowRoom/MsAllSrmProductReceiveController.js"></script>

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
            changeYear: true
        });

        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

    })(jQuery);

</script>