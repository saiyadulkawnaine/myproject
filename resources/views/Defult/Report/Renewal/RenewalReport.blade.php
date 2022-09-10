<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Lisence Renewal Details'" style="padding:2px">
        <div id="renewentrymatrix"></div>
    </div>
     {{-- <div data-options="region:'west',border:true,title:'Search',footer:'#prodgmtcapacityachievementft4'" style="width:350px; padding:2px">
        <form id="renewalreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date </div>
                            <div class="col-sm-4" style="padding-right:0px; display: none ">
                                <input type="text" name="validity_end" id="validity_end" class="datepicker" placeholder="From" value=""/>
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="prodgmtcapacityachievementft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRenewalReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRenewalReport.resetForm('renewalreportFrm')" >Reset</a>
            </div>
        </form>
    </div>  --}}
</div>

<div id="renewalEntryRemarksWindow" class="easyui-window" title="Comments Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:600px;height:400px;padding:2px;">
    {{-- <div id="renewalWindowContainer" style="width:500px;height:400px;padding:0px;"></div> --}}
    <table id="remarkTbl" width="550px">
        <thead>
        <tr>
            <td data-options="field:'remarks',halign:'center'" width="200px" align="center">Remarks</td>
        </tr>
    </thead>
    </table>

</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Renewal/MsRenewalReportController.js"></script>
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