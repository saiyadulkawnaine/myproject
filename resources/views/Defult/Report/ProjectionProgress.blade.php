<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'east',border:true,title:'Chart',hideCollapsedContent:false,collapsed:true" style="padding:2px; width:350px">
<div id="container">
 <canvas id="canvas"></canvas>
 </div>
 <div id="container">
 <canvas id="canvasam"></canvas>
 </div>
</div>
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">

<table id="projetionprogressTbl" style="width:940px;" >
<thead>
    <tr>
        <th data-options="field:'company_code'" width="60">Company</th>
        <th data-options="field:'teammember'" width="100">Dealing Merchant</th>
        <th data-options="field:'buyer'" width="80">Buyer</th>
        <th data-options="field:'style_ref'" width="100">Style No</th>

        <th data-options="field:'proj_no'" width="100">Proj. No</th>
        <th data-options="field:'productdepartment'" width="80">Prod. Dept</th>
        <th data-options="field:'country_ship_date'" width="70">Ship Date</th>
        <th data-options="field:'month'" width="60">Month</th>


        <th data-options="field:'qty'" width="80" align="right">Order Qty</th>
        <th data-options="field:'uom'" width="60">UOM</th>
        <th data-options="field:'rate'" width="60" align="right">Price / Unit</th>
        <th data-options="field:'amount'" width="80" align="right">Projected Value</th>


   </tr>
</thead>
</table>

</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="projectionFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Company</div>
                    <div class="col-sm-8">
                   {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}

                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Buyer </div>
                    <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Style Ref</div>
                    <div class="col-sm-8">
                   <input type="text" name="style_ref" id="style_ref" />

                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Proj. No : </div>
                    <div class="col-sm-8"><input type="text" name="proj_no" id="proj_no" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Ship Date : </div>
                    <div class="col-sm-4" style="padding-right:0px"><input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" /></div>
                    <div class="col-sm-4" style="padding-left:0px"><input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" /></div>
                </div>


          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProjectionProgress.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProjectionProgress.resetForm('orderprogressFrm')" >Reset</a>
    </div>

  </form>


</div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Chart.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsProjectionProgressController.js"></script>
<script>

$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
