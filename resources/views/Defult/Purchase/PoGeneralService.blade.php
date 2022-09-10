
<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="pogeneralserviceAccordion">
  <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
    <div class="easyui-layout"  data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="pogeneralserviceTbl" style="width:100%">
          <thead>
            <tr>
              <th data-options="field:'id'" width="40">ID</th>
              <th data-options="field:'po_no'" width="80">WO No</th>
              <th data-options="field:'po_date'" width="80">WO Date</th>
              <th data-options="field:'company_code'" width="60"  align="center">Company</th>
              <th data-options="field:'supplier_code'" width="160">Supplier</th>
              <th data-options="field:'pi_no'" width="160">PI No</th>
              <th data-options="field:'source'" width="70" align="center">Source</th>
              <th data-options="field:'item_qty'" width="80" align="right">Qty</th>
              <th data-options="field:'amount'" width="100" align="right">Amount</th>
              <th data-options="field:'exch_rate'" width="70" align="right">Conv.Rate</th>
              <th data-options="field:'currency_code'" width="60">Currency</th>
              <th data-options="field:'delv_start_date'" width="80" align="center">Delivery Start</th>
              <th data-options="field:'delv_end_date'" width="80" align="center">Delivery End</th>
              <th data-options="field:'paymode'" width="120">Pay Mode</th>
              <th data-options="field:'remarks'" width="180">Remarks</th>
              <th data-options="field:'approve_status'" width="80">Approve<br />Status</th>
            </tr>
          </thead>
        </table>
      </div>
      <div data-options="region:'west',border:true,title:'Work Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding:2px">
        <form id="pogeneralserviceFrm">
          <div id="container">
              <div id="body">
                <code>
                    <div class="row">
                      <div class="col-sm-4">WO No</div>
                        <div class="col-sm-8">
                      <input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                        </div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">WO Date</div>
                        <div class="col-sm-8"><input type="text" name="po_date" id="po_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Bnf.Company</div>
                        <div class="col-sm-8">
                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        <input type="hidden" name="id" id="id" value=""/>
                        </div>
                      </div>
                      <div class="row middle">   
                        <div class="col-sm-4 req-text">Source</div>
                        <div class="col-sm-8">{!! Form::select('source_id', $source,'',array('id'=>'source_id')) !!}</div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Supplier</div>
                        <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
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
                        <div class="col-sm-4 req-text">Exch.Rate</div>
                        <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Delivery Start</div>
                        <div class="col-sm-8"><input type="text" name="delv_start_date" id="delv_start_date" class="datepicker"/></div>
                        </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Delivery End</div>
                        <div class="col-sm-8"><input type="text" name="delv_end_date" id="delv_end_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">Indentor</div>
                        <div class="col-sm-8">
                          {!! Form::select('indentor_id', $indentor,'',array('id'=>'indentor_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                      </div>
                      <div class="row middle">  
                        <div class="col-sm-4">PI No.</div>
                        <div class="col-sm-8"><input type="text" name="pi_no" id="pi_no" value=""/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">PI Date</div>
                        <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">Remarks</div>
                        <div class="col-sm-8">
                          <textarea name="remarks" id="remarks"></textarea>
                        </div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">Price Verified By</div>
                        <div class="col-sm-8">{!! Form::select('price_verified_by_id', $user,'',array('id'=>'price_verified_by_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                      </div>
                </code>
            </div>
          </div>
          <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralService.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pogeneralserviceFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete"  onClick="MsPoGeneralService.remove()">Delete</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="pdf" onClick="MsPoGeneralService.pdf()" >PDF</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div title="Items" style="overflow:auto;padding:1px;height:520px">
    <div class="easyui-layout"  data-options="fit:true" >
      <div data-options="region:'west',border:true,title:'From',footer:'#pogeneralserviceitemfrmft'" style=" width:380px;padding:2px; ">
        <form id="pogeneralserviceitemFrm">
          <div id="container">
            <div id="body">
              <code>
                <div class="row">
                  <div class="col-sm-4">Service Desc.</div>
                  <div class="col-sm-8">
                    <textarea name="service_description" id="service_description"></textarea>
                    <input type="hidden" name="id" id="id" value=""/>  
                    <input type="hidden" name="po_general_service_id" id="po_general_service_id" value=""/>
                  </div>
                </div>  
                <div class="row middle">
                  <div class="col-sm-4 req-text" >WO. Qty</div>
                  <div class="col-sm-8"><input type="text" name="qty" id="qty" class="integer number" onchange="MsPoGeneralServiceItem.calculate()" /></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">UOM</div>
                  <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Rate</div>
                  <div class="col-sm-8"><input type="text" name="rate" id="rate" class="integer number" onchange="MsPoGeneralServiceItem.calculate()" /></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Amount</div>
                  <div class="col-sm-8"><input type="text" name="amount" id="amount" value="" class="integer number" readonly /></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Asset No</div>
                  <div class="col-sm-8">
                      <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id" />
                      <input type="text" name="asset_desc" id="asset_desc" ondblclick="MsPoGeneralServiceItem.openAssetWindow()" placeholder=" Double Click">
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Demand By</div>
                  <div class="col-sm-8">{!! Form::select('demand_by_id', $user,'',array('id'=>'demand_by_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Consuming Dept</div>
                  <div class="col-sm-8">
                      {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width:100%;border:2px;')) !!}
                  </div>
              </div>
                <div class="row middle">
                  <div class="col-sm-4">Remarks</div>
                  <div class="col-sm-8"> 
                    <textarea  name="remarks" id="remarks"></textarea>
                  </div>
                </div>
              </code>
            </div>
          </div>
          <div id="pogeneralserviceitemfrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralServiceItem.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pogeneralserviceitemFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoGeneralServiceItem.remove()" >Delete</a>
          </div>
        </form>
      </div>
      <div data-options="region:'center',border:true,title:'List',footer:'#pogeneralserviceitemtblft'" style="padding:2px; ">
        <div class="easyui-layout"  data-options="fit:true" >
          <table id="pogeneralserviceitemTbl" style="width:1220px">
            <thead>
              <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'service_description'" width="120">Service Desc</th>
                <th data-options="field:'uom_code'" width="50">UOM</th>
                <th data-options="field:'department_name'" width="80" align="right">Department</th>
                <th data-options="field:'demand_by'" width="80" align="right">Demand By</th>
                <th data-options="field:'asset_desc'" width="80" align="right">Asset No</th>
                <th data-options="field:'qty'" width="80" align="right">Qty</th>
                <th data-options="field:'rate'" width="80" align="right">Rate</th>
                <th data-options="field:'amount'" width="100" align="right">Amount</th>
              </tr>
            </thead>
          </table>
        </div>
      </div> 
    </div>
  </div>
  <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
  @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
  </div>
</div>

{{-- Asset No --}}
<div id="assetWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'center',border:true,footer:'#assetsearchTblFt'" style="padding:2px">
          <table id="assetsearchTbl" style="width:100%">
              <thead>
                  <tr>
                  <th data-options="field:'id'" width="80">ID</th>
                  <th data-options="field:'custom_no'" width="100">Machine No</th>
                  <th data-options="field:'asset_name'" width="100">Asset Name</th>
                  <th data-options="field:'origin'" width="100">Origin</th>
                  <th data-options="field:'brand'" width="100">Brand</th>
                  <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
                  <th data-options="field:'dia_width'" width="60">Dia/Width</th>
                  <th data-options="field:'gauge'" width="60">Gauge</th>
                  <th data-options="field:'extra_cylinder'" width="60">Extra Cylinder</th>
                  <th data-options="field:'no_of_feeder'" width="60">Feeder</th>
                  </tr>
              </thead>
          </table>
          <div id="assetsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#assetWindow').window('close')" style="width:80px">Close</a>
          </div>
      </div>
      <div data-options="region:'west',border:true,footer:'#asstsearchFrmFt'" style="padding:2px; width:350px">
          <form id="assetsearchFrm">
              <div id="container">
                  <div id="body">
                      <code>
                          <div class="row ">
                          <div class="col-sm-4">Machine No</div>
                          <div class="col-sm-8"> <input type="text" name="machine_no" id="machine_no" /> </div>
                          </div>
                          <div class="row middle ">
                          <div class="col-sm-4">Brand</div>
                          <div class="col-sm-8"> <input type="text" name="brand" id="brand" /> </div>
                          </div>
                      </code>
                  </div>
              </div>
              <div id="asstsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" onClick="MsPoGeneralServiceItem.searchAsset()" >Search</a>
              </div>
          </form>
      </div>
  </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoGeneralServiceController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoGeneralServiceItemController.js"></script>

<script>
  $(".datepicker" ).datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true
  });
  $('.integer').keyup(function () {
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }
  });
  $('#pogeneralserviceFrm [id="supplier_id"]').combobox();
  $('#pogeneralserviceFrm [id="indentor_id"]').combobox();
  $('#pogeneralserviceFrm [id="price_verified_by_id"]').combobox();
  $('#pogeneralserviceitemFrm [id="uom_id"]').combobox();
  $('#pogeneralserviceitemFrm [id="demand_by_id"]').combobox();
  $('#pogeneralserviceitemFrm [id="department_id"]').combobox();
</script>