
  <div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="FabricProcess Loss" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'north',split:true, title:'FabricProcess Loss',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="height:190px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="fabricprocesslossFrm">
            <div class="row">
                   <div class="col-sm-2 req-text">Buyer</div>
                   <div class="col-sm-4">
                     {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                   <input type="hidden" name="id" id="id" value=""/>
                   </div>
                   <div class="col-sm-2 req-text">Fabric Nature </div>
                   <div class="col-sm-4">{!! Form::select('fabric_nature_id', $fabricnature,'',array('id'=>'fabric_nature_id')) !!}</div>
               </div>
               <div class="row middle">
                   <div class="col-sm-2 req-text">Composition </div>
                   <div class="col-sm-4">{!! Form::select('composition_id', $composition,'',array('id'=>'composition_id')) !!}</div>
                   <div class="col-sm-2 req-text">Color Range </div>
                   <div class="col-sm-4">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}</div>
               </div>
               <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsFabricprocessloss.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('fabricprocesslossFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFabricprocessloss.remove()" >Delete</a>
    </div>
          </form>
          </code>
          </div>
          </div>
      </div>
      <div data-options="region:'center',split:true, title:'List'">
        <table id="fabricprocesslossTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'buyer'" width="100">Buyer</th>
        <th data-options="field:'fabricnature'" width="100">Fabric Nature</th>
        <th data-options="field:'composition'" width="100">Composition</th>
        <th data-options="field:'colorrange'" width="100">Color Range</th>
        </tr>
        </thead>
        </table>
      </div>
      </div>
    </div>
<div title="FabricProcess Perent" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Supplier Wash Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="height:150px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="fabricprocesslosspercentFrm">
            <div class="row">
                     <div class="col-sm-2 req-text">Loss Area</div>
                     <div class="col-sm-4">
                       {!! Form::select('loss_area_id', $productionarea,'',array('id'=>'loss_area_id')) !!}
                       <input type="hidden" name="id" id="id" value=""/>
                       <input type="hidden" name="fabricprocessloss_id" id="fabricprocessloss_id" value=""/>
                     </div>
                     <div class="col-sm-2">Process Area </div>
                     <div class="col-sm-4">
                       {!! Form::select('process_area_id', $productionprocess,'',array('id'=>'process_area_id')) !!}
                     </div>

                 </div>
                 <div class="row middle">

                   <div class="col-sm-2 req-text">Loss Percent</div>
                   <div class="col-sm-4"><input type="text" name="loss_percent" id="loss_percent" value=""/></div>
               </div>
               <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
           <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsFabricprocesslossPercent.submit()">Save</a>
           <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('fabricprocesslosspercentFrm')" >Reset</a>
           <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFabricprocesslossPercent.remove()" >Delete</a>
       </div>
          </form>
          </code>
          </div>
          </div>
        </div>
        <div data-options="region:'center',split:true, title:'List'">
          <table id="fabricprocesslosspercentTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'lossarea'" width="100">Loss Area</th>
        <th data-options="field:'losspercent',align:'right'" width="100">Loss Percent</th>
   </tr>
</thead>
</table>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsFabricprocesslossController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsFabricprocesslossPercentController.js"></script>

  <script>
  $(".datepicker" ).datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
  });
  </script>
