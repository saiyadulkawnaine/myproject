<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="capacitydistTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'company'" width="100">Company</th>
      <th data-options="field:'prodtype'" width="100">Product Type</th>
      <th data-options="field:'prodsource'" width="100">Product Source</th>
      <th data-options="field:'year'" width="100">Year</th>
      <th data-options="field:'week'" width="100">Week</th>
      <th data-options="field:'mktsmv'" width="100">MKT SMV</th>
      <th data-options="field:'mktpcs'" width="100">MKT Pcs</th>
      <th data-options="field:'prodsmv'" width="100">Product SMV</th>
      <th data-options="field:'prodpcs'" width="100">Product Pcs</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New CapacityDist',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:190px; padding:2px">
<form id="capacitydistFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                  <div class="col-sm-2 req-text">Company</div>
                  <div class="col-sm-4">
                  {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                  <input type="hidden" name="id" id="id" value=""/>
                  </div>
                  <div class="col-sm-2">Location  </div>
                  <div class="col-sm-4">{!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}</div>
                </div>
                <div class="row">
                  <div class="col-sm-2 req-text">Product Type </div>
                  <div class="col-sm-4">{!! Form::select('prod_type_id', $itemcategory,'',array('id'=>'prod_type_id')) !!}</div>
                  <div class="col-sm-2 req-text">Production Source </div>
                  <div class="col-sm-4">{!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}</div>
                </div>
                <div class="row">
                    <div class="col-sm-2 req-text">Year </div>
                    <div class="col-sm-4"><input type="text" name="year" id="year" value=""/></div>
                    <div class="col-sm-2 req-text">Week </div>
                    <div class="col-sm-4">{!! Form::select('week_id', $week,'',array('id'=>'week_id')) !!}</div>
                </div>
                <div class="row">
                    <div class="col-sm-2 req-text">MKT SMV </div>
                    <div class="col-sm-4"><input type="text" name="mkt_smv" id="mkt_smv" value=""/></div>
                    <div class="col-sm-2 req-text">Product SMV </div>
                    <div class="col-sm-4"><input type="text" name="prod_smv" id="prod_smv" value=""/></div>
                </div>
                <div class="row">
                    <div class="col-sm-2 req-text">MKT Pcs </div>
                    <div class="col-sm-4"><input type="text" name="mkt_pcs" id="mkt_pcs" value=""/></div>
                    <div class="col-sm-2 req-text">Product Pcs </div>
                    <div class="col-sm-4"><input type="text" name="prod_pcs" id="prod_pcs" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCapacityDist.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('capacitydistFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCapacityDist.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCapacityDistController.js"></script>
