<div class="easyui-layout animated rollIn" data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="expdocprepstddayTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'company_id'" width="100">Company</th>
                    <th data-options="field:'exp_doc_progress_event_id'" width="100">Event</th>
                    <th data-options="field:'standard_days'" width="100">Days</th>
                    <th data-options="field:'status_id'" width="100">Status</th>  
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Export Doc Progress Days Standard',footer:'#ft2'" style="width: 400px; padding:2px">
        <form id="expdocprepstddayFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                            <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Company </div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Event </div>
                            <div class="col-sm-8">
                                {!! Form::select('exp_doc_progress_event_id', $expdocprogressevent,'',array('id'=>'exp_doc_progress_event_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 ">Days</div>
                            <div class="col-sm-8">
                                <input type="text" name="standard_days" id="standard_days" class="number integer"/>
                            </div>  
                        </div>
                        
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Status </div>
                            <div class="col-sm-8">
                                {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpDocPrepStdDay.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocPrepStdDay.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocPrepStdDay.remove()">Delete</a>
            </div>

        </form>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/System/Configuration/MsExpDocPrepStdDayController.js"></script>
<script>

    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
      });

</script>
