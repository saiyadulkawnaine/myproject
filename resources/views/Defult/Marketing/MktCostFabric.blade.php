<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="mktcostfabricTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'mktcost'" width="70">MKT Cost</th>
      <th data-options="field:'gmtspart'" width="70">Gmts Part</th>
      <th data-options="field:'autoyarn'" width="70">Autoyarn</th>
      <th data-options="field:'colorrange'" width="70">Color Range</th>
      <th data-options="field:'uom'" width="70">Uom</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New MktCostFabric',footer:'#ft2'" style="width:350px; padding:2px">
<form id="mktcostfabricFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-4 req-text">Mkt Cost </div>
                 <div class="col-sm-8">
                 {!! Form::select('mkt_cost_id', $mktcost,'',array('id'=>'mkt_cost_id')) !!}
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Fabric Type  </div>
                 <div class="col-sm-8">{!! Form::select('fabric_type', $fabrictype,'',array('id'=>'fabric_type')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">GMT Part  </div>
                 <div class="col-sm-8">{!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Fabric Nature </div>
                 <div class="col-sm-8">{!! Form::select('fabric_nature_id', $fabricnature,'',array('id'=>'fabric_nature_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Fabric Look  </div>
                 <div class="col-sm-8">{!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Autoyarn  </div>
                 <div class="col-sm-8">{!! Form::select('autoyarn_id', $autoyarn,'',array('id'=>'autoyarn_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">GSM Weight </div>
                 <div class="col-sm-8"><input type="text" name="gsm_weight" id="gsm_weight" value=""/></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Color Range </div>
                 <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Fabric Shape </div>
                 <div class="col-sm-8">{!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Material Source </div>
                 <div class="col-sm-8">{!! Form::select('material_source_id', $materialsourcing,'',array('id'=>'material_source_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Cons Basis  </div>
                 <div class="col-sm-8"><input type="text" name="cons_basis" id="cons_basis" value=""/></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">UOM </div>
                 <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Fabric Cons </div>
                 <div class="col-sm-8"><input type="text" name="fabric_cons" id="fabric_cons" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Rate </div>
                 <div class="col-sm-8"><input type="text" name="rate" id="rate" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Amount </div>
                 <div class="col-sm-8"><input type="text" name="amount" id="amount" /></div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFabric.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostfabricFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostFabric.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsMktCostFabricController.js"></script>
