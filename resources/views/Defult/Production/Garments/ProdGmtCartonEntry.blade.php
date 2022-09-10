<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtcartontabs">
    <div title="Carton Completion Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtcartonentryTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Buyer</th>
                            <th data-options="field:'carton_date'" width="80">Carton Date</th>
                            <th data-options="field:'order_source_id'" width="120">Order Source</th>
                            <th data-options="field:'prod_source_id'" width="100">Production Source</th>
                            <th data-options="field:'location_id'" width="80">Location</th>
                            <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Requisition Reference',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="prodgmtcartonentryFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Buyer </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border:2')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Carton. Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="carton_date" id="carton_date" class="datepicker" placeholder="  yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Source</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('order_source_id', $ordersource,'',array('id'=>'order_source_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod. Sourse </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                    </div>
                                </div>      
                                <div class="row middle">
                                    <div class="col-sm-4">Prod. Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location </div>
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
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCartonEntry.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtcartonentryFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCartonEntry.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-----===============Carton Item Detail=============----------->
    <div title="Completed Carton Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtcartondetailTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                            <th data-options="field:'job_no'" width="100">Job No</th>
                            <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                            <th data-options="field:'country_id'" width="100">Country</th>    
                            <th data-options="field:'assortment_name'" width="100"> Assortment Name</th>
                            <th data-options="field:'packing_type'" width="100"> Packing Type</th>
                             
                           
                            <th data-options="field:'qty'" width="100" align="right"> GMT/Carton</th>
                             <th data-options="field:'order_rate'" width="100" align="right"> Rate</th> 
                            <th data-options="field:'carton_amount'" width="100" align="right"> Amount</th>
                            <th data-options="field:'ac_button'" width="100" align="right" formatter="MsProdGmtCartonDetail.formatDetail"></th>

                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Requisition Reference',footer:'#ft3'" style="width: 400px; padding:2px">
                <form id="prodgmtcartondetailFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_carton_entry_id" id="prod_gmt_carton_entry_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" ondblclick="MsProdGmtCartonDetail.openSalesOrderCartonWindow()" value="" readonly placeholder=" Double Click" />
                                        <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Assortment </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_pkg_name" id="style_pkg_name" onclick="MsProdGmtCartonDetail.openStylePkgWindow()" value="" readonly />
                                        <input type="hidden" name="style_pkg_id" id="style_pkg_id" value="" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Country</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="country_id" id="country_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Curr. Carton Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty"  value="" class="number integer" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Prev. Carton  Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="prev_qty" id="prev_qty" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="order_qty" id="order_qty" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GMT/Carton </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_per_carton" id="gmt_per_carton" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yet To Carton  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yet_to_carton" id="yet_to_carton" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref"  disabled/>
                                        <input type="hidden" name="style_id" id="style_id" value="" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" disabled />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCartonDetail.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtcartondetailFrm')">Reset</a>
                        <!-- <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCartonDetail.remove()">Delete</a> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-----===============Un Assortment Carton Detail=============----------->
    <div title="Unassorted Carton" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <form id="prodgmtcartonunassortedpkgratioFrm">
                <div id="container">
                <div id="body">
                <input type="hidden" name="style_pkg_id" id="style_pkg_id" value=""/>
                <input type="hidden" name="id" id="id" value=""/>
                <input type="hidden" name="style_id" id="style_id" value=""/>
                <code id="prodgmtcartonunassortedpkgcs">
                </code>
                </div>
                </div>
                <!--  <div id="ftpkgratio" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStylePkgRatio.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkgRatio.resetForm()" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkgRatio.remove()" >Delete</a>
                </div> -->
                </form>
            </div>
            <div data-options="region:'west',border:true,title:'Requisition Reference',footer:'#ft4'" style="width: 400px; padding:2px">
                <form id="prodgmtcartondetailunassortedFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_carton_entry_id" id="prod_gmt_carton_entry_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" onclick="MsProdGmtCartonDetail.openSalesOrderCartonWindow()" value="" readonly />
                                        <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="style_pkg_id" id="style_pkg_id" value="" />
                                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px; width: 100%" plain="true" id="save" onClick="MsProdGmtCartonDetailUnassorted.loadRatioForm()">Load Ratio Form</a> 
                                    </div>
                                    
                                </div>
                                

                                <div class="row middle">
                                    <div class="col-sm-4">Country</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="country_id" id="country_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Curr. Carton Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty"  value="" class="number integer" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Prev. Carton  Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="prev_qty" id="prev_qty" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="order_qty" id="order_qty" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GMT/Carton </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_per_carton" id="gmt_per_carton" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yet To Carton  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yet_to_carton" id="yet_to_carton" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref"  disabled/>
                                        <input type="hidden" name="style_id" id="style_id" value="" >
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" disabled />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCartonDetailUnassorted.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtcartondetailunassortedFrm')">Reset</a>
                       <!--  <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCartonDetailUnassorted.remove()">Delete</a> -->
                    </div>
                </form>
                <table id="prodgmtcartondetailunassortedTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'assortment_name'" width="100">Assortment Name</th>
                            <th data-options="field:'itemclass'" width="100">Item Class</th>
                            <th data-options="field:'spec'" width="100">Spec</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!--------------------      ------------------->

