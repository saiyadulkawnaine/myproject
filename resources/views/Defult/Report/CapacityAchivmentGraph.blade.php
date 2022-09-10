<div class="easyui-layout"  data-options="fit:true" id="gmtcapgraphwindowcontainerlayout">
        <div data-options="region:'center',border:true,title:'Central Plan',footer:'#capacityachivmentgraphTblFt'" style="padding:2px" id="gmtcapgraphwindowcontainerlayoutcenter">
        <div id="capacityachivmentgraphTblFt" style="padding:0px 0px; text-align:left; background:#F3F3F3;" >
        

        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphqtysp')">Qty(Srp)</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphamountsp')">Amt(Srp)</a>

        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphqtyemb')">Qty(Emb)</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphamountemb')">Amt(Emb)</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphsewmintprod')">Sewing Prod Mint</a>
        </div>
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#capacityachivmentgraphFrmFt'" style="width:350px; padding:2px">
            <form id="capacityachivmentgraphFrm">
                 <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="gmtmg_date_from" class="gmtmgdatepickerfirst" placeholder="From" value="{{$from}}" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="gmtmg_date_to" class="gmtmgdatepickerlast"  placeholder="To" value="{{$to}}"/>
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>
                <div id="capacityachivmentgraphFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphqty')">Qty(Sew)</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphamount')">Amt(Sew)</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphqtycut')">Qty(Cut)</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraph.get('getgraphamountcut')">Amt(Cut)</a>

                    
                </div>
            </form> 
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/Chart.js"></script>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdGmtCapacityAchievementGraphController.js"></script>
    <script>
    $(".gmtmgdatepickerfirst" ).datepicker({
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

    $(".gmtmgdatepickerlast" ).datepicker({
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
