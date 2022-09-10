<div class="easyui-layout animated rollIn" data-options="fit:true">
 <div data-options="region:'center',border:true,title:'Asset Repair Out and Back Report'" style="padding:2px">
  <div id="assetrepairbackreportTblContainer">
  </div>
 </div>
 <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
  <form id="assetrepairbackreportFrm">
   <div id="container">
    <div id="body">
     <code>
                    <div class="row middle">
                        <div class="col-sm-4">Company </div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Date Range </div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker">
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                           <input type="text" name="date_to" id="date_to" class="datepicker">
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Location </div>
                        <div class="col-sm-8">
                            {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Asset Name </div>
                        <div class="col-sm-8">
                            {!! Form::select('$asset_id', $assetName,'',array('id'=>'asset_id','style'=>'width:100%;border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Asset Type </div>
                        <div class="col-sm-8">
                            {!! Form::select('$type_id', $assetType,'',array('id'=>'type_id','style'=>'width:100%;border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Prod. Area </div>
                        <div class="col-sm-8">
                            {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                        </div>
                    </div> 
                </code>
    </div>
   </div>
   <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsAssetRepairBackReport.get()">Show</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete"
     onClick="MsAssetRepairBackReport.resetForm('assetrepairbackreportFrm')">Reset</a>
   </div>

  </form>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/FAM/MsAssetRepairBackReportController.js">
</script>
<script>
 $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
 $('#assetrepairbackreportFrm [id=asset_id]').combobox();
</script>