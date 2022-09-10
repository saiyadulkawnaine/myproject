<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="tnaactualtabs">
    <div title="TNA Actual" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="tnaactualTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'task_name'" width="100">Custom Name</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
                            <th data-options="field:'acl_start_date'" width="100">Actual Start Dare</th>
                            <th data-options="field:'acl_end_date'" width="100">Actual End Date</th>                            
                            <th data-options="field:'tna_start_date'" width="100">Plan Start Dare</th>
                            <th data-options="field:'tna_end_date'" width="100">Plan End Date</th>                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#tnaactualFrmFt'" style="width:350px; padding:2px">
                <form id="tnaactualFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Order</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="MsTnaActual.openOrderWindow()" placeholder="double click" readonly/>
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />
                                        <input type="hidden" name="tna_task_id" id="tna_task_id" value="" />
                                    </div>
                                </div>                             
                                <div class="row middle">
                                    <div class="col-sm-5">Task</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="task_name" id="task_name" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Actual Start Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="acl_start_date" id="acl_start_date" value="" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Actual End Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="acl_end_date" id="acl_end_date" value="" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Style Ref</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="style_ref" id="style_ref" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buyer</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bnf.Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="beneficiary" id="beneficiary" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Prod.Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="produced_company_name" id="produced_company_name" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Shipment Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="ship_date" id="ship_date" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Plan Start Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tna_start_date" id="tna_start_date" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Plan End Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tna_end_date" id="tna_end_date" value="" disabled="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="tnaactualFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnaActual.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnaactualFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaActual.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="openorderwindow" class="easyui-window" title="Order Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="ordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Ship Date </div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Buyer </div>
                                <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsTnaActual.searchOrder()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'task_name'" width="100">Custom Name</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                        <th data-options="field:'buyer_name'" width="130">Buyer</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'produced_company_name'" width="80">Produced Company</th>
                        <th data-options="field:'tna_start_date'" width="100">Plan Start Date</th>
                        <th data-options="field:'tna_end_date'" width="100">Plan End Date</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openorderwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Planing/MsTnaActualController.js"></script>
<script>
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('#ordersearchFrm [id="buyer_id"]').combobox();
</script>