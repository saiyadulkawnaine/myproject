<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="mktcostyarnTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'mktcost'" width="70">MKT Cost</th>
      <th data-options="field:'embelishment'" width="70">Embelishment</th>
      <th data-options="field:'embelishmenttype'" width="70">Embelishment Type</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New MktCostYarn',footer:'#ft2'" style="width:350px; padding:2px">
<form id="mktcostyarnFrm">
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
                 <div class="col-sm-4 req-text">Embelishment  </div>
                 <div class="col-sm-8">{!! Form::select('embelishment_id', $mktcostfabric,'',array('id'=>'embelishment_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Embelishment Type  </div>
                 <div class="col-sm-8">{!! Form::select('embelishment_type_id', $yarn,'',array('id'=>'embelishment_type_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Cons </div>
                 <div class="col-sm-8"><input type="text" name="cons" id="cons" /></div>
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
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostYarn.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostyarnFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostYarn.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsMktCostYarnController.js"></script>
