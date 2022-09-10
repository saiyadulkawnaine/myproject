<style>
.flex-form-container-dyeing {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}
.flex-container-dyeing {
  display: flex;
  background-color: #f1f1f1;
  height: 100%;
  justify-content: center;
  text-transform: uppercase;
}

.flex-container-dyeing > div {
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
<div class="easyui-layout"  data-options="fit:true" id="todaydyeingachivementgraphwindowcontainerlayout">
      <div data-options="region:'north',border:true" style="height:29px; padding:2px">
            <form id="todaydyeingachivmentgraphFrm">
                 <div class="flex-form-container-dyeing">
                                <div class="row middle" style="width:100%">
                                    
                                    <div class="col-sm-1 req-text">Date</div>
                                    <div class="col-sm-1">
                                    <input type="text" id="date_to" name="date_to" class="datepicker" value="{{$today}}">
                                    </div>
                                    
                                    <div class="col-sm-1" style="text-align: right">
                                         <a href="javascript:void(0)" class="easyui-linkbutton" style="height:20px; border-radius:1px; background-color: #021344" iconCls="icon-search" plain="true" id="save" onClick="MsTodayDyeingAchievementGraph.get()">Show</a>
                                    </div>

                                   
                                </div>
                                
                        
                </div>
                
            </form> 
        </div>
        <div data-options="region:'center',border:true" style="padding:2px" id="todaydyeingachivementgraphwindowcontainerlayoutcenter">
            <div class="flex-container-dyeing" style="height: 700px">
                <div id="divtodaydyeingachivmentgraph_1">
                  <canvas id="canvastodaydyeingachivmentgraph_1"></canvas>
                </div>
                <div id="divtodaydyeingachivmentgraph_2">
                  <canvas id="canvastodaydyeingachivmentgraph_2"></canvas>
                </div>
            </div>

            <div class="flex-container-dyeing" style="height: 1000px">
                <div id="divtodaydyeingachivmentgraph_3">
                  <canvas id="canvastodaydyeingachivmentgraph_3"></canvas>
                </div>
                <div id="divtodaydyeingachivmentgraph_4">
                  <canvas id="canvastodaydyeingachivmentgraph_4"></canvas>
                </div>
            </div>

            <div class="flex-container-dyeing" style="height: 700px">
                <div id="divtodaydyeingachivmentgraph_5">
                  <canvas id="canvastodaydyeingachivmentgraph_5"></canvas>
                </div>
                <div id="divtodaydyeingachivmentgraph_6">
                  <canvas id="canvastodaydyeingachivmentgraph_6"></canvas>
                </div>
            </div>

            <div class="flex-container-dyeing" style="height: 1000px">
                <div id="divtodaydyeingachivmentgraph_7">
                  <canvas id="canvastodaydyeingachivmentgraph_7"></canvas>
                </div>
                <div id="divtodaydyeingachivmentgraph_8">
                  <canvas id="canvastodaydyeingachivmentgraph_8"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayDyeingAchievementGraphController.js"></script>
   
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
