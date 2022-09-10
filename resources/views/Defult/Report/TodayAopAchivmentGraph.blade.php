<style>
.flex-form-container-aop {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}
.flex-container-aop {
  display: flex;
  background-color: #f1f1f1;
  height: 100%;
  justify-content: center;
  text-transform: uppercase;
}

.flex-container-aop > div {
  background-color: #ffffff;
  margin: 5px;
  padding: 10px;
  font-size: 18px;
  width:50%;
  color:#000000;
  font-weight: bold;
  height: 70%
}
</style>
<div class="easyui-layout"  data-options="fit:true" id="todayaopachivementgraphwindowcontainerlayout">
      <div data-options="region:'north',border:true" style="height:29px; padding:2px">
            <form id="todayaopachivmentgraphFrm">
                 <div class="flex-form-container-aop">
                                <div class="row middle" style="width:100%">
                                    
                                    <div class="col-sm-1 req-text">Date</div>
                                    <div class="col-sm-1">
                                    <input type="text" id="date_to" name="date_to" class="datepicker" value="{{$today}}">
                                    </div>
                                    
                                    <div class="col-sm-1" style="text-align: right">
                                         <a href="javascript:void(0)" class="easyui-linkbutton" style="height:20px; border-radius:1px; background-color: #021344" iconCls="icon-search" plain="true" id="save" onClick="MsTodayAopAchievementGraph.get()">Show</a>
                                    </div>

                                   
                                </div>
                                
                        
                </div>
                
            </form> 
        </div>
        <div data-options="region:'center',border:true" style="padding:2px" id="todayaopachivementgraphwindowcontainerlayoutcenter">
            <div class="flex-container-aop" style="height: 700px">
                <div id="divtodayaopachivmentgraph_1">
                  <canvas id="canvastodayaopachivmentgraph_1"></canvas>
                </div>
                <div id="divtodayaopachivmentgraph_2">
                  <canvas id="canvastodayaopachivmentgraph_2"></canvas>
                </div>
            </div>

            <div class="flex-container-aop" style="height: 1500px">
                <div id="divtodayaopachivmentgraph_3">
                  <canvas id="canvastodayaopachivmentgraph_3"></canvas>
                </div>
                <div id="divtodayaopachivmentgraph_4">
                  <canvas id="canvastodayaopachivmentgraph_4"></canvas>
                </div>
            </div>

            <div class="flex-container-aop" style="height: 700px">
                <div id="divtodayaopachivmentgraph_5">
                  <canvas id="canvastodayaopachivmentgraph_5"></canvas>
                </div>
                <div id="divtodayaopachivmentgraph_6">
                  <canvas id="canvastodayaopachivmentgraph_6"></canvas>
                </div>
            </div>

            <div class="flex-container-aop" style="height: 1500px">
                <div id="divtodayaopachivmentgraph_7">
                  <canvas id="canvastodayaopachivmentgraph_7"></canvas>
                </div>
                <div id="divtodayaopachivmentgraph_8">
                  <canvas id="canvastodayaopachivmentgraph_8"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayAopAchievementGraphController.js"></script>
   
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
    /*$('#todaysewingachivmentgraphFrm  [name=date_to]').change(function(){
        MsTodaySewingAchievementGraph.getLine()
         
      });
   
    setInterval(function(){ 
        MsTodaySewingAchievementGraph.autoget() 
    }, 360000);
    setInterval(function(){ 
        MsTodaySewingAchievementGraph.autogettwo() 
    }, 10000);*/
</script>
