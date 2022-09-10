<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilAccHeadtabs">
    <div title="Chart Of Account" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Control Head'" style="padding:2px">
                <table id="accchartctrlheadTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'code'" width="100">Code</th>
                            <th data-options="field:'name'" width="80">Name</th>
                            <th data-options="field:'root_id'" width="100">Root Name</th>
                            <th data-options="field:'acc_chart_sub_group_id'" width="100">Sub Group</th>
                            <th data-options="field:'main_group'" width="100">Main Group</th>
                             <th data-options="field:'row_status'" width="100">Active</th>
                            
                            <th data-options="field:'statement_type_id'" width="100">Statement Type</th>
                            <th data-options="field:'retained_earning_account_id'" width="100">Retained Earnings</th>
                            <th data-options="field:'control_name_id'" width="100">Control Name</th>
                            <th data-options="field:'is_cm_expense'" width="100">CM Expense</th>
                            <th data-options="field:'expense_type_id'" width="100">Expense Type</th>
                            <th data-options="field:'currency_id'" width="100">Currency</th>
                            <th data-options="field:'normal_balance_id'" width="100">Normal Balance</th>
                            <th data-options="field:'other_type_id'" width="100">Others</th>
                        </tr>
                    </thead>
                </table>

            </div>
            <div data-options="region:'west',border:true,title:'New Control Head',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="accchartctrlheadFrm">
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sub Group Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('acc_chart_sub_group_id', $accchartsubgroup,'',array('id'=>'acc_chart_sub_group_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Name </div>
                                    <div class="col-sm-8"><input type="text" name="name" id="name" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Code </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" id="code" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Parent Account</div>
                                    <div class="col-sm-8">
                                        
                                        <input type="text" name="root_id" id="root_id" style="width: 100%; border-radius:2px" value="0" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Account Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('ctrlhead_type_id', $ctrlheadtype,'',array('id'=>'ctrlhead_type_id','onchange'=>'MsAccChartCtrlHead.ctrlheadtypechange()')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Statement Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('statement_type_id', $statementType,'',array('id'=>'statement_type_id','onchange'=>'MsAccChartCtrlHead.statementtypechange()')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 for-coa for-income_st req-text">Retained Earning</div>
                                    <div class="col-sm-8">
                                     <input type="text" name="retained_earning_account_id" id="retained_earning_account_id" style="width: 100%; border-radius:2px" value="0" />   

                                    
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Control Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('control_name_id', $controlname,'',array('id'=>'control_name_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">CM Expense </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('is_cm_expense', $yesno,'',array('id'=>'is_cm_expense')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Expense Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('expense_type_id', $expenseType,'',array('id'=>'expense_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Other Types </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('other_type_id', $otherType,'',array('id'=>'other_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Currency </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4">Normal Balance </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('normal_balance_id', $normalbalance,'',array('id'=>'normal_balance_id')) !!}
                                    </div>
                                </div>
                              
                                <div class="row middle">
                                    <div class="col-sm-4">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Status </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('row_status', $status,'1',array('id'=>'row_status')) !!}
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartCtrlHead.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccChartCtrlHead.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccChartCtrlHead.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!----------===============================Chart Location======================---------------------->
    <div title="Tag Location" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Location',footer:'#utilaccChartLocationft'" style="width:450px; padding:2px">
                <form id="accchartlocationFrm">
                    <input type="hidden" name="acc_chart_ctrl_head_id" id="acc_chart_ctrl_head_id" value="" />
                </form>
                <table id="accchartlocationTbl">
                </table>
                <div id="utilaccChartLocationft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartLocation.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Location'" style="padding:2px">
                <table id="accchartlocationsavedTbl">
                </table>
            </div>
        </div>
    </div>
    <!------================Division========================----------------->
    <div title="Tag Division" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Division',footer:'#utilaccChartDivisionft'" style="width:450px; padding:2px">
                <form id="accchartdivisionFrm">
                    <input type="hidden" name="acc_chart_ctrl_head_id" id="acc_chart_ctrl_head_id" value="" />
                </form>
                <table id="accchartdivisionTbl">
                </table>
                <div id="utilaccChartDivisionft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartDivision.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Division'" style="padding:2px">
                <table id="accchartdivisionsavedTbl">
                </table>
            </div>
        </div>
    </div>
    <!-----==============================Department==========================-------------->
    <div title="Tag Department" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Department',footer:'#utilaccChartDepartmentft'" style="width:450px; padding:2px">
                <form id="accchartdepartmentFrm">
                    <input type="hidden" name="acc_chart_ctrl_head_id" id="acc_chart_ctrl_head_id" value="" />
                </form>
                <table id="accchartdepartmentTbl">
                </table>
                <div id="utilaccChartDepartmentft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartDepartment.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Department'" style="padding:2px">
                <table id="accchartdepartmentsavedTbl">
                </table>
            </div>
        </div>
    </div>
    <!----===============================Section=======================--------------->
    <div title="Tag Section" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Section',footer:'#utilaccChartSectionft'" style="width:450px; padding:2px">
                <form id="accchartsectionFrm">
                    <input type="hidden" name="acc_chart_ctrl_head_id" id="acc_chart_ctrl_head_id" value="" />
                </form>
                <table id="accchartsectionTbl">
                </table>
                <div id="utilaccChartSectionft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartSection.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Section'" style="padding:2px">
                <table id="accchartsectionsavedTbl">
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAllAccountController.js"></script>
<script>

(function(){
    
    $('#accchartctrlheadFrm [id="root_id"]').combotree({
        method:'get',
        url: MsAccChartCtrlHead.route+'/getroot',
        editable:true,
        filter: function(q, node){
            if (node.text.toLowerCase()== q.toLowerCase()){
                $('#accchartctrlheadFrm [id="root_id"]').combotree('setValue', node.id);
            }
            return node.text.toLowerCase().indexOf(q.toLowerCase()) >= 0;
        }
    });

    $('#accchartctrlheadFrm [id="retained_earning_account_id"]').combobox({
        method:'get',
        url: MsAccChartCtrlHead.route+'/retainedearningaccount',
        valueField:'id',
        textField:'text'
    });
  $('#accchartctrlheadFrm [id="acc_chart_sub_group_id"]').combobox();
    /*$('#acc_chart_sub_group_id').combobox({
        valueField:'id',
        textField:'name'
    });*/
    
    /*$.extend($.fn.combotree.methods,{
        nav:function(jq,dir){
            return jq.each(function(){
                var opts = $(this).combotree('options');
                var t = $(this).combotree('tree');
                var nodes = t.tree('getChildren');
                if (!nodes.length){return}
                var node = t.tree('getSelected');
                if (!node){
                    t.tree('select', dir>0 ? nodes[0].target : nodes[nodes.length-1].target);
                } else {
                    var index = 0;
                    for(var i=0; i<nodes.length; i++){
                        if (nodes[i].target == node.target){
                            index = i;
                            break;
                        }
                    }
                    if (dir>0){
                        while (index < nodes.length-1){
                            index++;
                            if ($(nodes[index].target).is(':visible')){break}
                        }
                    } else {
                        while (index > 0){
                            index--;
                            if ($(nodes[index].target).is(':visible')){break}
                        }
                    }
                    t.tree('select',nodes[index].target);
                }
                if (opts.selectOnNavigation){
                    var node = t.tree('getSelected');
                    $(node.target).trigger('click');
                    $(this).combotree('showPanel');
                }
            });
        }
    });

    $.extend($.fn.combotree.defaults.keyHandler,{
        up:function(){
            $(this).combotree('nav',-1);
        },
        down:function(){
            $(this).combotree('nav',1);
        },
        enter:function(){
            var t = $(this).combotree('tree');
            var node = t.tree('getSelected');
            if (node){
                $(node.target).trigger('click');
            }
            $(this).combotree('hidePanel');
        }
    });*/

})(jQuery);
</script>


