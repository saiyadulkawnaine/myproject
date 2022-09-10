<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Marketing Team Performance'" style="padding:2px">
        <div id="mktteamperformancematrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#mktteamperformanceFrmFt'" style="width:330px; padding:2px">
        <form id="mktteamperformanceFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Ship Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Receive Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="receive_date_from" id="receive_date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="receive_date_to" id="receive_date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Status</div>
                            <div class="col-sm-8">{!! Form::select('order_status', $status,'',array('id'=>'order_status')) !!}</div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="mktteamperformanceFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktTeamPerformance.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktTeamPerformance.resetForm('mktteamperformanceFrm')" >Reset</a>
                </div>
        </form>
    </div>
</div>
    
    
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/MsMktTeamPerformanceController.js"></script>
    <script>
        $(".datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    </script>