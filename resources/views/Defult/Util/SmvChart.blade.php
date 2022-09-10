<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="smvchartTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'company'" width="100">Company</th>
        <th data-options="field:'gmtcategory'" width="100">Gmt. category</th>
        <th data-options="field:'gmtcomplexity'" width="80">Gmt Complexity</th>
        <th data-options="field:'dew_efficiency_per',align:'right'" width="100">Sew Efficiency %</th>
        <th data-options="field:'man_power_line',align:'right'" width="100">Man Power Per Line</th>
        <th data-options="field:'gmt_smv',align:'right'" width="100">GMT SMV </th>
        <th data-options="field:'sew_target_per_hour',align:'right'" width="100">Sew Target Per Hour</th>

   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New SMV Chart',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:180px; padding:2px">
<form id="smvchartFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Company </div>
                    <div class="col-sm-4">
                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Location </div>
                    <div class="col-sm-4">{!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">GMT Catg  </div>
                    <div class="col-sm-4">{!! Form::select('gmt_category_id', $gmtcategory,'',array('id'=>'gmt_category_id')) !!}</div>
                    <div class="col-sm-2 req-text">Gmt Complexity  </div>
                    <div class="col-sm-4">{!! Form::select('gmt_complexity_id', $gmtcomplexity,'',array('id'=>'gmt_complexity_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2">GMT SMV</div>
                    <div class="col-sm-4"><input type="text" name="gmt_smv" id="gmt_smv" value="" onChange="MsSmvChart.setSewTargetPerHour()"/></div>
                    <div class="col-sm-2">Man Power Per Line </div>
                    <div class="col-sm-4"><input type="text" name="man_power_line" id="man_power_line" value="" onChange="MsSmvChart.setSewTargetPerHour()"/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2">Sew Efficiency % </div>
                    <div class="col-sm-4"><input type="text" name="sew_efficiency_per" id="sew_efficiency_per" value="" onChange="MsSmvChart.setSewTargetPerHour()"/></div>
                    <div class="col-sm-2">Sew Target Per Hour </div>
                    <div class="col-sm-4"><input type="text" name="sew_target_per_hour" id="sew_target_per_hour" value="" readonly/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmvChart.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smvchartFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmvChart.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSmvChartController.js"></script>
