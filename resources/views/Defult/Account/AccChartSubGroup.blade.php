<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilAccSubtabs">
    <div title="Sub Group" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'SubGroup'" style="padding:2px">
                <table id="accchartsubgroupTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'acc_chart_group_id'" width="100">Group</th>
                            <th data-options="field:'sort_id'" width="100">Sequence</th>
                        </tr>
                    </thead>
                </table>

            </div>
            <div data-options="region:'west',border:true,title:'Add New Sub Group',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="accchartsubgroupFrm">
                                <div class="row">
                                    
                                     <div class="col-sm-4 req-text">Name </div>
                                     <div class="col-sm-8">
                                        <input type="text" name="name" id="name"/> 
                                        <input type="hidden" name="id" id="id" value=""/>     
                                     </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Group</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('acc_chart_group_id', $accchartgroup,'',array('id'=>'acc_chart_group_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                     <div class="col-sm-4">Sequence </div>
                                     <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id"/>      
                                     </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartSubGroup.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('accchartsubgroupFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccChartSubGroup.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    

</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAccChartSubGroupController.js"></script>


