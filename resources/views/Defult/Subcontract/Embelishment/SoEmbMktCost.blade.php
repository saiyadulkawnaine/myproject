<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soembmktcosttabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soembmktcostTbl" style="width:100%">
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
            <div data-options="region:'west',border:true,title:'Embelishment Marketing Costing',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soembmktcostft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soembmktcostFrm">
                                <div class="row">
                                    <div class="col-sm-5">Costing ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="id" id="id" readonly />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Forecasting ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sub_inb_service_id" id="sub_inb_service_id" ondblclick="MsSoEmbMktCost.subinbserviceWindow()" placeholder="Double Click"/>
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
                <div id="soembmktcostft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCost.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembmktcostFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCost.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Parameters" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#soembmktcostparamFrmFt'" style="width:450px; padding:2px">
                <form id="soembmktcostparamFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_aop_mkt_cost_id" id="so_aop_mkt_cost_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                {{-- <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabrication</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabrication" id="fabrication" placeholder="Double Click" ondblclick="MsSoEmbMktCostFab.fabricItemWindowOpen()" readonly />
                                        <input type="hidden" name="autoyarn_id" id="autoyarn_id" value="" />
                                    </div>
                                </div> --}}
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
                                    <div class="col-sm-4">No of Color</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="no_of_color_from" id="no_of_color_from" class="number integer" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="no_of_color_to" id="no_of_color_to" class="number integer"  placeholder="To" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Print Type</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('print_type_id',$embelishmenttype,'',array('id'=>'print_type_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt"  class="number integer" value="1" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Paste Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paste_wgt" id="paste_wgt"  class="number integer"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Offer Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="offer_qty" id="offer_qty"  class="number integer" value="" />
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
                    <div id="soembmktcostparamFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCostParam.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParam.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParam.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="soembmktcostparamTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'colorrange'" width="70">Color Range</th>
                            <th data-options="field:'print_type'" width="80">Print Type</th>
                            <th data-options="field:'dia'" width="70">DIA</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'fabric_wgt'" width="70" align="right">Fabric Weight</th>
                            <th data-options="field:'color_ratio_from'" width="100" align="right">Color % From</th>
                            <th data-options="field:'color_ratio_to'" width="100" align="right">Color % To</th>
                            <th data-options="field:'no_of_color_from'" width="100" align="right">No of Color From</th>
                            <th data-options="field:'no_of_color_to'" width="100" align="right">No of Color To</th>
                            <th data-options="field:'paste_wgt'" width="100" align="right">Paste Weight</th>
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
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#soembmktcostparamitemFrmFt'" style="width:400px; padding:2px">
                <form id="soembmktcostparamitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row">
                                    <div class="col-sm-4" ></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="so_aop_mkt_cost_param_id" id="so_aop_mkt_cost_param_id" readonly />
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
                                    <div class="col-sm-4 req-text">Paste Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paste_wgt" id="paste_wgt" value="" class="number integer"  disabled
                                         />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item ID</div>
                                    <div class="col-sm-8">
                                        
                                        <input type="text" name="item_account_id" id="item_account_id" ondblclick="MsSoEmbMktCostParamItem.openitemWindow()" readonly placeholder="Double Click"    />
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
                                    <div class="col-sm-4">Ratio on Paste Wgt</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rto_on_paste_wgt" id="rto_on_paste_wgt" value="" class="number integer" onchange="MsSoEmbMktCostParamItem.calculate_qty('rto_on_paste_wgt')" />
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
                                        <input type="text" name="rate" id="rate" value="" class="number integer"  onchange="MsSoEmbMktCostParamItem.calculate_amount()" 
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
                                    <div class="col-sm-4">Parameters</div>
                                    <div class="col-sm-5">
                                        <input type="text" name="master_fab_id" id="master_fab_id" ondblclick="MsSoEmbMktCostParamItem.openitemCopyWindow()" />
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCostParamItem.itemCopy()">Copy</a>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="soembmktcostparamitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCostParamItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParamItem.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParamItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#soembmktcostparamitemTblFt'" style="padding:2px">
                <table id="soembmktcostparamitemTbl" style="width:100%">
                    <thead>
                        <tr> 
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'category_name'" width="100">Item Category</th>
                            <th data-options="field:'class_name'" width="100">Item Class</th>
                            <th data-options="field:'item_description'" width="100">Description</th>
                            <th data-options="field:'specification'" width="100">Specification</th>
                            <th data-options="field:'uom_name'" width="100">UOM</th>
                            <th data-options="field:'rto_on_paste_wgt'" width="100" align="right">% on Paste Wgt</th>
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
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#soembmktcostspfinFrmFt'" style="width:350px; padding:2px">
                <form id="soembmktcostparamfinFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_aop_mkt_cost_param_id" id="so_aop_mkt_cost_param_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Process: </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Chemical Cost</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount"  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Sequence</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sort_id" id="sort_id"  class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="soembmktcostspfinFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCostParamFin.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParamFin.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParamFin.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="soembmktcostparamfinTbl" style="width:100%">
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
            <div data-options="region:'west',border:true" style="width:340px; padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',border:true,title:'Add Details',footer:'#ft4'"
                    style="height:250px; padding:2px">
                        <form id="soembmktcostqpriceFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row middle" style="display:none">
                                            <input type="hidden" name="id" id="id" value="" />
                                            <input type="hidden" name="so_aop_mkt_cost_id" id="so_aop_mkt_cost_id" value="" />
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
                                iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCostQprice.submit()">Save</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                                iconCls="icon-remove" plain="true" id="delete"
                                onClick="MsSoEmbMktCostQprice.resetForm()">Reset</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                                iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostQprice.remove()">Delete</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                                iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostQprice.pdf()">PDF</a>
                            </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                        <table id="soembmktcostqpriceTbl" style="width:100%">
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
                <form id="soembmktcostqpricedtlFrm" >
                    <input type="hidden" name="id" id="id" value="" />
                    <code id="soembmktcostqpricedtlcosi">
                    </code>
                    <div id="ftqpricedtl" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
                        iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbMktCostQpricedtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                        iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembmktcostqpricedtlFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
                        iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostQpricedtl.remove()">Delete</a>
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
<div id="soembmktcostsubinbserviceWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#subinbserviceWindowFt'" style="width:350px;height:130px">
            <div id="body">
                <code>
                    <form id="soembsubinbservicesearchFrm">
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
                <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoEmbMktCost.searchSoEmbSubInbService()">Search</a>
            </div>
        </div>
        <div data-options="region:'center',footer:'#subinbsearchTblFt'" style="padding:10px;">
            <table id="soembsubinbservicesearchTbl" style="width:100%">
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
            <div id="subinbsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soembmktcostsubinbserviceWindow').window('close')" style="border-radius:1px">Close</a>
            </div>
        </div>
    </div>
</div>


{{-- Marketing Cost Fabric Item Detail --}}
<div id="soembmktcostparamitemWindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#soembmktcostparamitemsearchFrmFt'" style="width:350px;height:130px">
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
                <div id="soembmktcostparamitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbMktCostParamItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soembmktcostparamitemsearchTblFt'" style="padding:10px;">
            <table id="soembmktcostparamitemsearchTbl" style="width:100%">
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

<div id="soembmktcostparamMasterCopyWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="soembmktcostparamMasterCopyTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                        <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                        <th data-options="field:'print_type'" width="80">Dye Type</th>
                        <th data-options="field:'color_ratio_from'" width="100" align="right">Color % From</th>
                        <th data-options="field:'color_ratio_to'" width="100" align="right">Color % To</th>
                        <th data-options="field:'no_of_color_from'" width="100" align="right">No of <br/>Color From</th>
                        <th data-options="field:'no_of_color_to'" width="100" align="right">No of <br/>Color To</th>
                        <th data-options="field:'fabric_wgt'" width="70" align="right">Fabric Weight</th>
                        <th data-options="field:'paste_wgt'" width="70" align="right">Paste Weight</th>
                        <th data-options="field:'amount'" width="100" align="right">Order Value</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>  
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllEmbMktCostController.js"></script>
 
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
    $('#soembmktcostparamFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});

</script>
    