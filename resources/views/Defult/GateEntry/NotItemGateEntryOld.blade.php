<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="gateEntrytabs">
    <div title="Gate In Entry - Goods Only" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true"> 
            <div data-options="region:'west',border:true" style="width:400px; padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'" style="height:380px; padding:2px">
                        <form id="gateentryFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row middle" style="display:none">
                                            <input type="hidden" name="id" id="id" value="" />
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Purchase Order Type</div>
                                            <div class="col-sm-8">
                                               {!! Form::select('menu_id',
                                               $menu,'',array('id'=>'menu_id')) !!}
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4"><strong>Barcode</strong></div>
                                            <div class="col-sm-8">
                                               <input type="text" name="barcode_no_id" id="barcode_no_id" onchange="MsGateEntry.getPurchaseNo()" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Challan No</div>
                                            <div class="col-sm-8">
                                               <input type="text" name="challan_no" id="challan_no" value="" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Supplier</div>
                                            <div class="col-sm-8">
                                               <input type="text" name="supplier_name" id="supplier_name" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Supplier Contact</div>
                                            <div class="col-sm-8">
                                               <input type="text" name="supply_contact" id="supply_contact" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">PO Number</div>
                                            <div class="col-sm-8">
                                               <input type="text" name="po_number" id="po_number" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Requisition No</div>
                                            <div class="col-sm-8">
                                               <input type="text" name="requisition_no" id="requisition_no" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">LC Number</div>
                                            <div class="col-sm-8">
                                               <input type="text" name="lc_sc_no" id="lc_sc_no" value="" disabled />
                                            </div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                            <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGateEntry.submit()">Save</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGateEntry.resetForm()">Reset</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGateEntry.remove()">Delete</a>
                            </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                        <table id="gateentryTbl" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="40">Id</th>
                                    <th data-options="field:'barcode_no_id'" width="100">Barcode No</th>
                                    <th data-options="field:'challan_no'" width="100">Challan No</th> 
                                </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Item Details',footer:'#ftdtl'" style="padding:2px">
                <div data-options="region:'center',border:true,title:''" style="padding:2px">
                    <table id="gateendtryitemTbl">
                      <thead>
                        <tr>
                          <th data-options="field:'barcode_no_id'" width="40">Item ID</th>
                          <th data-options="field:'item_description'" width="130">Item Description</th>
                          <th data-options="field:'qty',editor:{type:'numberbox',options:{precision:0}}" width="60">Receive<br/>Qty</th>
                          <th data-options="field:'remarks'" width="130">Remarks</th>
                        </tr>
                      </thead>
                    </table>
                </div> 
                <form id="srmproductreceivedtlFrm">
                    {{-- <input type="hidden" name="id" id="id" value=""/> --}}
                    <code id="receivedtalicosi">
                    </code>
                    <div id="ftdtl" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSrmProductReceiveDtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('srmproductreceivedtlFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSrmProductReceiveDtl.remove()" >Delete</a>
                    </div>
                </form>        
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/GateEntry/MsGateEntryController.js"></script>

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

        $( "#barcode_no_id" ).focus();

    })(jQuery);

</script>