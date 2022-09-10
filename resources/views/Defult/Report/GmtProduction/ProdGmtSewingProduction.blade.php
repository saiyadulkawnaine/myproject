<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'north',border:true,title:'List'" style="padding:2px;height:100%">
                <table id="prodgmtsewingproductionTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'company_code',halign:'center'" width="100" >1<br/>Bnf.Company</th>
                            <th data-options="field:'pcompany_code',halign:'center'" align="center" width="70">2<br/>Resp.company</th>
                            <th data-options="field:'supplier_name'" align="center" width="100" formatter="MsProdGmtSewingProduction.formatserviceprovider">3<br/>ServiceProvider<br/>Company</th>
                            <th data-options="field:'dl_marchent',halign:'center'" width="100" formatter="MsProdGmtSewingProduction.formatprodgmtdlmerchant">4<br/>Dealing <br/> Merchant</th>
                            <th data-options="field:'buyer_name',halign:'center'" width="100" formatter="MsProdGmtSewingProduction.formatbuyer">5<br/>Buyer</th>
                            <th data-options="field:'style_ref',halign:'center'" width="80" formatter="MsProdGmtSewingProduction.formatprodgmtfile">6<br/>Style No</th>
                            <th data-options="field:'sale_order_no',halign:'center'" width="100">7<br/>Order No</th>
                            <th data-options="field:'ship_date',halign:'center'" width="80">8<br/>Ship Date</th>
                            <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsProdGmtSewingProduction.formatimage" width="30">9<br/>Image</th>
                            <th data-options="field:'color_name',halign:'center'" width="80">10<br/>Color</th>
                            <th data-options="field:'size_name',halign:'center'" width="100">11<br/>Size</th>
                            <th data-options="field:'qty',halign:'center'" width="80" align="right">12<br/>Qty</th>
                            <th data-options="field:'qc_date',halign:'center'" align="right" width="80" >13<br/>Recv<br/>Date</th>
                            <th data-options="field:'cm_rate',halign:'center'" width="70" align="right">14<br/> CM Cost</th>
                            <th data-options="field:'cm_amount',halign:'center'" width="70" align="right">15<br/>CM <br/>Total Cost</th>
                            <th data-options="field:'recv_by',halign:'center'" align="right" width="100">16<br/>Recv By</th>
                        </tr>
                    </thead>
                </table>
            </div>     
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Subcontract Garment Production Report',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="prodgmtsewingproductionFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4">Service.Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Prod. Area</div>
                        <div class="col-sm-8">
                            {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Date Range</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Prod.Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Beneficiary </div>
                        <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Style Ref</div>
                        <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" ondblclick="
                            MsProdGmtSewingProduction.openStyleWindow()" placeholder=" Double Click" />
                            <input type="hidden" name="style_id" id="style_id" value="" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Sales Order</div>
                        <div class="col-sm-8">
                            <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="
                            MsProdGmtSewingProduction.openOrderWindow()" placeholder=" Double Click" />
                            <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />
                        </div>
                    </div>      
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtSewingProduction.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtSewingProduction.resetForm('prodgmtsewingproductionFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtSewingProduction.getpdf()">PDF</a>
            </div>
        </form>
    </div>
</div>
<div id="dailyreportImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="dailyreportImageWindowoutput" src=""/>
</div>
{{-- Style Filtering Search Window --}}
<div id="styleWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="stylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
                                <div class="col-sm-4">
                                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                <div class="col-sm-2 req-text">Style Ref. </div>
                                <div class="col-sm-4">
                                    <input type="text" name="style_ref" id="style_ref" value=""/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtSewingProduction.searchStyleGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="stylesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'receivedate'" width="80">Receive Date</th>
                        <th data-options="field:'style_ref'" width="120">Style Refference</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                        <th data-options="field:'productdepartment'" width="80">Product Department</th>
                        <th data-options="field:'season'" width="80">Season</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#styleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>  
{{-- Order window --}}
<div id="salesorderWindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="orderdlvprintsearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtSewingProduction.searchOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'sales_order_id'" width="40">ID</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'company_id'" width="120">Beneficiary</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'style_ref'" width="80">Style Ref</th>
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#salesorderWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Dealing Marchant --}}
<div id="prodgmtdlmerchantWindow" class="easyui-window" title="Merchandiser Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="prodgmtdealmctinfoTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'name'" width="120">Name</th>
                <th data-options="field:'date_of_join'" width="80px">Date Of Join </th>
                <th data-options="field:'last_education'" width="100px">Last Education</th>
                <th data-options="field:'contact'" width="100px">Contact</th>
                <th data-options="field:'experience'" width="100px">Experience</th>
                <th data-options="field:'email'" width="100px">Email</th>
                <th data-options="field:'address'" width="350px">Address</th>
            </tr>
        </thead>
    </table>
</div>
{{-- Buyer Details --}}
<div id="prodbuyerwindow" class="easyui-window" title="Buyer Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="prodbuyerTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'buyer_name'" width="120">Name</th>
                <th data-options="field:'branch_name'" width="100px">Branch Name</th>
                <th data-options="field:'contact_person'" width="100px">Contact Person</th>
                <th data-options="field:'email'" width="150px">Email Address</th>
                <th data-options="field:'designation'" width="150px">Designation</th>
                <th data-options="field:'address'" width="350px">Address</th>
            </tr>
        </thead>
    </table>
</div>
{{-- Service Provider/Supplier Details --}}
<div id="serviceproviderwindow" class="easyui-window" title="Service Provider Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="serviceproviderTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="50">ID</th>
                <th data-options="field:'name'" width="100">Name</th>
                <th data-options="field:'address'" width="300">Address</th>
                <th data-options="field:'contact_person'" width="150">Contact person</th>
                <th data-options="field:'nature_name'" width="120">Nature</th>
                <th data-options="field:'company_id'" width="100">Company</th>
                <th data-options="field:'vendor_code'" width="100">Vendor Code</th>
                <th data-options="field:'status_id'" width="100">Status</th>
            </tr>
        </thead>
    </table>
</div>
{{-- File source --}}
<div id="prodgmtfilesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="prodgmtfilesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsProdGmtSewingProduction.formatProdGmtShowFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>   
  
<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsProdGmtSewingProductionController.js"></script>
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
      $('#prodgmtsewingproductionFrm [id="supplier_id"]').combobox();
      $('#prodgmtsewingproductionFrm [id="buyer_id"]').combobox();
      $('#stylesearchFrm [id="buyer_id"]').combobox();
   })(jQuery);
</script>