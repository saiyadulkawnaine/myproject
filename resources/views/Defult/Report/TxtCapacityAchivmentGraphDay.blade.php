<div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Day Plan On Qty'" style="padding:2px" id="txtcapgraphwindowcontainerdaylayoutcenter">
            
            <div id="txtcapgraphwindowcontainerday4">
            <canvas id="txtcapgraphwindowcontainerdaycom4"></canvas>
            </div>
            <div id="txtcapgraphwindowcontainerday5">
            <canvas id="txtcapgraphwindowcontainerdaycom5"></canvas>
            </div>
            <div id="txtcapgraphwindowcontainerday6">
            <canvas id="txtcapgraphwindowcontainerdaycom6"></canvas>
            </div>
            
        
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#txtcapacityachivmentgraphdayFrmFt'" style="width:350px; padding:2px">
            <form id="txtcapacityachivmentgraphdayFrm">
                <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="txtdg_date_from" class="datepicker" placeholder="From" value="{{$from}}" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="txtdg_date_to" class="datepicker"  placeholder="To" value="{{$to}}"/>
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>
                <div id="txtcapacityachivmentgraphdayFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdTxtCapacityAchievementGraphDay.get()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdTxtCapacityAchievementGraphDay.resetForm('txtcapacityachivmentgraphdayFrm')" >Reset</a>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/Chart.js"></script>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdTxtCapacityAchievementGraphDayController.js"></script>
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
