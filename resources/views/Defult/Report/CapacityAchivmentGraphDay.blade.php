<div class="easyui-layout"  data-options="fit:true" id="gmtcapgraphwindowcontainerdaylayout">
        <div data-options="region:'center',border:true,title:'Day Plan On Qty',footer:'#gmtcapgraphwindowcontainerdaylayoutcenterFt'" style="padding:2px" id="gmtcapgraphwindowcontainerdaylayoutcenter">
            <div id="gmtcapgraphwindowcontainerday">
            <canvas id="gmtcapgraphwindowcontainerdaycanvas1"></canvas>
            </div>
            <div id="gmtcapgraphwindowcontainerday1">
            <canvas id="gmtcapgraphwindowcontainerdaycom1"></canvas>
            </div>
            <div id="gmtcapgraphwindowcontainerday2">
            <canvas id="gmtcapgraphwindowcontainerdaycom2"></canvas>
            </div>
            <div id="gmtcapgraphwindowcontainerday4">
            <canvas id="gmtcapgraphwindowcontainerdaycom4"></canvas>
            </div>
            <div id="gmtcapgraphwindowcontainerdaylayoutcenterFt" style="padding:0px 0px; text-align:left; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphsewmintprod')">Sewing Prod Mint</a>
            </div>
        
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#capacityachivmentgraphdayFrmFt'" style="width:350px; padding:2px">
            <form id="capacityachivmentgraphdayFrm">
                <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="gmtdg_date_from" class="datepicker" placeholder="From"  value="{{$from}}" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="gmtdg_date_to" class="datepicker"  placeholder="To" value="{{$to}}"/>
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>
                <div id="capacityachivmentgraphdayFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraph')">Sewing</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphcut')">Cutting</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphsp')">Sr. Print</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphemb')">Embel</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphiron')">Iron</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphpoly')">Poly</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsProdGmtCapacityAchievementGraphDay.get('getgraphfin')">Finish</a>

                       
                   
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/Chart.js"></script>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdGmtCapacityAchievementGraphDayController.js"></script>
    <script>
    $(function() {
    $(".datepicker" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
    });
    });
</script>
