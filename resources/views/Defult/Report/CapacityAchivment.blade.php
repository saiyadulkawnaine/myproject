<div class="easyui-layout animated rollIn"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Production Achivment'" style="padding:2px">
            <div id="pcacolorsizematrix"></div>
        </div>
         <div data-options="region:'west',border:true,title:'Search',footer:'#prodgmtcapacityachievementft4'" style="width:350px; padding:2px">
            <form id="prodgmtcapacityachievementFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4">Date </div>
            <div class="col-sm-4" style="padding-right:0px; display: none ">
            <input type="text" name="capacity_date_from" id="capacity_date_from" class="datepicker" placeholder="From" value=""/>
            </div>
            <div class="col-sm-4" style="padding-left:0px">
                <?php
                $str2=date('Y-m-d');
                // $date_to = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
                $date_to = date('Y-m-d', strtotime($str2));
                ?>
            <input type="text" name="capacity_date_to" id="capacity_date_to" class="datepicker"  placeholder="To" value="<?php echo $date_to; ?>" />
            </div>
            </div>

            </code>
            </div>
            </div>
            <div id="prodgmtcapacityachievementft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievement.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCapacityAchievement.resetForm('prodgmtcapacityachievementFrm')" >Reset</a>
            </div>
            </form>
        </div> 
    </div>

    <div id="capacitydetailWindowTwo" class="easyui-window" title="Line Wise Hourly Production Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
            <div id="capacitydetailWindowTwoContainer" style="width:100%;height:100%;padding:0px;"></div>
   </div>

    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdGmtCapacityAchievementController.js"></script>
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
