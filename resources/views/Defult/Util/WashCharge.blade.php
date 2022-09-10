
  <div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Embelishment Charge" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'west',split:true, title:'Embelishment Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="washchargeFrm">
            <div class="row">
                   <div class="col-sm-4 req-text">Company</div>
                   <div class="col-sm-8">
                   {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                   <input type="hidden" name="id" id="id" value=""/>
                   <input type="hidden" name="production_area_id" id="production_area_id" readonly/>

                   </div>
               </div>
               <div class="row middle">
                   <div class="col-sm-4 req-text">Embel. Name </div>
                   <div class="col-sm-8">{!! Form::select('embelishment_id', $embelishment,'',array('id'=>'embelishment_id','onChange'=>'MsWashCharge.embnameChange(this.value)')) !!}</div>
               </div>
               <div class="row middle">
                   <div class="col-sm-4 req-text">Embel. Type </div>
                   <div class="col-sm-8">{!! Form::select('embelishment_type_id', $embelishmenttype,'',array('id'=>'embelishment_type_id','onChange'=>'MsWashCharge.embtypeChange(this.value)')) !!}</div>
               </div>
            <div class="row middle">
                <div class="col-sm-4 req-text" id="wash_charge_embelishment_size">Emb. Size </div>
                <div class="col-sm-8">{!! Form::select('embelishment_size_id', $embelishmentsize,'',array('id'=>'embelishment_size_id')) !!}</div>
            </div>
               <div class="row middle" style="display:none">
                   <div class="col-sm-4 req-text">Composition </div>
                   <div class="col-sm-8">{!! Form::select('composition_id', $composition,'',array('id'=>'composition_id')) !!}</div>
               </div>
               <div class="row middle" style="display:none">
                   <div class="col-sm-4 req-text">Color Range </div>
                   <div class="col-sm-8">{!! Form::select('color_range_id', $colorrange,'',array('id'=>'color_range_id')) !!}</div>
               </div>
               <div class="row middle" style="display:none">
                   <div class="col-sm-4" >Fabric Shape  </div>
                   <div class="col-sm-8">{!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id')) !!}</div>
               </div>
               <div class="row middle">
                   <div class="col-sm-4 req-text">Rate </div>
                   <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
               </div>
               <div class="row middle">
                   <div class="col-sm-4 req-text">Uom </div>
                   <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
               </div>
               <div class="row middle">
                   <div class="col-sm-4">Sequence  </div>
                   <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" value=""/></div>
               </div>
               <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                   <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsWashCharge.submit()">Save</a>
                   <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('washchargeFrm')" >Reset</a>
                   <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsWashCharge.remove()" >Delete</a>
               </div>
          </form>
          </code>
          </div>
          </div>
      </div>
      <div data-options="region:'center',split:true, title:'List'">
        <table id="washchargeTbl" style="width:100%">
        <thead>
            <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'company'" width="100">Company</th>
              <th data-options="field:'embelishment_name'" width="100">Embelishment Name</th>
              <th data-options="field:'embelishmenttype'" width="100">Embelishment Type</th>
              
              <th data-options="field:'embelishmentsize'" width="100">Embelishment Size</th>
              <th data-options="field:'rate'" width="100">Rate</th>
              <th data-options="field:'uom'" width="100">Uom</th>
           </tr>
        </thead>
        </table>
      </div>
      </div>
    </div>
    <div title="Supplier Embelishment Charge" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Supplier Embelishment Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="height:120px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="supplierwashchargeFrm">
            <div class="row">

                <div class="col-sm-2 req-text">Supplier: </div>
                <div class="col-sm-4">
                  {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                  <input type="hidden" name="id" id="id" value=""/>
                  <input type="hidden" name="wash_charge_id" id="wash_charge_id" value=""/>
                </div>
                <div class="col-sm-2 req-text">Rate: </div>
                <div class="col-sm-4"><input type="text" name="rate" id="rate" value=""/></div>
            </div>
            <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSupplierWashCharge.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('supplierwashchargeFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSupplierWashCharge.remove()" >Delete</a>
            </div>
          </form>
          </code>
          </div>
          </div>
        </div>
        <div data-options="region:'center',split:true, title:'List'">
          <table id="supplierwashchargeTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'supplier'" width="100">Supplier</th>
                    <th data-options="field:'rate'" width="100">Rate</th>
               </tr>
            </thead>
            </table>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsWashChargeController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSupplierWashChargeController.js"></script>
  <script>
  $(".datepicker" ).datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
  });
  </script>
