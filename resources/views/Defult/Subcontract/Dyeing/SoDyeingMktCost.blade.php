<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="sodyeingmktcosttabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="sodyeingmktcostTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'sub_inb_service_id'" width="80">Forcasting ID</th>
                            <th data-options="field:'sub_inb_marketing_id'" width="80">MKTCost ID</th>
                            <th data-options="field:'costing_date'" width="80">Costing Date</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'buyer_name'" width="100">Customer</th>
                            <th data-options="field:'amount'" width="100">Forcasted Amount</th>
                            <th data-options="field:'currency_code'" width="100">Currency</th>
                            <th data-options="field:'exch_rate'" width="70">Exch Rate</th>
                            <th data-options="field:'remarks'" width="150">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Dyeing Marketing Costing',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#sodyeingmktcostft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="sodyeingmktcostFrm">
                                <div class="row">
                                    <div class="col-sm-5">Costing ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="id" id="id" readonly />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Forecasting ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sub_inb_service_id" id="sub_inb_service_id" ondblclick="MsSoDyeingMktCost.subinbserviceWindow()" placeholder="Double Click"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5"> Costing Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="costing_date" id="costing_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Forcasted Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount"  class="number integer" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Currency</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Exch Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exch_rate" id="exch_rate"  class="number integer" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="sodyeingmktcostft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCost.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingmktcostFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCost.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Fabrication" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#sodyeingmktcostfabFrmFt'" style="width:450px; padding:2px">
                <form id="sodyeingmktcostfabFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_dyeing_mkt_cost_id" id="so_dyeing_mkt_cost_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabrication</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabrication" id="fabrication" placeholder="Double Click" ondblclick="MsSoDyeingMktCostFab.fabricItemWindowOpen()" readonly />
                                        <input type="hidden" name="autoyarn_id" id="autoyarn_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GSM/Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsm_weight" id="gsm_weight" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">DIA</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dia" id="dia" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Color % Range</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="color_ratio_from" id="color_ratio_from" class="number integer" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="color_ratio_to" id="color_ratio_to" class="number integer"  placeholder="To" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Dyeing Type</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('dyeing_type_id',$dyetype,'',array('id'=>'dyeing_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt"  class="number integer" value="1" onchange="MsSoDyeingMktCostFab.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Offer Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="offer_qty" id="offer_qty"  class="number integer" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Ratio</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_ratio" id="liqure_ratio"  class="number integer" onchange="MsSoDyeingMktCostFab.calculate()"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_wgt" id="liqure_wgt"  class="number integer"  readonly/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="sodyeingmktcostfabFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostFab.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingmktcostfabFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostFab.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="sodyeingmktcostfabTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'colorrange'" width="70">Color Range</th>
                            <th data-options="field:'dyeingtype'" width="80">Dye Type</th>
                            <th data-options="field:'dia'" width="70">DIA</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'fabric_wgt'" width="70" align="right">Fabric Weight</th>
                            <th data-options="field:'color_ratio_from'" width="100" align="right">Color % From</th>
                            <th data-options="field:'color_ratio_to'" width="100" align="right">Color % To</th>
                            <th data-options="field:'liqure_ratio'" width="70" align="right">Liqure Ratio</th>
                            <th data-options="field:'liqure_wgt'" width="100" align="right">Liqure Weight</th>
                            <th data-options="field:'offer_qty'" width="80">Offer Qty</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Dyes & Chemical" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#sodyeingmktcostfabitemFrmFt'" style="width:400px; padding:2px">
                <form id="sodyeingmktcostfabitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                
                                <div class="row">
                                    <div class="col-sm-4" ></div>
                                    <div class="col-sm-8">
                                        
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="so_dyeing_mkt_cost_fab_id" id="so_dyeing_mkt_cost_fab_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" value="" class="number integer"  disabled
                                         />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_wgt" id="liqure_wgt" value="" class="number integer"  disabled
                                         />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item ID</div>
                                    <div class="col-sm-8">
                                        
                                        <input type="text" name="item_account_id" id="item_account_id" ondblclick="MsSoDyeingMktCostFabItem.openitemWindow()" readonly placeholder="Double Click"    />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Specification</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="specification" id="specification" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Catg.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_category" id="item_category" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_class" id="item_class" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">% on Fabric Wgt</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="per_on_fabric_wgt" id="per_on_fabric_wgt" value="" class="number integer" onchange="MsSoDyeingMktCostFabItem.calculate_qty('per_on_fabric_wgt')" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Gram/L. Liqure</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gram_per_ltr_liqure" id="gram_per_ltr_liqure" value="" class="number integer" onchange="MsSoDyeingMktCostFabItem.calculate_qty('gram_per_ltr_liqure')" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer"  readonly
                                         />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Last MRR No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="last_receive_no" id="last_receive_no" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Last Rcv Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="last_rcv_rate" id="last_rcv_rate" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer"  onchange="MsSoDyeingMktCostFabItem.calculate_amount()" 
                                         />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Currency</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly
                                         />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" value="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>

                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Fabrication</div>
                                    <div class="col-sm-5">
                                        <input type="text" name="master_fab_id" id="master_fab_id" ondblclick="MsSoDyeingMktCostFabItem.openitemCopyWindow()" />
                                    </div>
                                    <div class="col-sm-3">
                                        
                                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostFabItem.itemCopy()">Copy</a>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="sodyeingmktcostfabitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostFabItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostFabItem.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostFabItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#sodyeingmktcostfabitemTblFt'" style="padding:2px">
                <table id="sodyeingmktcostfabitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'per_on_fabric_wgt'" width="100" align="right">% on Fabric Wgt</th>
                        <th data-options="field:'gram_per_ltr_liqure'" width="100">Gram/L. Liqure</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        <th data-options="field:'remarks'" width="100" align="right">Remarks</th>
                        
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Special Finish" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#sodyeingmktcostspfinFrmFt'" style="width:350px; padding:2px">
                <form id="sodyeingmktcostfabfinFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_dyeing_mkt_cost_fab_id" id="so_dyeing_mkt_cost_fab_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Process: </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Chemical Cost</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount"  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id"  class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="sodyeingmktcostspfinFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostFabFin.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostFabFin.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostFabFin.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="sodyeingmktcostfabfinTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'productionprocess'" width="250">Process</th>
                            <th data-options="field:'amount'" width="60" align="right">Chemical Cost</th>
                            <th data-options="field:'sort_id'" width="80" align="right">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Quoted Price" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true" style="width:300px; padding:2px">
                <div class="easyui-layout" data-options="fit:true">{{-- iconCls:'icon-more', --}}
                    <div data-options="region:'north',border:true,title:'Add Details',footer:'#ft4'"
                    style="height:250px; padding:2px">
                        <form id="sodyeingmktcostqpriceFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row middle" style="display:none">
                                            <input type="hidden" name="id" id="id" value="" />
                                            <input type="hidden" name="so_dyeing_mkt_cost_id" id="so_dyeing_mkt_cost_id" value="" />
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-5 req-text">Submission No</div>
                                            <div class="col-sm-7">
                                                <input type="text" name="qprice_no" id="qprice_no"  class="number integer" readonly/>
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-5 req-text">Submission Date</div>
                                            <div class="col-sm-7">
                                                <input type="text" name="qprice_date" id="qprice_date"  class="datepicker" value=""/>
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-5">Remarks </div>
                                            <div class="col-sm-7">
                                                <textarea name="remarks" id="remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-5 req-text">Ready To Approve</div>
                                            <div class="col-sm-7">
                                                {!! Form::select('ready_to_approve_id', $yesno, 0,array('id'=>'ready_to_approve_id')) !!}
                                            </div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                            <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                                iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQprice.submit()">Save</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                                iconCls="icon-remove" plain="true" id="delete"
                                onClick="MsSoDyeingMktCostQprice.resetForm()">Reset</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                                iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostQprice.remove()">Delete</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                                iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostQprice.pdf()">PDF</a>
                            </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                        <table id="sodyeingmktcostqpriceTbl" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="80">ID</th>
                                    <th data-options="field:'qprice_no'" width="100">Submission No</th>
                                    <th data-options="field:'qprice_date'" width="80">Submission Date</th>
                                    <th data-options="field:'remarks',align:'right'" width="180">Remarks</th>
                                    <th data-options="field:'ready_to_approve'" width="100">Ready To Approve</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Quoted Price Details',footer:'#ftqpricedtl'" style="padding:2px;width:100%;">
                <form id="sodyeingmktcostqpricedtlFrm" >
                    <input type="hidden" name="id" id="id" value="" />
                    <code id="sodyeingmktcostqpricedtlcosi">
                    </code>
                    <div id="ftqpricedtl" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
                        iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpricedtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                        iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingmktcostqpricedtlFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
                        iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostQpricedtl.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
        @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
    </div>
</div>
    

{{-- Forcasting ID --}}
<div id="subinbserviceWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#subinbserviceWindowFt'" style="width:350px;height:130px">
            <div id="body">
                <code>
                    <form id="subinbservicesearchFrm">
                        <div class="row middle">
                            <div class="col-sm-4">Est. Dlv Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Company </div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id'))
                                !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Buyer</div>
                            <div class="col-sm-8">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                    </form>
                </code>
            </div>
            <div id="subinbserviceWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoDyeingMktCost.getSubInbService()">Search</a>
            </div>
        </div>
        <div data-options="region:'center',footer:'#sodyeingbomsosearchTblFt'" style="padding:10px;">
            <table id="subinbservicesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'sub_inb_marketing_id'" width="80">MKTCost ID</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Customer</th>
                        <th data-options="field:'est_delv_date'" width="100">Est Dlv Date</th>
                        <th data-options="field:'qty'" width="100">Forcasted Qty</th>
                        <th data-options="field:'rate'" width="100">Forcasted Rate</th>
                        <th data-options="field:'uom_code'" width="60">UOM</th>
                        <th data-options="field:'amount'" width="100">Forcasted Value</th>
                        <th data-options="field:'remarks'" width="120">Remarks</th>
                        <th data-options="field:'currency_code'" width="100">Currency</th>
                        <th data-options="field:'team_name'" width="100">Team</th>
                        <th data-options="field:'team_member_name'" width="100">Marketing Member</th>
                        <th data-options="field:'contact_person'" width="100">Contact</th>
                        <th data-options="field:'contact_no'" width="200">Contact Number</th>
                    </tr>
                </thead>
            </table>
            <div id="sodyeingbomsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#subinbserviceWindow').window('close')" style="border-radius:1px">Close</a>
            </div>
        </div>
    </div>
</div>

{{-- Item Account Window Fabric Description--}}
<div id="sodyeingmktcostfabricitemWindow" class="easyui-window" title="Fabrication Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#sodyeingitemsearchTblft'" style="padding:2px">
            <table id="sodyeingmktcostfabricitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Construction</th>
                    <th data-options="field:'composition_name'" width="300">Composition</th>
                    </tr>
                </thead>
            </table>
            <div id="sodyeingitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#sodyeingmktcostfabricitemWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#sodyeingitemsearchFrmft'" style="padding:2px; width:350px">
            <form id="sodyeingmktcostfabricitemsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                                <div class="col-sm-4 req-text">Construction</div>
                                <div class="col-sm-8"> 
                                    <input type="text" name="construction_name" id="construction_name" /> 
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Composition</div>
                                <div class="col-sm-8">
                                    <input type="text" name="composition_name" id="composition_name" />
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="sodyeingitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsSoDyeingMktCostFab.searchFabricItem()" >Search</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
{{-- Marketing Cost Fabric Item Detail --}}
<div id="sodyeingmktcostfabitemWindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#sodyeingmktcostfabitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="">
                            <div class="row middle">
                            <div class="col-sm-4" >Item Category</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_category" id="item_category" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Item Class</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_class" id="item_class" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="sodyeingmktcostfabitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostFabItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#sodyeingmktcostfabitemsearchTblFt'" style="padding:10px;">
            <table id="sodyeingmktcostfabitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'stock_qty'" width="100" align="right">Stock Qty</th>
                        <th data-options="field:'last_rcv_rate'" width="100" align="right">Last Rcv Rate</th>
                        <th data-options="field:'last_receive_no'" width="100" align="right">Last MRR No</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="sodyeingmktcostfabMasterCopyWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="sodyeingmktcostfabMasterCopyTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'construction_name'" width="100">Constructions</th>
                        <th data-options="field:'fabrication'" width="100">Fabrication</th>
                        <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                        <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                        <th data-options="field:'dyeingtype'" width="80">Dye Type</th>
                        <th data-options="field:'color_ratio_from'" width="100" align="right">Color % From</th>
                        <th data-options="field:'color_ratio_to'" width="100" align="right">Color % To</th>
                        {{-- <th data-options="field:'uom_name'" width="80">Uom</th> --}}
                        <th data-options="field:'fabric_wgt'" width="70" align="right">Fabric Weight</th>
                        <th data-options="field:'amount'" width="100" align="right">Order Value</th>
                        <th data-options="field:'liqure_ratio'" width="70" align="right">Liqure Ratio</th>
                        <th data-options="field:'liqure_wgt'" width="100" align="right">Liqure Weight</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>  
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsAllDyeingMktCostController.js"></script>
 
<script>
$(document).ready(function() {
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
    $('#sodyeingmktcostfabFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
$('#sodyeingbomoverheadFrm [id="acc_chart_ctrl_head_id"]').combobox();
</script>
    