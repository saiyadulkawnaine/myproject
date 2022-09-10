<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="mailsetuptabs">
    <div title="Menu" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Menu'" style="padding:2px">
                <table id="mailsetupTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'report_name'" width="200">Report Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Mail Setup',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="mailsetupFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                 <input type="hidden" name="id" id="id" value="" />
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Report Name</div>
                                <div class="col-sm-7">
                                    <input type="hidden" name="report_name_id" id="report_name_id" value="" />
                                    {!! Form::select('report_name_id', $reportname,'',array('id'=>'report_name_id')) !!}
                                </div>
                            </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMailSetup.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMailSetup.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMailSetup.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Email To " style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Email To',footer:'#mailsetupemailtoFrmFt'" style="width: 400px; padding:2px">
                <form id="mailsetupemailtoFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                            <div class="row middle" style="display:none">
                                <input type="hidden" name="id" id="id" value="" />
                                <input type="hidden" name="mail_setup_id" id="mail_setup_id" value="" />
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Email</div>
                                <div class="col-sm-7">
                                    <input type="text" name="customer_email" id="customer_email">
                                </div>
                            </div>

                            <div class="row middle">
                            <div class="col-sm-5 req-text">Status  </div>
                            <div class="col-sm-7">
                                {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                            </div>
                        </div>
                            </code>
                        </div>
                    </div>
                    <div id="mailsetupemailtoFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMailSetupEmailTo.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMailSetupEmailTo.resetForm('mailsetupemailtoFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMailSetupEmailTo.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Email To'" style="padding:2px">
                <table id="mailsetupemailtoTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="20">ID</th>
                            <th data-options="field:'email'" width="40" >Email</th>
                            <th data-options="field:'status_id'" width="40" >Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllMailSetupController.js"></script>