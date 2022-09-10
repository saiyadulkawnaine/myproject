<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtpolytabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtpolyTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
                            <th data-options="field:'poly_qc_date'" width="100">Poly QC Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Poly QC Entry',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="prodgmtpolyFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Shift Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Poly QC Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="poly_qc_date" id="poly_qc_date" class="datepicker" />
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
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtPoly.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtpolyFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtPoly.remove()">Delete</a>
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
                        <form id="prodgmtpolyorderFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row middle" style="display:none">
                                            <input type="hidden" name="id" id="id" value="" />
                                            <input type="hidden" name="prod_gmt_poly_id" id="prod_gmt_poly_id" value="" />
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Order No</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="sale_order_no" id="sale_order_no" value="" onclick="
                                                MsProdGmtPolyOrder.openOrderPolyWindow()
                                                    /*  alert('error')   */" placeholder="  Click" readonly />
                                                <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                            </div>
                                        </div>
                                    
                                        <div class="row middle">
                                            <div class="col-sm-4">Line No</div>
                                            <div class="col-sm-8">  
                                                <input type="text" name="line_name" id="line_name" value="" onclick="MsProdGmtPolyOrder.openLineNoWindow()" placeholder=" Click Once" />
                                                <input type="hidden" name="wstudy_line_setup_id" id="wstudy_line_setup_id" value="" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Prod Hour</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="prod_hour" id="prod_hour" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Prod Source</div>
                                            <div class="col-sm-8">
                                                {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                                {{-- <input type="text" name="prod_source_id" id="prod_source_id" value="" /> --}}
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Supplier</div>
                                            <div class="col-sm-8">
                                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Company</div>
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
                                            <div class="col-sm-4">Location</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="location_id" id="location_id" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Prod.Company</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="produced_company_name" id="produced_company_name" value="" disabled />
                                                <input type="hidden" name="produced_company_id" id="produced_company_id" value="" disabled />                                        
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Buyer</div>
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
                                                <input type="text" name="order_source_id" id="order_source_id" value="" disabled />
                                            </div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                            <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtPolyOrder.submit()">Save</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtpolyorderFrm')">Reset</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtPolyOrder.remove()">Delete</a>
                            </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                        <table id="prodgmtpolyorderTbl" style="width:100%">
                            <thead>
                            <tr>
                                <th data-options="field:'id'" width="40">Id</th>
                                <th data-options="field:'sale_order_no'" width="100">Order No</th>
                                <th data-options="field:'prod_source_id'" width="100">Prod.Source</th> 
                                <th data-options="field:'supplier_id'" width="100">Service Provider</th> 
                                <th data-options="field:'prod_hour'" width="100">Prod.Hour</th> 
                                <th data-options="field:'location_id'" width="90">Location</th>  
                                <th data-options="field:'wstudy_line_setup_id'" width="100">Line No</th> 
                            </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Gmt Qty',footer:'#ftpolyqty'" style="padding:2px">   
                    <form id="prodgmtpolyqtyFrm">
                        <input type="hidden" name="id" id="id" value=""/>
                        <code id="polygmtcosi">
                        </code>
                        <div id="ftpolyqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtPolyQty.submit()">Save</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtpolyqtyFrm')" >Reset</a>
                            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtPolyQty.remove()" >Delete</a>
                        </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="openorderpolywindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="orderpolysearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtPolyOrder.searchPolyOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="orderpolysearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'country_id'" width="100">Country</th>    
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'company_id'" width="80">Company</th>
                        <th data-options="field:'produced_company_name'" width="80">Produced Company</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openorderpolywindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Line Window --}}
<div id="openlinenowindow" class="easyui-window" title="Line No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="linenosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Location</div>
                                <div class="col-sm-8">
                                    {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtPolyOrder.searchLineNoGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="linenosearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'company_name'" align="center" width="170">Company</th>
                        <th data-options="field:'line_name'" width="100">Line No</th>
                        <th data-options="field:'line_code'" width="100">Line Name</th>
                        <th data-options="field:'line_floor'" width="200">Line Floor</th>
                        <th data-options="field:'location_name'" align="center" width="100">Location</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openlinenowindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllPolyController.js"></script>

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
      $('#prodgmtpolyorderFrm [id="supplier_id"]').combobox();
   })(jQuery);

$(function() {
$('#prod_hour').timepicker(
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