<div class="easyui-layout"  data-options="fit:true" id="txtcapgraphwindowcontainerlayout">
        <div data-options="region:'center',border:true,title:'Central Plan'" style="padding:2px">
            
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
                <!-- <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>-->
                <div id="txtcapacityachivmentgraphFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdTxtCapacityAchievementGraph.get('getgraphqty')">Qty</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdTxtCapacityAchievementGraph.get('getgraphamount')">Amount</a>
                </div>
            </form> 
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdTxtCapacityAchievementGraphController.js"></script>
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
    changeYear: true
    });
</script>
