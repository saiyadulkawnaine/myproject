<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
  <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="capacitydistbuyerTbl" style="width:100%">
      <thead>
          <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'capacitydist'" width="120">Capacity Dist</th>
            <th data-options="field:'buyer'" width="100">Buyer</th>
            <th data-options="field:'distributedpercent'" width="100">Distributed (%)</th>
            <th data-options="field:'prodsource'" width="100">Product Source</th>
            <th data-options="field:'mktsmv'" width="100">MKT SMV</th>
            <th data-options="field:'mktpcs'" width="100">MKT Pcs</th>
            <th data-options="field:'prodsmv'" width="100">Product SMV</th>
            <th data-options="field:'prodpcs'" width="100">Product Pcs</th>
         </tr>
      </thead>
    </table>
  </div>
  <div data-options="region:'north',border:true,title:'Add New CapacityDistBuyer',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
    <form id="capacitydistbuyerFrm">
        <div id="container">
             <div id="body">
               <code>

                    <div class="row">
                        <div class="col-sm-2 req-text">Capacity Dist</div>
                        <div class="col-sm-4">
                          {!! Form::select('capacity_dist_id', $capacitydist,'',array('id'=>'capacity_dist_id')) !!}
                        <input type="hidden" name="id" id="id" value=""/>
                        </div>
                        <div class="col-sm-2 req-text">Buyer </div>
                        <div class="col-sm-4">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 req-text">Distributed (%) </div>
                        <div class="col-sm-4"><input type="text" name="distributed_percent" id="distributed_percent" value=""/></div>
                        <div class="col-sm-2 req-text">Product Source </div>
                        <div class="col-sm-4">{!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}</div>
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
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCapacityDistBuyer.submit()">Save</a>
             <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('capacitydistbuyerFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCapacityDistBuyer.remove()" >Delete</a>
        </div>

      </form>
    </div>
  </div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCapacityDistBuyerController.js"></script>
