<style>
.flex-form-container-kniting {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}
.flex-container-kniting {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}

.flex-container-kniting > div {
  background-color: #ffffff;
  margin: 5px;
  padding: 10px;
  font-size: 18px;
  /*width:50%;*/
  color:#000000;
  font-weight: bold;
  /*height: 70%*/
}
</style>
<div class="easyui-layout"  data-options="fit:true" id="todayknitingachivementgraphwindowcontainerlayout">
      <div data-options="region:'north',border:true" style="height:29px; padding:2px">
            <form id="todayknitingachivmentgraphFrm">
                 <div class="flex-form-container-kniting">
                                <div class="row middle" style="width:100%">
                                    
                                    <div class="col-sm-1 req-text">Date</div>
                                    <div class="col-sm-1">
                                    <input type="text" id="date_to" name="date_to" class="datepicker" value="{{$today}}">
                                    </div>
                                    
                                    <div class="col-sm-1" style="text-align: right">
                                         <a href="javascript:void(0)" class="easyui-linkbutton" style="height:20px; border-radius:1px; background-color: #021344" iconCls="icon-search" plain="true" id="save" onClick="MsTodayKnitingAchievementGraph.get()">Show</a>
                                    </div>

                                   
                                </div>
                                
                        
                </div>
                
            </form> 
        </div>
        <div data-options="region:'center',border:true" style="padding:2px" id="todayknitingachivementgraphwindowcontainerlayoutcenter">
            <div class="flex-container-kniting" style="height: 1000px">
                <div id="divtodayknitingachivmentgraph_1" style="width: 50%">
                  <canvas id="canvastodayknitingachivmentgraph_1"></canvas>
                </div>
                <div id="divtodayknitingachivmentgraph_2" style="width: 50%">
                  <canvas id="canvastodayknitingachivmentgraph_2"></canvas>
                </div>
            </div>

           <div class="flex-container-kniting" style="height: 200px">
                <div id="divtodayknitingachivmentgraphtemp_1" style="width: 50%;">
                  
                </div>
                <div id="divtodayknitingachivmentgraphtemp_2" class="flex-container-kniting" style="width: 50%;">
                  
                </div>
            </div>

            <div class="flex-container-kniting" style="height: 1000px">
                <div id="divtodayknitingachivmentgraph_3" style="width: 50%">
                  <canvas id="canvastodayknitingachivmentgraph_3"></canvas>
                </div>
                <div id="divtodayknitingachivmentgraph_4" style="width: 50%">
                  <canvas id="canvastodayknitingachivmentgraph_4"></canvas>
                </div>
            </div>

            <div class="flex-container-kniting" style="height: 200px">
                <div id="divtodayknitingachivmentgraphtemp_3" style="width: 50%;">
                  
                </div>
                <div id="divtodayknitingachivmentgraphtemp_4" class="flex-container-kniting" style="width: 50%;">
                  
                </div>
            </div>


        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayKnitingAchievementGraphController.js"></script>
   
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
