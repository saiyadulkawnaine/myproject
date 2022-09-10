<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtcuttingtabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtcuttingTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
                            <th data-options="field:'cut_qc_date'" width="100">Cutting QC Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Cutting QC Entry',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="prodgmtcuttingFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shift Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cut QC Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cut_qc_date" id="cut_qc_date" class="datepicker" />
                                    </div>
                                </div>    
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" ></textarea>
                                    </div>
                                </div>    
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCutting.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtcuttingFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCutting.remove()">Delete</a>
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
                <form id="prodgmtcuttingorderFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_cutting_id" id="prod_gmt_cutting_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="
                                        MsProdGmtCuttingOrder.openOrderCuttingWindow()
                                            /*  alert('error')   */" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Look </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id')) !!}
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4">Table No</div>
                                    <div class="col-sm-8">      
                                        <input type="text" name="table_no" id="table_no" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lay/Cut No</div>
                                    <div class="col-sm-8">      
                                        <input type="text" name="lay_cut_no" id="lay_cut_no" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Cutting Hour</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cutting_hour" id="cutting_hour" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Prod Source</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod.Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}  
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Marker Length</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="marker_length" id="marker_length" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Marker Width</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="marker_width" id="marker_width" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Used Fabric</div>
                                    <div class="col-sm-8">  
                                        <input type="text" name="used_fabric" id="used_fabric" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Wastage Fabric</div>
                                    <div class="col-sm-8">  
                                        <input type="text" name="wastage_fabric" id="wastage_fabric" value="" class="number integer" />
                                    </div>
                                </div>
								<div class="row middle">
                                    <div class="col-sm-4">UOM</div>
									<div class="col-sm-8">  
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                                    </div>
								</div>
                                <div class="row middle">
                                    <div class="col-sm-4">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}  
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Beneficiary</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="company_id" id="company_id" value="" disabled />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Country</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="country_id" id="country_id" value="" disabled />
                                    </div>
                                </div>
                                                            
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ship Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ship_date" id="ship_date" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Source</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_id" id="supplier_id" value="" disabled />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCuttingOrder.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtcuttingorderFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCuttingOrder.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="prodgmtcuttingorderTbl" style="width:100%">
                    <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">Id</th>
                        <th data-options="field:'sale_order_no'" width="100">Order No</th>
                        <th data-options="field:'prod_source_id'" width="100">Prod.Source</th> 
                        <th data-options="field:'location_id'" width="90">Location</th>  
                        <th data-options="field:'table_no'" width="100">Table No</th> 
                    </tr>
                    </thead>
                </table> 
            </div>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Gmt Qty',footer:'#ftcuttingqty'" style="padding:2px">     
                <form id="prodgmtcuttingqtyFrm">
                    <input type="hidden" name="id" id="id" value=""/>
                    <code id="cuttinggmtcosi">
                    </code>
                    <div id="ftcuttingqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCuttingQty.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtcuttingqtyFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCuttingQty.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="openordercuttingwindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="ordercuttingsearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtCuttingOrder.searchCuttingOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordercuttingsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'sales_order_country_id'" width="40">ID</th>
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openordercuttingwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllCuttingController.js"></script>

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

      $('#prodgmtcuttingorderFrm [id="supplier_id"]').combobox();

   })(jQuery);

$(function() {
$('#cutting_hour').timepicker(
{
'minTime': '12:00pm',
'maxTime': '11:00am',
'showDuration': false,
'step':60,
'scrollDefault': 'now',
'change': function(){
    alert('m')
}
}
);
});

</script>