<div id="opencartoncountrywindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
        <div id="body">
            <code>
                <form id="salesordernosearchFrm">
                    <div class="row middle">
                    <div class="col-sm-4">Style Ref</div>
                    <div class="col-sm-8">
                        <input type="text" name="style_ref" id="style_ref" value=""> 
                    </div>
                    </div>
                    <div class="row middle">
                    <div class="col-sm-4">Job </div>
                    <div class="col-sm-8">
                    <input type="text" name="job_no" id="job_no" value="">
                    </div>
                    </div>
                    <div class="row middle">
                    <div class="col-sm-4">Sales Order No</div>
                    <div class="col-sm-8">
                    <input type="text" name="sales_order_no" id="sales_order_no" value="">
                    </div>
                    </div>
                </form>
            </code>
        </div>
        <p class="footer">
        <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtCartonDetail.getSalesOrderCountry()">Search</a>
        </p>
       
    </div>
    <div data-options="region:'center'" style="padding:10px;">
        <table id="salesordernosearchTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'style_ref'" width="70">Style Ref</th>
                    <th data-options="field:'job_no'" width="90">Job No</th>
                    <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                    <th data-options="field:'country_id'" width="100">Country</th>    
                    <th data-options="field:'order_qty'" width="100" align="right"> Order Qty</th> 
                    <th data-options="field:'order_rate'" width="100" align="right"> Rate</th> 
                    <th data-options="field:'order_amount'" width="100" align="right"> Amount</th>
                    <!-- <th data-options="field:'cumulative_qty'" width="100" align="right"> Cumulative Qty</th>
                    <th data-options="field:'balance_qty'" width="100" align="right"> Balance Qty</th> -->
                </tr>
            </thead>
        </table>
    </div>
    
    </div>
</div>
<div id="opencartonpkgwindow" class="easyui-window" title="Packing Ratio" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
     <table id="cartonpackingratioTbl" style="width:100%">
        <thead>
        <tr>
         <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'style_ref'" width="100">Style</th>
         <th data-options="field:'assortment_name'" width="120">Assortment Name</th>
         <th data-options="field:'spec'" width="100">Spec</th>
         <th data-options="field:'style_pkg_name'" width="100">Assortment</th>
         <th data-options="field:'packing_type'" width="100">Packing Type</th>
         
         <th data-options="field:'qty'" width="70" align="right">Qty</th>
        </tr>
        </thead>
        </table>
</div>


<!--------------------------------------->
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllProductionController.js"></script>

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

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });
      
      $('#prodgmtcartonentryFrm [id="buyer_id"]').combobox();

   })(jQuery);

</script>

