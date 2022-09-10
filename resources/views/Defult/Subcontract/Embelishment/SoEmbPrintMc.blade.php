<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soembprintmctabs">
 <div title="Machine" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Machine Lists'" style="padding:2px">
    <table id="soembprintmcTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'asset_no'" width="100">Machine No</th>
       <th data-options="field:'company_name'" width="100">Company Name</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Add Machine',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
    style="width:350px; padding:2px">
    <form id="soembprintmcFrm">
     <div id="container">
      <div id="body">
       <code>
          <div class="row middle">
           <div class="col-sm-4 req-text">Machine No</div>
           <div class="col-sm-8">
            <input type="text" name="asset_no" id="asset_no" placeholder="Browse" ondblclick="MsSoEmbPrintMc.openstudyprintmcsetupWindow()">
            <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id">
           </div>
          </div>
          <div class="row middle">
              <div class="col-sm-4 req-text">Company</div>
              <div class="col-sm-8">
                  {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                  <input type="hidden" name="id" id="id" value="" />
              </div>
          </div>
          <div class="row middle">
              <div class="col-sm-4 req-text">Remarks</div>
              <div class="col-sm-8">
                 <textarea name="remarks" id="remarks"></textarea>
              </div>
          </div>  
         </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintMc.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintmcFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintMc.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Man Power Engaged" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'Man Power Details',iconCls:'icon-more',footer:'#workstudydetailft'"
    style="width:350px; padding:2px">
    <form id="soembprintmcdtlFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="so_emb_print_mc_id" id="so_emb_print_mc_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="from_date" id="from_date" class="datepicker" placeholder=" From" />
                                        <input type="hidden" name="to_date" id="to_date" class="datepicker"  placeholder=" To" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Operator</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="operator" id="operator" value="" class="number integer" onchange="MsSoEmbPrintMcDtl.calculateTotalmnt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Helper</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="helper" id="helper" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Supervisor</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_name" id="employee_name" value="" ondblclick="MsSoEmbPrintMcDtl.openEmployeeWindow()" placeholder="Browse"/>
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Working Hour</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="working_hour" id="working_hour" value="" class="number integer" onchange="MsSoEmbPrintMcDtl.calculateTotalmnt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">OTH</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="overtime_hour" id="overtime_hour" value="" class="number integer" onchange="MsSoEmbPrintMcDtl.calculateTotalmnt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Total Mnt</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="total_mnt" id="total_mnt" value="" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Target Hour</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="target_per_hour" id="target_per_hour" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Printing Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="printing_start_at" id="printing_start_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Printing Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="printing_end_at" id="printing_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Lunch Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lunch_start_at" id="lunch_start_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5  req-text">Lunch Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lunch_end_at" id="lunch_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Tiffin Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tiffin_start_at" id="tiffin_start_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Tiffin Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tiffin_end_at" id="tiffin_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Comments</div>
                                    <div class="col-sm-7">
                                       <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="workstudydetailft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintMcDtl.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintmcdtlFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintMcDtl.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Man Power Lists'" style="padding:2px">
    <table id="soembprintmcdtlTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'from_date'" width="80">From Date</th>
       <th data-options="field:'to_date'" width="80">To Date</th>
       <th data-options="field:'operator'" width="100">Operator</th>
       <th data-options="field:'helper'" width="100">Helper</th>
       <th data-options="field:'employee_name'" width="100">Supervisor</th>
       <th data-options="field:'working_hour'" width="100">WH</th>
       <th data-options="field:'overtime_hour'" width="100">OTH</th>
       <th data-options="field:'total_mnt'" width="100">Total MmT</th>
       <th data-options="field:'printing_start_at'" width="100">Printing Started</th>
       <th data-options="field:'printing_end_at'" width="100">Printing Ended</th>
       <th data-options="field:'lunch_start_at'" width="100">Lunch Started</th>
       <th data-options="field:'lunch_end_at'" width="100">Lunch Ended</th>
       <th data-options="field:'tiffin_start_at'" width="100">Tiffin Started</th>
       <th data-options="field:'tiffin_end_at'" width="100">Tiffin Ended</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Item Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Add Item',iconCls:'icon-more',footer:'#soembprintmcdtlordFrmft'"
    style="width:350px; padding:2px">
    <form id="soembprintmcdtlordFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="so_emb_print_mc_dtl_id" id="so_emb_print_mc_dtl_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                 <div class="col-sm-5">GMT Part</div>
                                 <div class="col-sm-7">
                                  <input type="text" name="gmtspart" id="gmtspart" ondblclick="MsSoEmbPrintMcDtlOrd.opensalesOrder();" placeholder="Double click">
                                  <input type="hidden" name="gmtspart_id" name="gmtspart_id">
                                 </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">GMT Item </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="item_desc" id="item_desc" readonly/>
                                        <input type="hidden" name="item_account_id" id="item_account_id" value="" />        
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Order No </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sale_order_no" id="sale_order_no" readonly/>
                                        <input type="hidden" name="so_emb_ref_id" id="so_emb_ref_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Printing Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="printing_start_at" id="printing_start_at" value="09:00:00 AM" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Printing Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="printing_end_at" id="printing_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Comments</div>
                                    <div class="col-sm-7">
                                       <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="soembprintmcdtlordFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintMcDtlOrd.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintmcdtlordFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintMcDtlOrd.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Item Lists'" style="padding:2px">
    <table id="soembprintmcdtlordTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'gmtspart'" width="100">GMT Part</th>
       <th data-options="field:'item_desc'" width="100">GMT Item</th>
       <th data-options="field:'sale_order_no'" width="100">Order No</th>
       <th data-options="field:'qty'" width="70">Qty</th>
       <th data-options="field:'printing_start_at'" width="100">Printin Hr<br /> Starts</th>
       <th data-options="field:'printing_end_at'" width="100">Printin Hr<br /> End</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Minute Ajustments" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Add Minute',iconCls:'icon-more',footer:'#wstudyminadjFrmft'"
    style="width:450px; padding:2px">
    <form id="soembprintmcdtlminajFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="so_emb_print_mc_dtl_id" id="so_emb_print_mc_dtl_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Adjustment Reasons</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('minute_adj_reason_id', $minuteadjustmentreasons,'',array('id'=>'minute_adj_reason_id')) !!} 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Hours</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_hour" id="no_of_hour" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">No of employee/resource</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_resource" id="no_of_resource" value="" class="number integer" onchange="MsSoEmbPrintMcDtlMinaj.calculateMinute()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Adjustable Minutes</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_minute" id="no_of_minute" value="" class="number integer" />
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="wstudyminadjFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintMcDtlMinaj.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintmcdtlminajFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintMcDtlMinaj.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Minute Lists'" style="padding:2px">
    <table id="soembprintmcdtlminajTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'minute_adj_reason_id'" width="150">Adjustment Reasons</th>
       <th data-options="field:'no_of_hour'" width="100">Hour</th>
       <th data-options="field:'no_of_resource'" width="100">No of employee/<br>resource</th>
       <th data-options="field:'no_of_minute'" width="120">Adjustable Minutes</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
