<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="stylefabricationstripeTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'stylefabrication'" width="100">Style Fabrication</th>
        <th data-options="field:'color'" width="100">Color</th>
        <th data-options="field:'measurment'" width="100">Measurment</th>
        <th data-options="field:'feeder'" width="100">Feeder</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New StyleFabricationStripe',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="stylefabricationstripeFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-2 req-text">Style Fabrication</div>
                 <div class="col-sm-4">
                 {!! Form::select('style_fabrication_id', $stylefabrication,'',array('id'=>'style_fabrication_id')) !!}
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
                 <div class="col-sm-2 req-text">Color </div>
                 <div class="col-sm-4">{!! Form::select('color_id', $color,'',array('id'=>'color_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2 req-text">Measurment </div>
                 <div class="col-sm-4"><input type="text" name="measurment" id="measurment" value=""/></div>
                 <div class="col-sm-2 req-text">Feeder </div>
                 <div class="col-sm-4"><input type="text" name="feeder" id="feeder" value=""/></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2">Is Dye Wash  </div>
                 <div class="col-sm-4">{!! Form::select('is_dye_wash', $yesno,'',array('id'=>'is_dye_wash')) !!}</div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleFabricationStripe.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylefabricationstripeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleFabricationStripe.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsStyleFabricationStripeController.js"></script>
