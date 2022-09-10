<style>
.flex-form-container {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}
.flex-container {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}

.flex-container > div {
  background-color: #021344;
  margin: 5px;
  padding: 10px;
  font-size: 18px;
  width:33.33%;
  color:#ffffff;
  font-weight: bold;
}
</style>
<div class="easyui-layout"  data-options="fit:true" id="todaysewingachivementgraphwindowcontainerlayout">
      <div data-options="region:'north',border:true" style="height:29px; padding:2px">
            <form id="todaysewingachivmentgraphFrm">
                 <div class="flex-form-container">
                                <div class="row middle" style="width:100%">
                                    <div class="col-sm-1 req-text">Company</div>
                                    <div class="col-sm-2">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsTodaySewingAchievementGraph.getLine()')) !!}
                                    </div>
                                    <div class="col-sm-1 req-text">Date</div>
                                    <div class="col-sm-1">
                                    <input type="text" id="date_to" name="date_to" class="datepicker" value="{{$today}}">
                                    <input type="hidden" id="line_id" name="line_id">
                                    </div>
                                    <div class="col-sm-2 req-text">Auto Update</div>
                                    <div class="col-sm-1">
                                    {!! Form::select('auto_update', $yesno,'1',array('id'=>'auto_update')) !!}
                                    </div>
                                    <div class="col-sm-1" style="text-align: right">
                                         <a href="javascript:void(0)" class="easyui-linkbutton" style="height:20px; border-radius:1px; background-color: #021344" iconCls="icon-search" plain="true" id="save" onClick="MsTodaySewingAchievementGraph.get()">Show</a>
                                    </div>
                                    <div class="col-sm-1">Line</div>
                                    <div class="col-sm-1">
                                    {!! Form::select('recall_line_id', array(),'1',array('id'=>'recall_line_id')) !!}
                                    </div>
                                    <div class="col-sm-1" style="text-align: right">
                                    <a href="javascript:void(0)" class="easyui-linkbutton " style="height:20px; border-radius:1px; background-color: #021344" iconCls="icon-search" plain="true" id="save" onClick="MsTodaySewingAchievementGraph.gettwo()">Re-Call</a>
                                    </div>
                                </div>
                                
                        
                </div>
                
            </form> 
        </div>
        <div data-options="region:'center',border:true" style="padding:2px" id="todaysewingachivementgraphwindowcontainerlayoutcenter">
        </div>
        <div data-options="region:'west',border:true" style="width:500px; padding:2px" id="todaysewingachivementgraphwindowcontainerlayoutwest">
            
           
        </div>
    </div>

    <div id="todaysewingachivementgraphlinedetailWindow" class="easyui-window" title="Line Wise Hourly Production Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
            <div id="todaysewingachivementgraphlinedetailWindowContainer" style="width:100%;height:100%;padding:0px;"></div>
   </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodaySewingAchievementGraphController.js"></script>
   
    <script>
    $(".datepicker" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    });
//$('#todaysewingachivmentgraphFrm  [name=date_to]')
    $('#todaysewingachivmentgraphFrm  [name=date_to]').change(function(){
        MsTodaySewingAchievementGraph.getLine()
         
      });
   
    setInterval(function(){ 
        MsTodaySewingAchievementGraph.autoget() 
    }, 360000);
    setInterval(function(){ 
        MsTodaySewingAchievementGraph.autogettwo() 
    }, 10000);
</script>
