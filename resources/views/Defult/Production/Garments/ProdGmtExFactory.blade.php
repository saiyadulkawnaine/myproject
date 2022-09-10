<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtexfactorytabs">
    <div title="Ex Factory Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodgmtexfactoryTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="40">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Buyer</th>
                            <th data-options="field:'location_id'" width="70">Location</th>
                            <th data-options="field:'transport_agent_id'" width="100">Forward Agent</th>
                            <th data-options="field:'exfactory_date'" width="80">Exfactory Date</th>
                            <th data-options="field:'invoice_no'" width="100">Invoice No</th>
                            <th data-options="field:'port_of_loading'" width="100">Port of loading</th>
                            <th data-options="field:'truck_no'" width="100">Truck No</th>
                            <th data-options="field:'lock_no'" width="80">Lock No</th>
                            <th data-options="field:'depo_name'" width="80">Depo</th>
                            <th data-options="field:'driver_name'" width="100">Driver</th>
                            <th data-options="field:'driver_contact_no'" width="120">Driver Contact</th>
                            <th data-options="field:'driver_license_no'" width="120">Driver License</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#ft2'" style="width: 380px; padding:2px">
                <form id="prodgmtexfactoryFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Company </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border-radious:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Prod. Company</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_id" id="supplier_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Location </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Exfactory date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exfactory_date" id="exfactory_date" class="datepicker" />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Transporter</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('transport_agent_id', $transportAgents,'',array('id'=>'transport_agent_id')) !!}
                                    </div>
                                </div>    
                                <div class="row middle">
                                    <div class="col-sm-4">Forwarder</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('forwarding_agent_id', $forwardingAgents,'',array('id'=>'forwarding_agent_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice No </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="invoice_no" id="invoice_no" value="" ondblclick="MsProdGmtExFactory.openExpInvoiceWindow()" placeholder=" Double Click" readonly="">
                                    <input type="hidden" name="exp_invoice_id" id="exp_invoice_id" value="" //>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Port of Loading</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="port_of_loading" id="port_of_loading" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver Name </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="driver_name" id="driver_name" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver Contact</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="driver_contact_no" id="driver_contact_no" placeholder="(+88) 01234567890" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver License No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="driver_license_no" id="driver_license_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lock
                                     No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lock_no" id="lock_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Truck
                                     No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="truck_no" id="truck_no" value="" placeholder=" write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Recipient</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="recipient" id="recipient" value="" placeholder=" Write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">DEPO</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="depo_name" id="depo_name" value="" placeholder=" Write" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtExFactory.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtexfactoryFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtExFactory.remove()">Delete</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsProdGmtExFactory.pdf()">Challan/GPS</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-----===============  =============----------->
    <div title="Ex-Factory Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#ft3'" style="padding:2px">
                
                        <table id="prodgmtexfactoryqtyTbl" style="width:100%">
                        <thead>
                        <tr>
                        <th field="ck" checkbox="true"></th>
                        <th data-options="field:'id'" width="40">Carton Id</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                        <th data-options="field:'job_no'" width="80">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'country_id'" width="90">Country</th>  
                        <th data-options="field:'assortment_name'" width="100"> Assortment Name</th> 
                        <th data-options="field:'packing_type'" width="100"> Packing Type</th> 
                        <th data-options="field:'qty'" width="100" align="right"> GMT/Carton</th>
                        
                        <th data-options="field:'order_rate'" width="100" align="right"> Rate/GMT</th> 
                        <th data-options="field:'amount'" width="100" align="right"> Amount/Carton</th>
                        </tr>
                        </thead>
                        </table>
                         <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                            <a href="javascript:void(0)" class="easyui-linkbutton" style="height:25px; border-radius:1px; color: #000" plain="true" id="save" >
                                Number of Carton
                            </a>
                            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" plain="true" id="save" >
                                <input type="text" name="no_of_carton" id="no_of_carton" class="number integer"  />
                            </a>
                            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px; border-radius:1px" plain="true" id="select" onClick="MsProdGmtExFactoryQty.select()">Click</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtExFactoryQty.submit()">Save</a>
                        </div>

            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#ft4'" style="width: 400px; padding:2px">
                <form id="prodgmtexfactoryqtyFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_ex_factory_id" id="prod_gmt_ex_factory_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Country </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="" ondblclick="MsProdGmtExFactoryQty.openExStyleWindow()" placeholder=" Double Click" />
                                        <input type="hidden" name="style_id" id="style_id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Carton. Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder=" From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder=" To" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtExFactoryQty.search()">Search</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtexfactoryqtyFrm')">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div title="Ship Out" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                
                        <table id="prodgmtshipoutqtyTbl" style="width:100%">
                        <thead>
                        <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'prod_gmt_carton_detail_id'" width="40">Carton Id</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                        <th data-options="field:'job_no'" width="80">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'country_id'" width="90">Country</th>
                        <th data-options="field:'assortment_name'" width="100"> Assortment Name</th>
                        <th data-options="field:'packing_type'" width="100"> Packing Type</th>    
                        <th data-options="field:'qty'" width="100" align="right"> GMT/Carton</th>
                        
                        <th data-options="field:'order_rate'" width="100" align="right"> Rate/GMT</th> 
                        <th data-options="field:'amount'" width="100" align="right"> Amount/Carton</th>
                        <th data-options="field:'ac_button'" width="100" align="right" formatter="MsProdGmtExFactoryQty.formatDetail"></th>
                        </tr>
                        </thead>
                        </table>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#ft6'" style="width: 400px; padding:2px">
                <form id="prodgmtshipoutqtyFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_ex_factory_id" id="prod_gmt_ex_factory_id" value="" />
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Country </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtExFactoryQty.searchShipOut()">Search</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtshipoutqtyFrm')">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--------------------------------------->
<div id="exstylewindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
             <div class="easyui-layout" data-options="fit:true">
                 <div id="body">
                     <code>
                         <form id="exstylesearch">
                             <div class="row">
                                 <div class="col-sm-2">Buyer :</div>
                                 <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                 </div>
                                  <div class="col-sm-2 req-text">Style Ref. </div>
                                 <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                             </div>
                             <div class="row middle">
                                  <div class="col-sm-2">Style Des.  </div>
                                  <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                             </div>
                         </form>
                     </code>
                 </div>
                 <p class="footer">
                     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtExFactoryQty.showExStyleGrid()">Search</a>
                 </p>
             </div>
         </div>
        <div data-options="region:'center'" style="padding:10px;">
             <table id="exstyleTbl" style="width:610px">
               <thead>
                   <tr>
                       <th data-options="field:'id'" width="50">ID</th>
                       <th data-options="field:'buyer_name'" width="70">Buyer</th>
                       <th data-options="field:'receive_date'" width="80">Receive Date</th>
                       <th data-options="field:'style_ref'" width="100">Style Refference</th>
                       <th data-options="field:'style_description'" width="150">Style Description</th>
                       <th data-options="field:'dept_category_id'" width="80">Dept. Category</th>
                       <th data-options="field:'department_name'" width="80">Product Department</th>
                       <th data-options="field:'season_name'" width="80">Season</th>
                  </tr>
               </thead>
             </table>
         </div>
         <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
             <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#exstylewindow').window('close')" style="width:80px">Close</a>
         </div>
     </div>
 </div>
{{-- <div id="opencartonwindow" class="easyui-window" title="Sales Contract Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                    <form id="cartonsearchFrm">
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
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtExFactory.showCartonGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="cartonsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'buyer_id'" width="100">Buyer</th>
                        <th data-options="field:'carton_date'" width="100">Carton Date</th>
                        <th data-options="field:'order_source_id'" width="120">Order Source</th>
                        <th data-options="field:'prod_source_id'" width="100">Production Source</th>
                        <th data-options="field:'location_id'" width="100">Location</th>
                        <th data-options="field:'shiftname_id'" width="120">Shift Name</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opencartonwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div> --}}
<!--------------------------------------->
<!-----------------Export Invoice---------------------->
<div id="openinvoicewindow" class="easyui-window" title="Export Commercial Invoice Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="expinvoicesearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Invoice No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="invoice_no" id="invoice_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Invoice Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="invoice_date" id="invoice_date" value="" class="datepicker" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtExFactory.searchExpInvoice()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="expinvoicesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'lc_sc_no'" width="100">LC/SC No</th>
                        <th data-options="field:'invoice_no'" width="100">Invoice No</th>
                        <th data-options="field:'invoice_date'" width="100">Invoice Date</th>
                        <th data-options="field:'invoice_value'" width="100">Invoice Value</th>
                        <th data-options="field:'exp_form_no'" width="100">Exp Form No</th>
                        <th data-options="field:'exp_form_date'" width="100">Exp Form Date</th>
                        <th data-options="field:'actual_ship_date'" width="100">Actual Ship Date</th>
                        <th data-options="field:'net_wgt_exp_qty'" width="70" align="right">Net Wgt</th>
                        <th data-options="field:'gross_wgt_exp_qty'" width="70" align="right">Gross Wgt</th>
                        <th data-options="field:'remarks'" width="120">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openinvoicewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllExFactoryController.js"></script>

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
      $('#prodgmtexfactoryFrm [id="buyer_id"]').combobox();
   })(jQuery);

</script>

