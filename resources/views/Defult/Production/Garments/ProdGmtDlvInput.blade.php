<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtdlvinputtabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtdlvinputTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'challan_no'" width="80">Challan No</th>
                            <th data-options="field:'supplier_id'" width="150">Service Provider</th>
                            <th data-options="field:'location_id'" width="80">Location</th>
                            <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
                            <th data-options="field:'delivery_date'" width="100">Delivery Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Cut Panel Delivery To Input Department',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="prodgmtdlvinputFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Producing Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Service Provider</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'border:2px;width:100%')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shift Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_date" id="delivery_date" class="datepicker" />
                                    </div>
                                </div>  
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtDlvInput.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtdlvinputFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtDlvInput.remove()">Delete</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsProdGmtDlvInput.pdf()">Challan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-----===============  =============----------->
    <div title="Order Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">        
            <div data-options="region:'west',border:true" style="width:400px; padding:2px">
            <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'" style="height:300px; padding:2px">
                <form id="prodgmtdlvinputorderFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_dlv_input_id" id="prod_gmt_dlv_input_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="
                                        MsProdGmtDlvInputOrder.openOrderDlvInputWindow()
                                            " placeholder=" Double Click" readonly />
                                        <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Looks </div>
                                    <div class="col-sm-8">{!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id')) !!}</div>  {{-- ,'onchange'=>'MsStyleFabrication.fabricLookChange(this.value)', 'disabled' --}}
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Country</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="country_id" id="country_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Beneficiary</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="company_id" id="company_id" value="" disabled />
                                    </div>
                               </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Style Refference</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ship Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ship_date" id="ship_date" value="" class="datepicker" disabled/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtDlvInputOrder.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtdlvinputorderFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtDlvInputOrder.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="prodgmtdlvinputorderTbl" style="width:100%">
                    <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">Id</th>
                        <th data-options="field:'sale_order_no'" width="100">Order No</th>
                        <th data-options="field:'country_id'" width="100">Country</th> 
                        <th data-options="field:'buyer_name'" width="90">Buyer</th>  
                        <th data-options="field:'job_no'" width="100">Job No</th> 
                        <th data-options="field:'fabric_look_id'" width="100">Fabric Looks</th> 
                        <th data-options="field:'style_ref'" width="100">Style Ref</th> 
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                    </tr>
                    </thead>
                </table> 
            </div>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Gmt Qty',footer:'#ftdlvinputqty'" style="padding:2px">    
                <form id="prodgmtdlvinputqtyFrm">
                    <input type="hidden" name="id" id="id" value=""/>
                    <code id="dlvinputgmtcosi">
                    </code>
                    <div id="ftdlvinputqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtDlvInputQty.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtdlvinputqtyFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtDlvInputQty.remove()" >Delete</a>
                    </div>
                </form>        
            </div>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="openorderdlvinputwindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="orderdlvinputsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtDlvInputOrder.searchDlvInputOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="orderdlvinputsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'country_id'" width="100">Country</th>    
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'company_id'" width="120">Company</th>
                        <th data-options="field:'produced_company_name'" width="120">Produced Company</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openorderdlvinputwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllDlvInputController.js"></script>

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

      $('#prodgmtdlvinputFrm [id="supplier_id"]').combobox();

   })(jQuery);

</script>