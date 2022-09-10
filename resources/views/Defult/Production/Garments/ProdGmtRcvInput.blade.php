<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtrcvinputtabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtrcvinputTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'receive_no'" width="80">Receive No</th>
                            <th data-options="field:'challan_no'" width="80">Challan No</th>
                            <th data-options="field:'receive_date'" width="100">Receive Date</th>
                            <th data-options="field:'supplier_id'" width="80">Prod. Comp</th>
                            <th data-options="field:'location_id'" width="80">Location</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Cut Panel Received by Input Department',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="prodgmtrcvinputFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Receive No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_no" id="receive_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no" value="" ondblclick="MsProdGmtRcvInput.openDlvInputWindow()" readonly placeholder=" Double Click Here" />
                                        <input type="hidden" name="prod_gmt_dlv_input_id" id="prod_gmt_dlv_input_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod.Comp</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="location_id" id="location_id" disabled />
                                    </div>
                                </div> 
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtRcvInput.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtrcvinputFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtRcvInput.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-----===============  =============----------->
    <div title="Order Quantity Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">        
            <div data-options="region:'center',border:true,footer:'#ft4',title:'Lists'" style="padding:2px">
                <form id="prodgmtrcvinputqtyFrm">               
                    <code id="prodgmtrcvinputmatrix">
                    
                    </code>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtRcvInputQty.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtrcvinputqtyFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtRcvInputQty.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Challan Window --}}
<div id="opendlvinputwindow" class="easyui-window" title="Challan Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="dlvinputsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Pro.Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Delivery Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="delivery_date" id="delivery_date" class="datepicker" />
                                </div>
                            </div> 
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtRcvInput.searchDlvInputGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="dlvinputsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'challan_no'" width="80">Challan No</th>
                        <th data-options="field:'supplier_name'" width="80">Prod. Company</th>
                        <th data-options="field:'location_id'" width="80">Prod. Location</th>
                        <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
                        <th data-options="field:'delivery_date'" width="100">Delivery Date</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opendlvinputwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllRcvInputController.js"></script>

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