</div>


{{-- Fixed Asset Search Window --}}
<div id="opensoembprintmcwindow" class="easyui-window" title="Asset Details Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
   <table id="opensoembprintmcTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'custom_no'" width="100">Custom Asset No</th>
      <th data-options="field:'asset_no'" width="100">Asset No</th>
      <th data-options="field:'employee_name'" width="100">Custody of</th>
      <th data-options="field:'asset_name'" width="100">Asset Name</th>
      <th data-options="field:'origin'" width="100">Origin</th>
      <th data-options="field:'origin_cost'" width="100">Origin Cost</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'company_id'" width="100">Company</th>
      <th data-options="field:'location_id'" width="100">Location</th>
      <th data-options="field:'type_id'" width="100">Asset Type</th>
      <th data-options="field:'production_area_id'" width="100">Production Area</th>
      <th data-options="field:'asset_group'" width="100">Group</th>
      <th data-options="field:'supplier_id'" width="100">Supplier</th>
      <th data-options="field:'salvage_value'" width="100">Salvage Value</th>
      <th data-options="field:'iregular_supplier'" width="100">Irragular Supplier</th>
      <th data-options="field:'prod_capacity'" width="100">Prod Capacity</th>
      <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
      <th data-options="field:'depreciation_method_id'" width="100">Dep. Method</th>
      <th data-options="field:'depreciation_rate'" width="100">Dep. Rate</th>
      <th data-options="field:'accumulated_dep'" width="100">Acummulated Dep</th>
     </tr>
    </thead>
   </table>
   <div id="assetdtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#opensoembprintmcwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetFrmft'" style="padding:2px; width:350px">
   <form id="opensoembprintmcFrm">
    <div id="container">
     <div id="body">
      <code>
            <div class="row middle">
                <div class="col-sm-4">Asset No</div>
                <div class="col-sm-8">
                    <input type="text" name="asset_no" id="asset_no" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4">Custom No</div>
                <div class="col-sm-8">
                    <input type="text" name="custom_no" id="custom_no" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4">Asset Name</div>
                <div class="col-sm-8">
                    <input type="text" name="asset_name" id="asset_name" />
                </div>
            </div>
        </code>
     </div>
    </div>
    <div id="assetFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsSoEmbPrintMc.searchPrintMcsetup()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<!--------------------Employee Search-Window Start------------------>
