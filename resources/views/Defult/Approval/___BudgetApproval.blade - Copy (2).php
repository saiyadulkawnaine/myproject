<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="budgetapprovaltabs">
    <div title="Fabric" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetFabricApprovalAccordion">
                    @permission('approvefirst.budgetfabric')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetfabricapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetfabric')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.selectAll('#budgetfabricapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.unselectAll('#budgetfabricapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetfabric')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetfabricapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetfabric')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.selectAll('#budgetfabricapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.unselectAll('#budgetfabricapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetfabric')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetfabricapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetfabric')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.selectAll('#budgetfabricapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.unselectAll('#budgetfabricapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetfabric')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetfabricapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetfabric')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.selectAll('#budgetfabricapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.unselectAll('#budgetfabricapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetfabricapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetfabricapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_fabrin" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_fabric" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetfabricapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetfabricapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Yarn" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetYarnApprovalAccordion">
                    @permission('approvefirst.budgetyarn')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarnapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetyarnapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarnApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarnApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarnApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarnapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetyarn')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.selectAll('#budgetyarnapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.unselectAll('#budgetyarnapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetyarn')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarnapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetyarnapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarnApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarnApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarnApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarnapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetyarn')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.selectAll('#budgetyarnapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.unselectAll('#budgetyarnapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetyarn')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarnapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetyarnapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarnApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarnApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarnApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarnapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetyarn')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.selectAll('#budgetyarnapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.unselectAll('#budgetyarnapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetyarn')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarnapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetyarnapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarnApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarnApproval.budVsMktButton'></th>
                                           
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarnApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarnapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetyarn')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.selectAll('#budgetyarnapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.unselectAll('#budgetyarnapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetyarnapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetyarnapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_yarn" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_yarn" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetyarnapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetyarnapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Yarn Dyeing" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetYarndyeApprovalAccordion">
                    @permission('approvefirst.budgetyarndye')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarndyeapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetyarndyeapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarndyeApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarndyeApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarndyeApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarndyeapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetyarndye')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.selectAll('#budgetyarndyeapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.unselectAll('#budgetyarndyeapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetyarndye')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarndyeapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetyarndyeapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarndyeApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarndyeApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarndyeApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarndyeapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetyarndye')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.selectAll('#budgetyarndyeapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.unselectAll('#budgetyarndyeapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetyarndye')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarndyeapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetyarndyeapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarndyeApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarndyeApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarndyeApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarndyeapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetyarndye')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.selectAll('#budgetyarndyeapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.unselectAll('#budgetyarndyeapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetyarndye')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetyarndyeapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetyarndyeapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetYarndyeApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetYarndyeApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetYarndyeApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetyarndyeapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetyarndye')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.selectAll('#budgetyarndyeapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.unselectAll('#budgetyarndyeapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetyarndyeapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetyarndyeapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_yarndye" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_yarndye" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetyarndyeapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarndyeApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetyarndyeapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Fabric Production" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetFabricprodApprovalAccordion">
                    @permission('approvefirst.budgetfabricprod')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricprodapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetfabricprodapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricprodApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricprodApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricprodApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricprodapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetfabricprod')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.selectAll('#budgetfabricprodapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.unselectAll('#budgetfabricprodapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetfabricprod')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricprodapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetfabricprodapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricprodApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricprodApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricprodApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricprodapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetfabricprod')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.selectAll('#budgetfabricprodapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.unselectAll('#budgetfabricprodapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetfabricprod')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricprodapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetfabricprodapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricprodApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricprodApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricprodApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricprodapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetfabricprod')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.selectAll('#budgetfabricprodapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.unselectAll('#budgetfabricprodapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetfabricprod')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetfabricprodapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetfabricprodapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetFabricprodApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetFabricprodApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetFabricprodApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetfabricprodapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetfabricprod')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.selectAll('#budgetfabricprodapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.unselectAll('#budgetfabricprodapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetfabricprodapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetfabricprodapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_fabricprod" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_fabricprod" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetfabricprodapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricprodApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetfabricprodapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Embelishment" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetEmbelApprovalAccordion">
                    @permission('approvefirst.budgetembel')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetembelapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetembelapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetEmbelApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetEmbelApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetEmbelApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetembelapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetembel')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.selectAll('#budgetembelapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.unselectAll('#budgetembelapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetembel')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetembelapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetembelapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetEmbelApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetEmbelApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetEmbelApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetembelapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetembel')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.selectAll('#budgetembelapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.unselectAll('#budgetembelapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetembel')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetembelapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetembelapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetEmbelApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetEmbelApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetEmbelApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetembelapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetembel')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.selectAll('#budgetembelapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.unselectAll('#budgetembelapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetembel')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetembelapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetembelapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetEmbelApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetEmbelApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetEmbelApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetembelapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetembel')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.selectAll('#budgetembelapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.unselectAll('#budgetembelapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetembelapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetembelapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_embel" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_embel" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetembelapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbelApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetembelapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Trim" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetTrimApprovalAccordion">
                    @permission('approvefirst.budgettrim')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgettrimapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgettrimapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetTrimApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetTrimApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetTrimApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgettrimapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgettrim')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.selectAll('#budgettrimapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.unselectAll('#budgettrimapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgettrim')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgettrimapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgettrimapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetTrimApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetTrimApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetTrimApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgettrimapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgettrim')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.selectAll('#budgettrimapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.unselectAll('#budgettrimapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgettrim')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgettrimapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgettrimapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetTrimApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetTrimApproval.budVsMktButton'></th>
                                          
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetTrimApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgettrimapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgettrim')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.selectAll('#budgettrimapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.unselectAll('#budgettrimapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgettrim')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgettrimapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgettrimapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetTrimApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetTrimApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetTrimApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgettrimapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgettrim')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.selectAll('#budgettrimapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.unselectAll('#budgettrimapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgettrimapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgettrimapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_trim" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_trim" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgettrimapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgettrimapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Others" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetOtherApprovalAccordion">
                    @permission('approvefirst.budgetother')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetotherapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetotherapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetOtherApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetOtherApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetOtherApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetotherapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetother')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.selectAll('#budgetotherapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.unselectAll('#budgetotherapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetother')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetotherapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetotherapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetOtherApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetOtherApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetOtherApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetotherapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetother')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.selectAll('#budgetotherapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.unselectAll('#budgetotherapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetother')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetotherapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetotherapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetOtherApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetOtherApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetOtherApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetotherapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetother')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.selectAll('#budgetotherapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.unselectAll('#budgetotherapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetother')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetotherapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetotherapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetOtherApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetOtherApproval.budVsMktButton'></th>
                                            
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetOtherApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetotherapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetother')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.selectAll('#budgetotherapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.unselectAll('#budgetotherapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetotherapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetotherapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_other" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_other" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetotherapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetOtherApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetotherapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="All" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="budgetAllApprovalAccordion">
                    @permission('approvefirst.budgetall')
                    <div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetallapprovalfirstTblFt'" style="padding:2px">
                                <table id="budgetallapprovalfirstTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetAllApproval.formatHtmlFirst" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetAllApproval.budVsMktButton'></th>
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetAllApproval.formatpdf'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetallapprovalfirstTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvefirst.budgetall')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.selectAll('#budgetallapprovalfirstTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.unselectAll('#budgetallapprovalfirstTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.approved('firstapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvesecond.budgetall')
                    <div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetallapprovalsecondTblFt'" style="padding:2px">
                                <table id="budgetallapprovalsecondTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetAllApproval.formatHtmlSecond" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetAllApproval.budVsMktButton'></th>
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetAllApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetallapprovalsecondTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvesecond.budgetall')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.selectAll('#budgetallapprovalsecondTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.unselectAll('#budgetallapprovalsecondTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.approved('secondapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvethird.budgetall')
                    <div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetallapprovalthirdTblFt'" style="padding:2px">
                                <table id="budgetallapprovalthirdTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetAllApproval.formatHtmlThird" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetAllApproval.budVsMktButton'></th>
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetAllApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetallapprovalthirdTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    @permission('approvethird.budgetall')
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.selectAll('#budgetallapprovalthirdTbl')">Select All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.unselectAll('#budgetallapprovalthirdTbl')">Unselect All</a>
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.approved('thirdapproved')">Approve</a>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('approvefinal.budgetall')
                    <div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#budgetallapprovalfinalTblFt'" style="padding:2px">
                                <table id="budgetallapprovalfinalTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetAllApproval.formatHtmlFinal" align="center">Details</th>
                                            <th data-options="field:'app'" width="60" formatter='MsBudgetAllApproval.budVsMktButton'></th>
                                            <th data-options="field:'id'" width="80">ID</th>
                                            <th data-options="field:'job_no'" width="70">Job No</th>
                                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                            <th data-options="field:'company_name'" width="70">Company</th>
                                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                            <th data-options="field:'currency_code'" width="70">Currency</th>
                                            <th data-options="field:'uom_code'" width="70">Uom</th>
                                            <th data-options="field:'bill'" width="100" formatter='MsBudgetAllApproval.budgetButton'></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="budgetallapprovalfinalTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                @permission('approvefinal.budgetall')
                                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.selectAll('#budgetallapprovalfinalTbl')">Select All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.unselectAll('#budgetallapprovalfinalTbl')">Unselect All</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.approved('finalapproved')">Approve</a>
                                @endpermission
                                </div>
                            </div>
                        </div>
                    </div><!-- final -->
                    @endpermission
                </div><!-- accordian -->
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetallapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetallapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from_all" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_all" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetallapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAllApproval.show()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetallapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    
    

    
</div>

<div id="budgetApprovalDetailWindow" class="easyui-window" title="Cost Details" data-options="modal:true,closed:true," style="width:100%;height:100%;padding:2px;">
    <div id="budgetApprovalDetailContainer"></div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsAllBudgetApprovalController.js"></script>
<script>
$(".datepickery" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    