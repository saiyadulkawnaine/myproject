<div class="easyui-layout"  data-options="fit:true" id="txtcapgraphwindowcontainerlayout">
        <div data-options="region:'center',border:true,title:'Central Plan'" style="padding:2px" id="txtcapgraphwindowcontainerlayoutcenter">
            
            <div id="txtcapgraphwindowcontainer4">
            <canvas id="txtcapgraphwindowcontainercom4"></canvas>
            </div>
            <div id="txtcapgraphwindowcontainer5">
            <canvas id="gmtcapgraphwindowcontainercom5"></canvas>
            </div>
            <div id="txtcapgraphwindowcontainer6">
            <canvas id="gmtcapgraphwindowcontainercom6"></canvas>
            </div>
            
        
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#txtcapacityachivmentgraphFrmFt'" style="width:350px; padding:2px">
            <form id="txtcapacityachivmentgraphFrm">
                 <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="txtmg_date_from" class="txtmgdatepickerfirst" placeholder="From" value="{{$from}}" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="txtmg_date_to" class="txtmgdatepickerlast"  placeholder="To" value="{{$to}}" />
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>
                <div id="txtcapacityachivmentgraphFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdTxtCapacityAchievementGraph.get('getgraphqty')">Qty</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdTxtCapacityAchievementGraph.get('getgraphamount')">Amount</a>
                </div>
            </form> 
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/Chart.js"></script>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdTxtCapacityAchievementGraphController.js"></script>
    <script>
    $(".txtmgdatepickerfirst" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    beforeShowDay: function(date) {

            if (date.getDate() == 1) {
                return [true, ''];
            }
            return [false, ''];
        }
    });

    function LastDayOfMonth(Year, Month) {
        return (new Date((new Date(Year, Month + 1, 1)) - 1)).getDate();
    }

    $(".txtmgdatepickerlast" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    beforeShowDay: function(date) {
            //getDate() returns the day (0-31)
            if (date.getDate() == LastDayOfMonth(date.getFullYear(), date.getMonth())) {
                return [true, ''];
            }
            return [false, ''];
        }
    });
</script>