<div id="openemployeesearchwindow" class="easyui-window" title="Employee Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="employeesearchFrm">
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
      onClick="MsSoEmbPrintMcDtl.searchEmployee()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="employeesearchTbl" style="width:700px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'employee_name'" width="100">Name</th>
      <th data-options="field:'code'" width="100">User Given Code</th>
      <th data-options="field:'designation_name'" width="100">Designation</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'location_name'" width="100">Location</th>
      <th data-options="field:'division_name'" width="100">Division</th>
      <th data-options="field:'department_name'" width="100">Department</th>
      <th data-options="field:'section_name'" width="100">Section</th>
      <th data-options="field:'subsection_name'" width="100">Sub-Section</th>
      <th data-options="field:'contact'" width="100">Phone No</th>
      <th data-options="field:'email'" width="120">Email Address</th>
      <th data-options="field:'address'" width="100">Address</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openemployeesearchwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

{{-- Sales Order Window Start --}}
<div id="opensalesorderwindow" class="easyui-window" title="Sales Order Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#salesorderft'" style="padding:2px">
   <table id="salesorderTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="50">ID</th>
      <th data-options="field:'buyer_name'" width="100">Buyer</th>
      <th data-options="field:'style_ref'" width="100">Style Ref</th>
      <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
      <th data-options="field:'emb_name'" width="100">Emb. Name</th>
      <th data-options="field:'emb_type'" width="100">Emb. Type</th>
      <th data-options="field:'emb_size'" width="80">Emb. Size</th>
      <th data-options="field:'item_desc'" width="100">GMT Item</th>
      <th data-options="field:'gmtspart'" width="100">GMT Part</th>
      <th data-options="field:'gmt_color'" width="100">GMT Color</th>
      <th data-options="field:'gmt_size'" width="80">GMT Size</th>
      <th data-options="field:'qty'" width="80" align="right">Qty</th>
      <th data-options="field:'uom_name'" width="40">Uom</th>

      <th data-options="field:'rate'" width="60" align="right">Rate</th>
      <th data-options="field:'amount'" width="80" align="right">Amount</th>
      <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
     </tr>
    </thead>
   </table>
   <div id="salesorderft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#opensalesorderwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#salesorderFrmft'" style="padding:2px; width:350px">
   <form id="salesorderFrm">
    <div id="container">
     <div id="body">
      <code>
            <div class="row middle">
                <div class="col-sm-4">Sales Order</div>
                <div class="col-sm-8">
                    <input type="text" name="sale_order_no" id="sale_order_no" />
                </div>
            </div>
            <div class="row middle">
             <div class="col-sm-4">Customer</div>
             <div class="col-sm-8">
              {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border-radius:2px')) !!}
             </div>
            </div>
        </code>
     </div>
    </div>
    <div id="salesorderFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsSoEmbPrintMcDtlOrd.searchSalesOrder()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllSoEmbPrintMcController.js">
</script>
<script>
 $('#salesorderFrm [name=buyer_id]').combobox();
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

    $(".timepicker").timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':30,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    //$('#printing_end_at').timepicker({defaultTime: '09:00:00 AM'});

    $(document).ready(function() {
	var chiefname = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
		url: msApp.baseUrl()+'/soembprintmcdtl/getchiefname?q=%QUERY%',
		wildcard: '%QUERY%'
		},
	});
	
	$('#line_chief').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'line_chief',
		limit:1000,
		source: chiefname,
		display: function(data) {
			return data.name  //Input value to be set when you select a suggestion. 
		},
		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function(data) {
				return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
			}
	    }
	});
});
</script>