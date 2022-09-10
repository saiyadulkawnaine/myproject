<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="plknittabs">
    <div title="Kniting Plan" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#ftsc'" style="padding:2px">
                <table id="plknitTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'pl_no'" width="100">Plan No</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'supplier_id'" width="100">Kniting Company</th>
                           
                            <th data-options="field:'pl_date'" width="100">Plan Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="ftsc" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Plan Date: <input type="text" name="from_date" id="from_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsPlKnit.searchPlan()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Kniting Plan',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#plknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="plknitFrm">

                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>


                                <div class="row middle">
                                        <div class="col-sm-4">Plan No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="pl_no" id="pl_no" readonly />
                                        </div>
                                    </div>

                                    <div class="row middle">
                                            <div class="col-sm-4 req-text">Company</div>
                                            <div class="col-sm-8">
                                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                            </div>
                                    </div>
                                     <div class="row middle">
                                    <div class="col-sm-4">Plan Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="pl_date" id="pl_date" class="datepicker" />
                                    </div>
                                </div>

                                    <div class="row middle">
                                            <div class="col-sm-4">Kniting Company</div>
                                            <div class="col-sm-8">
                                               
                                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                          
                                            </div>
                                        </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="plknitFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnit.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('plknitFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnit.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Product Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="plknititemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'pdf'" formatter="MsPlKnitItem.formatPdf" width="45"></th>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'colorrange_id'" width="100">Colorrange</th>
                            <th data-options="field:'dia'" width="100">Dia</th>
                            <th data-options="field:'measurment'" width="100">Measurment</th>
                            <th data-options="field:'fabric_color'" width="100">Fabric Color</th>
                            <th data-options="field:'gsm_weight'" width="100">GSM</th>
                            <th data-options="field:'stitch_length'" width="100"> Stitch Length</th>
                            <th data-options="field:'spandex_stitch_length'" width="100">Spandex Stitch Length</th>
                            <th data-options="field:'draft_ratio'" width="100">Draft Ratio</th>
                            <th data-options="field:'machine_no'" width="100">Machine No</th>
                            <th data-options="field:'machine_gg'" width="100">M/C Gauge</th>
                            <th data-options="field:'no_of_feeder'" width="100">Feeder</th>
                            <th data-options="field:'capacity'" width="100">Capacity</th>
                            <th data-options="field:'qty'" width="100">Qty</th>
                            <th data-options="field:'pl_start_date'" width="100">Plan Start Date</th>
                            <th data-options="field:'pl_end_date'" width="100">Plan End Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Kniting Plan',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#plknititemFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="plknititemFrm">
                                <div class="row">
                                    <div class="col-sm-4">Fabrication</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="fabrication" id="fabrication" ondblclick="MsPlKnitItem.plknititemWindowOpen()"  placeholder="Double Click to Select" readonly />
                                    <input type="hidden" name="so_knit_ref_id" id="so_knit_ref_id" />
                                    <input type="hidden" name="pl_knit_id" id="pl_knit_id" />
                                    <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Colorrange</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Dia</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="dia" id="dia" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Shape</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id','disabled'=>'disabled',)) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Measurment</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="measurment" id="measurment" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Color</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="fabric_color" id="fabric_color" disabled/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GSM</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="gsm_weight" id="gsm_weight" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Stitch Length</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="stitch_length" id="stitch_length" onchange="MsPlKnitItem.calculateTgtPerDay()" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">S.L</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="spandex_stitch_length" id="spandex_stitch_length"  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Draft Ratio</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="draft_ratio" id="draft_ratio" class="number integer" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Machine</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="machine_no" id="machine_no" ondblclick="MsPlKnitItem.plknitmachineWindowOpen()"  placeholder="Double Click to Select" readonly  />
                                        <input type="hidden" name="machine_id" id="machine_id"  readonly  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">M/C Gauge</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="machine_gg" id="machine_gg"  class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">No of feeder</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="no_of_feeder" id="no_of_feeder" class="number integer" onchange="MsPlKnitItem.calculateTgtPerDay()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">RPM</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="rpm" id="rpm" class="number integer" onchange="MsPlKnitItem.calculateTgtPerDay()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">No of Needle</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_needle" id="no_of_needle" class="number integer" onchange="MsPlKnitItem.calculateTgtPerDay()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Hour</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="hour" id="hour" class="number integer" onchange="MsPlKnitItem.calculateTgtPerDay()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Expc Effi</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="expected_effi_per" id="expected_effi_per" class="number integer" onchange="MsPlKnitItem.calculateTgtPerDay()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Count</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="count" id="count" class="number integer" onchange="MsPlKnitItem.calculateTgtPerDay()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Tgt/Day</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="capacity" id="capacity"  class="number integer" onchange="MsPlKnitItem.setPlEndDate()" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Qty</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="qty" id="qty"  class="number integer" onchange="MsPlKnitItem.setPlEndDate()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Start Date</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="pl_start_date" id="pl_start_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan End Date</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="pl_end_date" id="pl_end_date" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Knitting Sales Order </div>
                                <div class="col-sm-8">
                                    <input type="text" name="knitting_sales_order" id="knitting_sales_order" disabled />
                                </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                    <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="plknititemFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnitItem.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('plknititemFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnitItem.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Edit Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="plknititemqtyTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="100">ID</th>
                            <th data-options="field:'pl_date'" width="80">Plan Date</th>
                            <th data-options="field:'qty'" width="100" align="right">Plan Qty</th>
                            <th data-options="field:'prod_qty'" width="100" align="right">Prod Qty</th>
                            <th data-options="field:'adjusted_minute'" width="100" align="right">Adjusted Minute</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Kniting Plan',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#plknititemqtyFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="plknititemqtyFrm">
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Date</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="pl_date" id="pl_date" class="datepicker" />
                                     <input type="hidden" name="pl_knit_item_id" id="pl_knit_item_id" />
                                    <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty"  class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Adjusted Minute</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="adjusted_minute" id="adjusted_minute"  class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                    <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="plknititemqtyFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnitItemQty.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('plknititemqtyFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnitItemQty.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Stripe Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="plknititemstripeTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'gmt_color_id'" width="100">GMT Color</th>
                        <th data-options="field:'stripe_color_id'" width="100">Stripe Color</th>
                        <th data-options="field:'no_of_feeder'" width="100">Feeder</th>
                        <th data-options="field:'measurment'" width="100">Measurment</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Stripe',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#plknititemstripeFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="plknititemstripeFrm">

                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                    <input type="hidden" name="pl_knit_item_id" id="pl_knit_item_id" />
                                    <input type="hidden" name="style_fabrication_stripe_id" id="style_fabrication_stripe_id" />
                                </div>


                                <div class="row middle">
                                        <div class="col-sm-4">Gmt Color</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="gmt_color_id" id="gmt_color_id" />
                                        </div>
                                    </div>

                                    <div class="row middle">
                                            <div class="col-sm-4 req-text">Stripe Color</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="stripe_color_id" id="stripe_color_id" />
                                            </div>
                                    </div>
                                     <div class="row middle">
                                    <div class="col-sm-4">No Of Feeder</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_feeder" id="no_of_feeder" />
                                    </div>
                                </div>

                                    
                                <div class="row middle">
                                    <div class="col-sm-4">Measurment </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="measurment" id="measurment" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="plknititemstripeFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnitItemStripe.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('plknititemstripeFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnitItemStripe.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Narrow Fabric Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="plknititemnarrowfabricTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'size_id'" width="100">Gmt Size</th>
                        <th data-options="field:'measurment'" width="100">Measurment</th>
                        <th data-options="field:'capacity'" width="100">Capacity</th>
                        <th data-options="field:'qty'" width="100">qty</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Stripe',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#plknititemnarrowfabricFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="plknititemnarrowfabricFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                    <input type="hidden" name="pl_knit_item_id" id="pl_knit_item_id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Gmt Size </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="gmt_size_id" id="gmt_size_id" />
                                    </div>
                                </div>    
                                <div class="row middle">
                                    <div class="col-sm-4">Measurment</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="measurment" id="measurment" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Capacity</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="capacity" id="capacity" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Plan Qty</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="qty" id="qty"/>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="plknititemnarrowfabricFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnitItemNarrowfabric.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('plknititemnarrowfabricFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnitItemNarrowfabric.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="plknitmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#plknitmachinesearchTblft'" style="padding:2px">
            <table id="plknitmachinesearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'custom_no'" width="100">Machine No</th>
                    <th data-options="field:'asset_name'" width="100">Asset Name</th>
                    <th data-options="field:'origin'" width="100">Origin</th>
                    <th data-options="field:'brand'" width="100">Brand</th>
                    <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
                    <th data-options="field:'dia_width'" width="60">Dia/Width</th>
                    <th data-options="field:'gauge'" width="60">Gauge</th>
                    <th data-options="field:'extra_cylinder'" width="60">Extra Cylinder</th>
                    <th data-options="field:'no_of_feeder'" width="60">Feeder</th>
                    </tr>
                </thead>
            </table>
            <div id="plknitmachinesearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#plknitmachineWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#plknitmachinesearchFrmft'" style="padding:2px; width:350px">
            <form id="plknitmachinesearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Dia/Width</div>
                            <div class="col-sm-8"> <input type="text" name="dia_width" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Feeder</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="no_of_feeder" />
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="plknitmachinesearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsPlKnitItem.searchMachine()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="plknititemWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#plknititemsearchTblft'" style="padding:2px">
            <table id="plknititemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'knitting_sales_order'" width="80">Knitting Sale Order No</th>
                        <th data-options="field:'customer_name'" width="80">Customer</th>
                        <th data-options="field:'gmt_buyer'" width="80">GMT Buyer</th>
                        <th data-options="field:'gmt_style_ref'" width="80">GMT Style</th>
                        <th data-options="field:'gmt_sale_order_no'" width="80">GMT Sales Order</th>
                        <th data-options="field:'gmtspart'" width="80">GMT Part</th>
                        <th data-options="field:'constructions_name'" width="80">Construction</th>
                        <th data-options="field:'fabrication'" width="80">Fabrication</th>
                        <th data-options="field:'fabriclooks'" width="80">Fabric Looks</th>
                        <th data-options="field:'fabricshape'" width="80">Fabric Shape</th>
                        <th data-options="field:'gsm_weight'" width="80" align="right">GSM/Weight</th>
                        <th data-options="field:'dia'" width="80" align="right">Dia</th>
                        <th data-options="field:'measurment'" width="80">Measurment</th>
                        <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                        <th data-options="field:'uom_id'" width="80">Uom</th>
                        <th data-options="field:'qty'" width="80" align="right">WO. Qty</th>
                        <th data-options="field:'prev_pl_qty'" width="80" align="right">Plan. Qty</th>
                        <th data-options="field:'balance'" width="80" align="right">Balance. Qty</th>
                        <th data-options="field:'rate'" width="80" align="right">Rate</th>
                        <th data-options="field:'amount'" width="80" align="right">Amount</th>
                        <th data-options="field:'delivery_date'" width="80">Dlv Date</th> 
                    </tr>
                </thead>
            </table>
            <div id="plknititemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#plknititemWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#plknititemsearchFrmft'" style="padding:2px; width:350px">
            <form id="plknititemsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row middle">
                            <div class="col-sm-4">Sales Order No</div>
                            <div class="col-sm-8"> 
                            <input type="text" name="sale_oreder_no" id="sale_oreder_no" /> 
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Customer</div>
                            <div class="col-sm-8">
                            {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="plknititemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" onClick="MsPlKnitItem.searchItem()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsAllPlKnitController.js"></script>

<script>

$(document).ready(function() {
    $('#plknitFrm [id="supplier_id"]').combobox();
    $('#plknititemsearchFrm [id="buyer_id"]').combobox();
    
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
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });

    $('#pl_start_date').change(function(){
        MsPlKnitItem.setPlEndDate();
      });


    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/plknit/getstyleref?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#style_ref').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'styles',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/plknit/getbuyer?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#buyer').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'buyers',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/plknit/getorder?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#order_no').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'orders',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });
    //====Product Tab========
    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/subinborderproduct/getcolor?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#color').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'colors',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/plknititemnarrowfabric/getsize?',
        replace: function(url, uriEncodedQuery) {
        //url = url + 'code=' + uriEncodedQuery;
        // How to change this to denominator for denominator queries?
        val = $('#plknititemnarrowfabricFrm [name=pl_knit_item_id]').val(); 
        if (!val) return url;
        return url + 'pl_knit_item_id=' + encodeURIComponent(val)
        },
        wildcard: '%QUERY%'
        },
    });

    $('#gmt_size_id').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'sizes',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });
    
});
</script>
    