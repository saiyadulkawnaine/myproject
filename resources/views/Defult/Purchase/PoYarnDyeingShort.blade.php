<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="poyarndyeingshorttabs">
 <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="poyarndyeingshortTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="60">ID</th>
       <th data-options="field:'po_no'" width="80">PO No</th>
       <th data-options="field:'company_code'" width="60" align="center">Beneficiary<br />Company
       </th>
       <th data-options="field:'supplier_code'" width="160">Supplier</th>
       <th data-options="field:'pi_no'" width="160">PI No</th>
       <th data-options="field:'source'" width="70" align="center">Source</th>
       <th data-options="field:'item_qty'" width="100" align="right">Qty</th>
       <th data-options="field:'amount'" width="100" align="right">Amount</th>
       <th data-options="field:'delv_start_date'" width="100" align="center">Delivery Start</th>
       <th data-options="field:'delv_end_date'" width="100" align="center">Delivery End</th>
       <th data-options="field:'paymode'" width="120">Pay Mode</th>
       <th data-options="field:'currency_code'" width="70">Currency</th>
       <th data-options="field:'exch_rate'" width="70">Exch Rate</th>
       <th data-options="field:'remarks'" width="200">Remarks</th>
       <th data-options="field:'approve_status'" width="80">Approve<br />Status</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'"
    style="width:350px; padding:2px">
    <form id="poyarndyeingshortFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <div class="col-sm-4">Po No</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                                    <input type="hidden" name="id" id="id" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Po Date</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="po_date" id="po_date" class="datepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Bnf.Company</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">   
                                    <div class="col-sm-4 req-text">Source</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('source_id', $source,'',array('id'=>'source_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Basis</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('basis_id', $basis,'',array('id'=>'basis_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Supplier</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width:100%;border-radius:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Pay Mode</div>
                                    <div class="col-sm-8">{!! Form::select('pay_mode', $paymode,'',array('id'=>'pay_mode')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Currency</div>
                                    <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                                    </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Exchange Rate  </div>
                                    <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery Start  </div>
                                    <div class="col-sm-8"><input type="text" name="delv_start_date" id="delv_start_date" class="datepicker"/></div>
                                    </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery End  </div>
                                    <div class="col-sm-8"><input type="text" name="delv_end_date" id="delv_end_date" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                    
                                    <div class="col-sm-4">PI No.  </div>
                                    <div class="col-sm-8"><input type="text" name="pi_no" id="pi_no" value=""/></div>
                                    </div>
                                <div class="row middle">
                                    <div class="col-sm-4">PI Date  </div>
                                    <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks  </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnDyeingShort.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poyarndyeingshortFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnDyeingShort.remove()">Delete</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="pdf" onClick="MsPoYarnDyeingShort.pdf()">PDF</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Yarn" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Yarn Entry',iconCls:'icon-more',footer:'#depft'"
    style="width:400px; padding:2px">
    <form id="poyarndyeingshortitemFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row" style="display:none">
                                    <input type="hidden" name="id" id="id">
                                    <input type="hidden" name="po_yarn_dyeing_id" id="po_yarn_dyeing_id"/>
                                </div> 
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Desc</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_des" id="yarn_des" ondblclick="MsPoYarnDyeingShortItem.openYarnDyeInvYarnWindow()" placeholder="Double Click To Select Yarn" readonly />
                                        <input type="hidden" name="inv_yarn_item_id" id="inv_yarn_item_id"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Lot</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_color_name" id="yarn_color_name" disabled />
                                    </div>
                                </div>
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Remark</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                
                                
                            </code>
      </div>
     </div>
     <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnDyeingShortItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poyarndyeingshortitemFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnDyeingShortItem.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="poyarndyeingshortitemTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="50">ID</th>
       <th data-options="field:'supplier_name'" width="100">Supplier</th>
       <th data-options="field:'yarn_des'" width="150">Yarn</th>
       <th data-options="field:'yarn_color_name'" width="100">Yarn Color</th>
       <th data-options="field:'lot'" width="100">Lot</th>
       <th data-options="field:'brand'" width="100">Brand</th>
       <th data-options="field:'qty'" width="70px" align="right">PO. Qty</th>
       <th data-options="field:'rate'" width="70px" align="right">Rate</th>
       <th data-options="field:'amount'" width="70px" align="right">Amount</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Responsible" style="overflow:auto;padding:1px;height:520px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="poyarndyeingshortitemrespTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'section'" width="120">Section</th>
       <th data-options="field:'employee_name'" width="100">Person</th>
       <th data-options="field:'company_code'" width="60" align="center">Company</th>
       <th data-options="field:'reason'" width="150" align="center">Reason</th>
       <th data-options="field:'short_type'" width="100">Short Type</th>
       <th data-options="field:'cost_share_per'" width="100">Cost Sharing</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Work Order Responsible Reason',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft5'"
    style="width:330px; padding:2px">
    <form id="poyarndyeingshortitemrespFrm">
     <div id="container">
      <div id="body">
       <code>
                   <div class="row middle">
                     <div class="col-sm-5 req-text">Section</div>
                     <div class="col-sm-7">
                       {!! Form::select('section_id', $section,'',array('id'=>'section_id')) !!}
                       <input type="hidden" name="id" id="id" value=""/>
                       <input type="hidden" name="po_yarn_dyeing_item_id" id="po_yarn_dyeing_item_id" value=""/>
                     </div>
                   </div>
                   <div class="row middle">
                     <div class="col-sm-5">Short Type</div>
                     <div class="col-sm-7">
                       {!! Form::select('short_type_id', $shorttype,'',array('id'=>'short_type_id')) !!}
                     </div>
                   </div>
                   <div class="row middle">
                     <div class="col-sm-5 req-text">Reason</div>
                     <div class="col-sm-7">
                       <textarea name="reason" id="reason"></textarea>
                     </div>
                   </div>
                   <div class="row middle">
                     <div class="col-sm-5">Person</div>
                     <div class="col-sm-7">
                         <input type="text" name="employee_name" id="employee_name"  placeholder=" Double Click To Select" ondblclick="MsPoYarnDyeingShortItemResp.openDyeingResponsibleEmpHrWindow()" />
                         <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" value="" />
                     </div>
                   </div>
                   <div class="row middle">
                     <div class="col-sm-5 req-text">Cost Sharing</div>
                     <div class="col-sm-7">
                       <input type="text" name="cost_share_per" id="cost_share_per" class="integer number"/>
                     </div>
                   </div>
                 </code>
      </div>
     </div>
     <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnDyeingShortItemResp.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnDyeingShortItemResp.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Sales Order & Color" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'Yarn Entry',iconCls:'icon-more',footer:'#poyarndyeingshortitembomqtyFrmFt'"
    style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <form id="poyarndyeingshortitembomqtyFrm">
      <div class="row" style="display:none">
       <input type="hidden" name="id" id="id">
       <input type="hidden" name="po_yarn_dyeing_id" id="po_yarn_dyeing_id" />
      </div>
      <div class="row middle">
       <div class="col-sm-4">Sales Order</div>
       <div class="col-sm-8">
        <input type="hidden" name="budget_yarn_dyeing_con_id" id="budget_yarn_dyeing_con_id" />
        <input type="hidden" name="sales_order_id" id="sales_order_id" />
        <input type="text" name="sale_order_no" id="sale_order_no"
         ondblclick="MsPoYarnDyeingShortItemBomQty.openDyeSaleOrderWindow()"
         placeholder="Double Click To Pick Sale Order" readonly />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Yarn Color</div>
       <div class="col-sm-8">
        <input type="text" name="yarn_color_name" id="yarn_color_name" disabled />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Color Range</div>
       <div class="col-sm-8">
        {!! Form::select('colorrange_id',$colorrange,'',array('id'=>'colorrange_id')) !!}
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Bom Rate</div>
       <div class="col-sm-8">
        <input type="text" name="bom_rate" id="bom_rate" readonly />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Blance Qty</div>
       <div class="col-sm-8">
        <input type="text" name="blance_qty" id="blance_qty" readonly />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">PO Qty</div>
       <div class="col-sm-8">
        <input type="text" name="qty" id="qty" class="number integer"
         onchange="MsPoYarnDyeingShortItemBomQty.calculateAmountfrom()" />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Rate</div>
       <div class="col-sm-8">
        <input type="text" name="rate" id="rate" class="number integer"
         onchange="MsPoYarnDyeingShortItemBomQty.calculateAmountfrom()" />
       </div>
      </div>

      <div class="row middle">
       <div class="col-sm-4">Amount</div>
       <div class="col-sm-8">
        <input type="text" name="amount" id="amount" class="number integer" readonly />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Process Loss %</div>
       <div class="col-sm-8">
        <input type="text" name="process_loss_per" id="process_loss_per" class="number integer"
         onchange="MsPoYarnDyeingShortItemBomQty.calculateReqConefrom()" />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Wgt/Cone</div>
       <div class="col-sm-8">
        <input type="text" name="wgt_per_cone" id="wgt_per_cone" class="number integer"
         onchange="MsPoYarnDyeingShortItemBomQty.calculateReqConefrom()" />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Required Cone</div>
       <div class="col-sm-8">
        <input type="text" name="req_cone" id="req_cone" class="number integer" />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Remark</div>
       <div class="col-sm-8">
        <textarea name="remarks" id="remarks"></textarea>
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Style</div>
       <div class="col-sm-8">
        <input type="text" name="style_ref" id="style_ref" disabled />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Buyer</div>
       <div class="col-sm-8">
        <input type="text" name="buyer_name" id="buyer_name" disabled />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Beneficiary</div>
       <div class="col-sm-8">
        <input type="text" name="company_name" id="company_name" disabled />
       </div>
      </div>
      <div class="row middle">
       <div class="col-sm-4">Prod.Company</div>
       <div class="col-sm-8">
        <input type="text" name="produced_company_name" id="produced_company_name" disabled />
       </div>
      </div>
      <div id="poyarndyeingshortitembomqtyFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
       <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
        iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnDyeingShortItemBomQty.submit()">Save</a>
       <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
        iconCls="icon-remove" plain="true" id="delete"
        onClick="msApp.resetForm('poyarndyeingshortitembomqtyFrm')">Reset</a>
       <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
        iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnDyeingShortItemBomQty.remove()">Delete</a>
      </div>
     </form>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="poyarndyeingshortitembomqtyTbl" style="width:800px">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'style_ref'" width="70">Style Ref</th>
       <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
       <th data-options="field:'buyer_name'" width="100">Buyer</th>
       <th data-options="field:'gmt_color_name'" width="100">GMT Color</th>
       <th data-options="field:'yarn_color_name'" width="100">Yarn Color</th>
       <th data-options="field:'bom_qty'" width="100">BOM Qty</th>
       <th data-options="field:'bom_rate'" width="100">Bom Rate</th>
       <th data-options="field:'bom_amount'" width="100">Bom Amount</th>
       <th data-options="field:'measurment'" width="100">Measurment</th>
       <th data-options="field:'feeder'" width="100">Feeder</th>
       <th data-options="field:'qty'" width="100">Qty</th>
       <th data-options="field:'rate'" width="100">Rate</th>
       <th data-options="field:'amount'" width="100">Amount</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
  @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
 </div>
</div>
<!--------------------Item Search-Window Start------------------>
<div id="dyeinvyarnitemwindow" class="easyui-window" title="Yarn Dyeing Service Order /Inventory Yarn Item Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="yarndyeinvyarnitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Item Category</div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Item Class </div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsPoYarnDyeingShortItem.searchYarnDyeRcvYarnItem()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="yarndyeinvyarnitemsearchTbl" style="width:610px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'yarn_des'" width="100">Yarn Description</th>
      <th data-options="field:'itemcategory_name'" width="100">Yarn Category</th>
      <th data-options="field:'itemclass_name'" width="100">Item Class</th>
      <th data-options="field:'lot'" width="70">Lot</th>
      <th data-options="field:'yarn_count'" width="70">Count</th>
      <th data-options="field:'item_description'" width="100">Item Description</th>
      <th data-options="field:'yarn_type'" width="80">Yarn Type</th>
      <th data-options="field:'yarn_color_name'" width="100">Color</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'supplier_name'" width="100">Supplier</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#dyeinvyarnitemwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
<!--------------------Sales Order Search-Window Ends----------------->
<div id="poyarndyeingshortitembomqtymultiWindow" class="easyui-window" title="PO Yarn Dyeing Qty"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',footer:'#poyarndyeingshortitembomqtymultiFrmFt'" style="padding:10px;">
   <form id="poyarndyeingshortitembomqtymultiFrm">
    <code id="poyarndyeingshortitembomqtymultiscs">
            </code>
   </form>
   <div id="poyarndyeingshortitembomqtymultiFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnDyeingShortItemBomQty.submitMalti()">Save</a>

   </div>
  </div>
 </div>
</div>

<div id="poyarndyesaleordercolorsearchwindow" class="easyui-window" title="PO Yarn Dyeing Item / Sales Order Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search',footer:'#poyarndyesaleordercolorsearchFrmFt'"
   style="width:400px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                         <form id="poyarndyesaleordercolorsearchFrm">
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
                                <div class="col-sm-4">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                         </form>
                     </code>
    </div>

    <div id="poyarndyesaleordercolorsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save"
      onClick="MsPoYarnDyeingShortItemBomQty.searchDyeSaleOrderGrid()">Search</a>

    </div>
   </div>
  </div>
  <div data-options="region:'center',footer:'#poyarndyesaleordercolorsearchTblFt'" style="padding:10px;">
   <table id="poyarndyesaleordercolorsearchTbl" style="width:800px">
    <thead>
     <tr>
      <th data-options="field:'sales_order_id'" width="40">ID</th>
      <th data-options="field:'style_ref'" width="70">Style Ref</th>
      <th data-options="field:'job_no'" width="90">Job No</th>
      <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
      <th data-options="field:'ship_date'" width="100">Ship Date</th>
      <th data-options="field:'buyer_name'" width="100">Buyer</th>
      <th data-options="field:'company_name'" width="80">Company</th>
      <th data-options="field:'produced_company_name'" width="140">Produced Company</th>
      <th data-options="field:'gmt_color_name'" width="100">GMT Color</th>
      <th data-options="field:'yarn_color_name'" width="100">Yarn Color</th>
      <th data-options="field:'bom_qty'" width="100">BOM Qty</th>
      <th data-options="field:'rate'" width="100">Rate</th>
      <th data-options="field:'amount'" width="100">Amount</th>
      <th data-options="field:'measurment'" width="100">Measurment</th>
      <th data-options="field:'feeder'" width="100">Feeder</th>

     </tr>
    </thead>
   </table>
   <div id="poyarndyesaleordercolorsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save"
     onClick="MsPoYarnDyeingShortItemBomQty.closeSaleorderColorsearchWindow()">Close</a>

   </div>
  </div>

 </div>
</div>

{{-- open Employee Search Window --}}
<div id="openrespemployhrwindow" class="easyui-window" title="Employee Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                      <form id="employeerespsearchFrm">
                          <div class="row middle">
                              <div class="col-sm-4">Company</div>
                              <div class="col-sm-8">
                                  {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                              </div>
                          </div>
                          <div class="row middle">
                              <div class="col-sm-4">Designation</div>
                              <div class="col-sm-8">
                                  {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                              </div>
                          </div>
                          <div class="row middle">
                              <div class="col-sm-4">Department </div>
                              <div class="col-sm-8">
                                  {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                              </div>
                          </div>
                      </form>
                  </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsPoYarnDyeingShortItemResp.searchResponsibleEmployeeHr()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="employeerespsearchTbl" style="width:700px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'employee_name'" width="100">Name</th>
      <th data-options="field:'code'" width="100">User Given Code</th>
      <th data-options="field:'designation'" width="100">Designation</th>
      <th data-options="field:'department'" width="100">Department</th>
      <th data-options="field:'company'" width="100">Company</th>
      <th data-options="field:'contact'" width="100">Phone No</th>
      <th data-options="field:'email'" width="120">Email Address</th>
      <th data-options="field:'last_education'" width="100">Last Education</th>
      <th data-options="field:'experience'" width="100">Experience</th>
      <th data-options="field:'address'" width="100">Address</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openrespemployhrwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>



<!------------------------------------->

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllPoYarnDyeingShortController.js"></script>
<script>
 (function(){
    $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
    });
    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
    });
    $('#poyarndyeingshortFrm [id="supplier_id"]').combobox();

})(jQuery);
</script>