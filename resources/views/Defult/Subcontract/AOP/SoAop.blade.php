<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="subaopworkrcvtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#soaopTblFt'" style="padding:2px">
                <table id="soaopTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'buyer_name'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'receive_date'" width="100">Rceive Date</th>
                            <th data-options="field:'currency_name'" width="70">Currency</th>
                            <th data-options="field:'exch_rate'" width="70">Conv.Rate</th>
                            <th data-options="field:'teammember_name'" width="100">Teammember</th>
                            <th data-options="field:'remarks'" width="180">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="soaopTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Customer: {!! Form::select('customer_id',
                     $buyer,'',array('id'=>'customer_id','style'=>'width:200px;height: 25px')) !!} Receive Date: <input type="text" name="from_date" id="from_date" class="datepicker" style="width: 100px ;height: 23px" />
                     <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
                     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsSoAop.searchSoAopList()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soaopFrmft'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soaopFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Team Member</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">MKT Ref.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sub_inb_marketing_id" id="sub_inb_marketing_id" ondblclick="MsSoAop.openSubInbMktWindow()" placeholder=" Double click" />
                                    </div>
                                </div>                                   
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">WO/SO No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoAop.soaoppoWindowOpen()" />
                                        <input type="hidden" name="po_aop_service_id" id="po_aop_service_id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rceive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Currency</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Exch. Rate  </div>
                                    <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="soaopFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAop.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAop.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soaopitemFrmft'" style="width:450px; padding:2px">
                <form id="soaopitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_aop_id" id="so_aop_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="po_aop_service_item_id" id="po_aop_service_item_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabrication </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabrication" id="fabrication" value=""  ondblclick="MsSoAopItem.soaopitemWindowOpen()"  placeholder="Double Click to Select" />
                                        <input type="hidden" name="autoyarn_id" id="autoyarn_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Gmt Parts </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Looks </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Shape </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GSM </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsm_weight" id="gsm_weight" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_color" id="fabric_color" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Aop Type</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("embelishment_type_id", $aoptype,'',array('id'=>'embelishment_type_id')) !!} 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Coverage%</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="coverage" id="coverage" class="number integer" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Impression</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="impression" id="impression" class="number integer" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Dia</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dia" id="dia" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Measurment</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="measurment" id="measurment" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">UOM </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoAopItem.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Bill For</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("bill_for", $billfor,'',array('id'=>'bill_for')) !!} 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoAopItem.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Value </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_date" id="delivery_date" value="" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Delivery Point </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_point" id="delivery_point" value="" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('gmt_buyer', $buyer,'',array('id'=>'gmt_buyer','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_style_ref" id="gmt_style_ref" value="" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_sale_order_no" id="gmt_sale_order_no" value="" />
                                    </div>
                                </div>   
                            </code>
                        </div>
                    </div>
                    <div id="soaopitemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopItem.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="soaopitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'buyer_name'" width="80">Buyer</th>
                            <th data-options="field:'style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'gmtspart'" width="70">GMT Part</th>
                            <th data-options="field:'construction_name'" width="100">Constructions</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'fabric_color_id'" width="80" align="right">Fabric Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'aoptype'" width="80">Dye Type</th>
                            <th data-options="field:'dia'" width="80">Dia</th>
                            <th data-options="field:'measurment'" width="80">Measurement</th>
                            <th data-options="field:'uom_name'" width="80">Uom</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'pcs_qty'" width="70" align="right">EQV Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Upload File" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add File',iconCls:'icon-more',footer:'#assetimageft'" style="width:450px; padding:2px">
                <form id="soaopfileFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="so_aop_id" id="so_aop_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">File Upload</div>
                                    <div class="col-sm-8">
                                        <input type="file" id="file_src" name="file_src" />
                                    </div>
                                </div>                               
                            </code>
                        </div>
                    </div>
                    <div id="assetimageft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFile.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopfileFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFile.remove()">Delete</a>
                    </div>
                </form>   
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soaopfileTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'file_src',halign:'center',align:'center'" width="100" formatter="MsSoAopFile.formatimage">Uploaded File</th>
                            <th data-options="field:'id'"  formatter="MsSoAopFile.formatFile"  width="100">Download</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Subcontract Inbound Marketing Window --}}
<div id="subinbmktwindow" class="easyui-window" title="Subcontract Inbound Marketing" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#marketingwindowft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="subinbmktsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            
                            <div class="row middle">
                                <div class="col-sm-4">Customer</div>
                                <div class="col-sm-8">
                                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4"> Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="mkt_date" id="mkt_date" class="datepicker" placeholder="YY-MM-DD" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; border-radius:1px" onClick="MsSoAop.showMktGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="subinbmktsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'production_area_id'" width="100">Prod. Capacity</th>
                        <th data-options="field:'team_name'" width="100">Team</th>
                        <th data-options="field:'teammember'" width="100">Marketing Member</th>
                        <th data-options="field:'buyer_id'" width="100">Customer</th>
                        <th data-options="field:'contact'" width="100">Contact</th>
                        <th data-options="field:'contact_no'" width="100">Contact No</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#subinbmktwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

{{--   Aop service order Window --}}
<div id="soaoppoWindow" class="easyui-window" title="Subcontract Inbound Marketing" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soaoppowindowft'" style="width:300px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soaopposearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Work Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="po_no" id="po_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soaoppowindowft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoAop.getsoaoppo()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soaopposearchTblft'" style="padding:10px;">
            <table id="soaopposearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'po_no'" width="100">WO No</th>
                        <th data-options="field:'po_date'" width="80">WO Date</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'currency_code'" width="60">Currency</th>
                        <th data-options="field:'exch_rate'" width="60">Exch.<br/>Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Wo.Amount</th>
                        <th data-options="field:'pi_no'" width="60">PI No</th>
                        <th data-options="field:'pi_date'" width="60">PI Date</th>
                        <th data-options="field:'remarks'" width="200">Remarks</th>
                    </tr>
                </thead>
            </table>
             <div id="soaopposearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soaoppoWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>
{{-- Item Account Window --}}
<div id="soaopitemWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#soaopitemsearchTblft'" style="padding:2px">
            <table id="soaopitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Construction</th>
                    <th data-options="field:'composition_name'" width="300">Composition</th>
                    </tr>
                </thead>
            </table>
            <div id="soaopitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soaopitemWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#soaopitemsearchFrmft'" style="padding:2px; width:350px">
            <form id="soaopitemsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Construction</div>
                            <div class="col-sm-8"> <input type="text" name="construction_name" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Composition</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="composition_name" />
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="soaopitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsSoAopItem.searchItem()" >Search</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
<!-------==============Work Order File Pop Up===================-------------->
<div id="assetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <div id="assetImageWindowoutput"></div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFileController.js"></script>
<script>
$(document).ready(function() {
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
    $('#soaopFrm [id="buyer_id"]').combobox();
    $('#soaopFrm [id="teammember_id"]').combobox();
    $('#soaopitemFrm [id="gmt_buyer"]').combobox();
    $('#soaopitemFrm [id="uom_id"]').combobox();
    $('#soaopTblFt [id="customer_id"]').combobox();

    //$('#soaopitemFrm [id="fabric_color_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>
    