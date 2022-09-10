<style>
.threedotv:after {content: '\2807';font-size: 13px;color:white}
</style>

<div class="easyui-accordion" data-options="multiple:false" style="width:99%;" id="mktCostAccordion">
    <div title="Cost Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:580px">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add New Marketing Cost',footer:'#ft2'" style="width:400px; padding:2px">
                <form id="mktcostFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <div class="col-sm-4 req-text">Style</div>
                                    <div class="col-sm-8">
                                     <input type="text" name="style_ref" id="style_ref" onDblClick="MsMktCost.openStyleWindow()" readonly placeholder=" Double Click"/>
                                     <input type="hidden" name="style_id" id="style_id" value=""/>
                                     <input type="hidden" name="id" id="id" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Costing Unit </div>
                                    <div class="col-sm-8">{!! Form::select('costing_unit_id', $costingunit,12,array('id'=>'costing_unit_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Quot Date </div>
                                    <div class="col-sm-8"><input type="text" name="quot_date" id="quot_date" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Currency</div>
                                    <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Incoterm </div>
                                    <div class="col-sm-8">{!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Incoterm Place </div>
                                    <div class="col-sm-8"><input type="text" name="incoterm_place" id="incoterm_place"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Buyer</div>
                                    <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sewing SMV </div>
                                    <div class="col-sm-8"><input type="text" name="sewing_smv" id="sewing_smv" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sewing Efficiency </div>
                                    <div class="col-sm-8"><input type="text" name="sewing_effi_per" id="sewing_effi_per" class="number integer"/></div>
                                </div>
                                {{-- <div class="row middle">
                                    <div class="col-sm-4">No of Manpower</div>
                                    <div class="col-sm-8"><input type="text" name="no_of_manpowar" id="no_of_manpowar" class="number integer"/></div>
                                </div> --}}
                                <div class="row middle">
                                    <div class="col-sm-4">Production/Hour</div>
                                    <div class="col-sm-8"><input type="text" name="production_per_hr" id="production_per_hr" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Exchange Rate </div>
                                    <div class="col-sm-8"><input type="text" name="exchange_rate" id="exchange_rate" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Offer Qty </div>
                                    <div class="col-sm-8"><input type="text" name="offer_qty" id="offer_qty" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">UOM</div>
                                    <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Est Ship Date </div>
                                    <div class="col-sm-8"><input type="text" name="est_ship_date" id="est_ship_date" class="datepicker" onChange="MsMktCost.calculateLeadTime()"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">OP Date</div>
                                    <div class="col-sm-8"><input type="text" name="op_date" id="op_date" class="datepicker" onChange="MsMktCost.calculateLeadTime()"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lead Time </div>
                                    <div class="col-sm-8"><input type="text" name="lead_time" id="lead_time" value="" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Week No </div>
                                    <div class="col-sm-8"><input type="text" name="week_no" id="week_no" value="" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Team</div>
                                    <div class="col-sm-8">{!! Form::select('team_id', $team,'',array('id'=>'team_id','disabled'=>'disabled')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8"><input type="text" name="remarks" id="remarks" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Status</div>
                                    <div class="col-sm-8">{!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Forcasting ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_development_order_qty_id" id="buyer_development_order_qty_id" onDblClick="MsMktCost.openBuyerDevelopmentOrderQtyWindow()" readonly placeholder=" Double Click"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCost.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCost.remove()" >Delete</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsMktCost.pdf()" >Pdf</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsMktCost.pdfquote()" >Pdf-2</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#mktsc'" style="padding:2px">
                <table id="mktcostTbl" width="930">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'style'" width="70">Style Ref</th>
                        <th data-options="field:'company'" width="70">Company</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'costingunit'" width="70">Costing Unit</th>
                        <th data-options="field:'quotdate'" width="70">Quot Date</th>
                        <th data-options="field:'currency'" width="70">Currency</th>
                        <th data-options="field:'incoterm'" width="70">Incoterm ID</th>
                        <th data-options="field:'incotermplace'" width="70">Incoterm Place</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'offerqty'" width="70">Offer Qty</th>
                        <th data-options="field:'estshipdate'" width="70">Est Ship Date</th>
                        <th data-options="field:'opdate'" width="70">OP Date</th>
                        <th data-options="field:'uom'" width="70">Uom</th>
                        <th data-options="field:'team'" width="80">Team</th>
                        <th data-options="field:'approval_status'" width="100">Approve Status</th>
                        </tr>
                        </tr>
                    </thead>
                </table>
                <div id="mktsc" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Quote Date: <input type="text" name="from_date" id="from_date" class="datepicker"
                  style="width: 100px ;height: 23px" />
                    <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                  iconCls="icon-search" plain="true" id="save" onClick="MsMktCost.searchMktCost()">Show</a>
                </div>
            </div>
        </div>
    </div>
	<div title="Fabric Cost" style="overflow:auto;padding:1px;">
        <form id="mktcostfabricFrm">
        <code  id="fabricdiv">
        </code>
         <div id="ftfabric" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFabric.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostfabricFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostFabric.remove()" >Delete</a>
        </div>
        </form>
	</div>
    <div title="Narrow Fabric  Cost" style="padding:1px;">
			<form id="mktcostnarrowfabricFrm">
            <code  id="narrowfabricdiv">
            </code>
             <div id="ftfabric" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostNarrowFabric.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostnarrowfabricFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostNarrowFabric.remove()" >Delete</a>
            </div>
            </form>
	</div>
    <div title="Yarn Cost Summary" style="padding:1px; height:350px">
            <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                    <table id="mktcostyarnTbl" style="width:100%">
                        <thead>
                            <tr>
                                <th data-options="field:'yarn_des'" width="250px">Yarn</th>
                                <th data-options="field:'yarn_ratio'" width="70px" align="right">Ratio</th>
                                <th data-options="field:'yarn_cons'" width="70px" align="right">Cons</th>
                                <th data-options="field:'yarn_rate'" width="70px" align="right">Rate</th>
                                <th data-options="field:'yarn_amount'" width="80px" align="right">Amount</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
    </div>
    <div title="Fabric Production Cost" style="padding:1px; height:350px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostfabricprodTbl" style="width:500px">
        <thead>
        <tr>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'mktcostfabric'" width="300px">Fabric Description</th>
        <th data-options="field:'process_id'" width="100px">Process</th>
        <th data-options="field:'cons'" width="50px" align="right">Cons</th>
        <th data-options="field:'rate'" width="50px" align="right">Rate</th>
        <th data-options="field:'amount'" width="50px" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Fabric Production',footer:'#ftfaprod'" style="width:350px; padding:2px">
        <form id="mktcostfabricprodFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric: </div>
        <div class="col-sm-8">
        <select id="mkt_cost_fabric_id" name="mkt_cost_fabric_id" onChange="MsMktCostFabricProd.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id"  readonly/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
        <input type="hidden" name="production_area_id" id="production_area_id" readonly/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Process: </div>
        <div class="col-sm-8">{!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id','onChange'=>'MsMktCostFabricProd.processChange()')) !!}</div>
        </div>
        <div class="row middle">
          <div class="col-sm-4 req-text" id="mktcost_fabprod_colorrange_id">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','onChange'=>'MsMktCostFabricProd.colorRangeChange()')) !!}</div>
          </div>
          <div class="row middle">
          <div class="col-sm-4 req-text" id="mktcost_fabprod_yarncount_id">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','onChange'=>'MsMktCostFabricProd.countChange()')) !!}</div>
          
      </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Req. Cons: </div>
        <div class="col-sm-8"><input type="text" name="req_cons" id="req_cons" class="number integer"  readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons: </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsMktCostFabricProd.calculatemount()"  /></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-7" style="padding-right:0px"><input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostFabricProd.calculatemount()"/></div>
        <div class="col-sm-1" style="padding-left:0px;text-align:center;" ><a class="threedotv" style="background-color:#0DAB33; padding-top:1px" href="javascript:void(0)" onClick="MsMktCostFabricProd.getRate()"></a></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftfaprod" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFabricProd.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostfabricprodFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostFabricProd.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
    </div>
	<div title="Trim Cost" style="padding:1px; height:350px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcosttrimTbl" width="620">
        <thead>
        <tr>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'item_account'" width="150">Item Class</th>
        <th data-options="field:'description'" width="200">Description</th>
        <th data-options="field:'uom'" width="70">Uom</th>
        <th data-options="field:'cons'" width="50" align="right">Cons</th>
        <th data-options="field:'rate'" width="50" align="right">Rate</th>
        <th data-options="field:'amount'" width="50" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add Trim Cost',footer:'#fttrim'" style="width:350px; padding:2px">
        <form id="mktcosttrimFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4 req-text">Item Class: </div>
        <div class="col-sm-8">
        {!! Form::select('itemclass_id', $trimgroup,'',array('id'=>'itemclass_id','onChange'=>'MsMktCostTrim.setUom(this.value)')) !!}
        <input type="hidden" name="id" id="id" value=""/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" value=""/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Description: </div>
        <div class="col-sm-8"><input type="text" name="description" id="description" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Specification : </div>
        <div class="col-sm-8"><input type="text" name="specification" id="specification" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Item Size : </div>
        <div class="col-sm-8"><input type="text" name="item_size" id="item_size" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Sup Ref : </div>
        <div class="col-sm-8"><input type="text" name="sup_ref" id="sup_ref" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">UOM: </div>
        <div class="col-sm-8">
            <input type="hidden" name="uom_id" id="uom_id" />
            {!! Form::select('uom_name', $uom,'',array('id'=>'uom_name','disabled'=>'disabled')) !!}
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Cons : </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsMktCostTrim.calculatemount()" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Rate : </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostTrim.calculatemount()"/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="fttrim" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostTrim.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcosttrimFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostTrim.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
	</div>
	<div title="Embelishment & Wash Cost" style="padding:1px;">
        <form id="mktcostembFrm">
        <code id="emb">
        </code>
        <div id="ftemb" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostEmb.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostembFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostEmb.remove()" >Delete</a>
        </div>
        </form>
	</div>
    <div title="Other Cost" style="padding:1px; height:250px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostotherTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'cost_head_id'" width="70" align="right">Cost Head</th>
        <th data-options="field:'amount'" width="70" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
            <div data-options="region:'west',border:true,title:'Add Other Cost',footer:'#ftother'" style="width:350px; padding:2px">
        <form id="mktcostotherFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cost Head: </div>
        <div class="col-sm-8">
        {!! Form::select('cost_head_id', $othercosthead,'',array('id'=>'cost_head_id')) !!}
        <input type="hidden" name="id" id="id" value=""/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" value=""/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftother" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostOther.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostotherFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostOther.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
	</div>
    <div title="CM Cost" style="padding:1px;;height:300px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostcmTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="80">Item</th>
        <th data-options="field:'gmt_qty'" width="80">Item Ratio</th>
        <th data-options="field:'smv'" width="70" align="right">SMV</th>
        <th data-options="field:'sewing_effi_per'" width="70" align="right">Sewing Effi.%</th>
        <th data-options="field:'cpm'" width="70" align="right">CPM</th>
        <th data-options="field:'cm_per_pcs'" width="70" align="right">CM/Pcs</th>
        <th data-options="field:'no_of_man_power'" width="70" align="right">Manpower</th>
        <th data-options="field:'prod_per_hour'" width="70" align="right">Prod/Hour</th>
        <th data-options="field:'amount'" width="70" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add New CM Cost',footer:'#ftcm'" style="width:400px; padding:2px">
        <form id="mktcostcmFrm">
        <div id="container">
        <div id="body">
        <code>
        
        <div class="row middle">
        <div class="col-sm-4 req-text">Gmts. Item </div>
        <div class="col-sm-8">
        
        <input type="hidden" name="id" id="id" readonly/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
        <input type="hidden" name="style_gmt_id" id="style_gmt_id" readonly/>
        <input type="text" name="style_gmt_name" id="style_gmt_name" readonly ondblclick="MsMktCostCm.getGmtItem()" />
        </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">SMV </div>
            <div class="col-sm-8">
                <input type="text" name="smv" id="smv" class="number integer" onchange="MsMktCostCm.calCmPerPcs()"/>
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">Sewing Effi.%</div>
            <div class="col-sm-8">
                <input type="text" name="sewing_effi_per" id="sewing_effi_per" class="number integer" onchange="MsMktCostCm.calCmPerPcs()"/>
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">CPM</div>
            <div class="col-sm-8">
                <input type="text" name="cpm" id="cpm" class="number integer" disabled />
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">CM/Pcs</div>
            <div class="col-sm-8">
                <input type="text" name="cm_per_pcs" id="cm_per_pcs" class="number integer" readonly />
            </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">No of Manpower </div>
        <div class="col-sm-8"><input type="text" name="no_of_man_power" id="no_of_man_power" class="number integer" onchange="MsMktCostCm.calProdHour()" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Prod/Hour </div>
        <div class="col-sm-8"><input type="text" name="prod_per_hour" id="prod_per_hour" class="number integer" readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount</div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftcm" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostCm.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostcmFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostCm.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
	</div>
    <div title="Commercial Cost" style="padding:1px;height:165px">
       <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostcommercialTbl" width="170">
        <thead>
        <tr>
        <th data-options="field:'id'" width="30">ID</th>
        <th data-options="field:'rate'" width="70" align="right">Rate</th>
        <th data-options="field:'amount'" width="70" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add Commercial Cost',footer:'#ftcommercial'" style="width:350px; padding:2px">
        <form id="mktcostcommercialFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8">
        <input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostCommercial.calculatemount()"/>
        <input type="hidden" name="id" id="id" readonly/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly/></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftcommercial" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostCommercial.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostcommercialFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostCommercial.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>

	</div>
    <div title="Total Cost" style="padding:1px; height:100px">
        <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:350px; padding:12px">
                    <form id="mktcosttotalFrm">
                        <code>
                            <div class="row">
                                <div class="col-sm-6 dzn-pcs">Total Cost </div>
                                <div class="col-sm-6">
                                    <input type="text" name="total_cost" id="total_cost" class="number integer" readonly/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-6">Total Cost/Unit </div>
                                <div class="col-sm-6">
                                    <input type="text" name="total_cost_pcs" id="total_cost_pcs" class="number integer" readonly/>
                                </div>
                            </div>
                        </code>
                    </form>
                </div>
           </div>
	</div>
    <div title="Profit" style="padding:1px;height:165px">
		<div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostprofitTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="30">ID</th>
        <th data-options="field:'rate'" width="70" align="right" class="dzn-pcs">Rate</th>
        <th data-options="field:'amount'" width="70" align="right" class="dzn-pcs">Amount</th>
        <th data-options="field:'rate_pcs'" width="70" align="right">Rate/Unit</th>
        <th data-options="field:'amount_pcs'" width="70" align="right">Amount/Unit</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Profit',footer:'#ftprofit'" style="width:350px; padding:2px">
        <form id="mktcostprofitFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4 dzn-pcs req-text">Rate </div>
        <div class="col-sm-8">
        <input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostProfit.calculatemount()"/>
        <input type="hidden" name="id" id="id" readonly/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 dzn-pcs">Amount  </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly/></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftprofit" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostProfit.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostprofitFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostProfit.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
	</div>
    <div title="Price Before Commission" style="padding:1px; height:130px">
        <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:410px; padding:12px">
                    <form id="mktcostpricebeforecommissionFrm">
                        <code>
                            <div class="row">
                                <div class="col-sm-6 dzn-pcs">Price Before Commission </div>
                                <div class="col-sm-6">
                                    <input type="text" name="price_before_commission" id="price_before_commission" class="number integer" readonly/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-6">Price Before Commission/Unit </div>
                                <div class="col-sm-6">
                                    <input type="text" name="price_before_commission_pcs" id="price_before_commission_pcs" class="number integer" readonly/>
                                </div>
                            </div>
                        </code>
                    </form>
                </div>
           </div>
	</div>
    <div title="Commission Cost" style="padding:1px;height:200px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="mktcostcommissionTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'for_id'" width="70" align="right">For</th>
            <th data-options="field:'rate'" width="70" align="right" class="dzn-pcs">Rate</th>
            <th data-options="field:'amount'" width="70" align="right" class="dzn-pcs">Amount</th>
            <th data-options="field:'rate_pcs'" width="70" align="right">Rate/Unit</th>
            <th data-options="field:'amount_pcs'" width="70" align="right">Amount/Unit</th>
            </tr>
            </thead>
            </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Mkt Cost Commision',footer:'#ftcommission'" style="width:350px; padding:2px">
            <form id="mktcostcommissionFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row">
            <div class="col-sm-4 req-text">For </div>
            <div class="col-sm-8">
            {!! Form::select('for_id', $commissionfor,'',array('id'=>'for_id')) !!}
            <input type="hidden" name="id" id="id" readonly/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 dzn-pcs req-text">Rate </div>
            <div class="col-sm-8">
            <input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostCommission.calculatemount()"/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 dzn-pcs">Amount </div>
            <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly/></div>
            </div>

            </code>
            </div>
            </div>
            <div id="ftcommission" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostCommission.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostcommissionFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostCommission.remove()" >Delete</a>
            </div>

            </form>
            </div>
            </div>

	</div>
    <div title="Price After Commission" style="padding:1px; height:140px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:400px; padding:12px">
                <form id="mktcostpriceaftercommissionFrm">
                    <code>
                        <div class="row">
                            <div class="col-sm-6 dzn-pcs">Price After Commission </div>
                            <div class="col-sm-6">
                                <input type="text" name="price_after_commission" id="price_after_commission" class="number integer" readonly/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-6">Price After Commission/Unit </div>
                            <div class="col-sm-6">
                                <input type="text" name="price_after_commission_pcs" id="price_after_commission_pcs" class="number integer" readonly/>
                            </div>
                        </div>
                    </code>
                </form>
            </div>
        </div>
	</div>
    <div title="Quoted Price" style="padding:1px;height:255px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,footer:'#ftquoteprice'" style="width:450px; padding:2px">
                <form id="mktcostquotepriceFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                <div class="col-sm-4 req-text">Quoted Price/Unit</div>
                                <div class="col-sm-8">
                                <input type="text" name="quote_price" id="quote_price" class="number integer"/>
                                <input type="hidden" name="id" id="id" readonly/>
                                <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
                                </div>
                                </div>

                                <div class="row middle">
                                <div class="col-sm-4 req-text">Price Date</div>
                                <div class="col-sm-8">
                                <input type="text" name="qprice_date" id="qprice_date" class="datepicker"/>
                                </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Submission Date</div>
                                <div class="col-sm-8"><input type="text" name="submission_date" id="submission_date" class="datepicker"/></div>
                                </div>
                                

                                <div class="row middle">
                                <div class="col-sm-4">Refused Date</div>
                                <div class="col-sm-8"><input type="text" name="refused_date" id="refused_date" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Cancel Date</div>
                                <div class="col-sm-8"><input type="text" name="cancel_date" id="cancel_date" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Confirm Date</div>
                                <div class="col-sm-8"><input type="text" name="confirm_date" id="confirm_date" class="datepicker"/></div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ftquoteprice" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostQuotePrice.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostQuotePrice.resetForm()" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostQuotePrice.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center'" style="padding:10px;">
                <table id="mktcostquotepriceTbl" width="180">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'qprice_date'" width="80">Price Date</th>
                            <th data-options="field:'quote_price'" width="70" align="right">Price</th>
                            <th data-options="field:'submission_date'" width="80">Submission Date</th>
                            <th data-options="field:'confirm_date'" width="80">Confirm Date</th>
                            <th data-options="field:'refused_date'" width="80">Refused Date</th>
                            <th data-options="field:'cancel_date'" width="80">Cancel Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
	</div>
    <div title="Target Price" style="padding:1px;height:140px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,footer:'#fttargetprice'" style="width:450px; padding:2px">
            <form id="mktcosttargetpriceFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row">
            <div class="col-sm-4 req-text">Target Price/Unit </div>
            <div class="col-sm-8">
            <input type="text" name="target_price" id="target_price" class="number integer"/>
            <input type="hidden" name="id" id="id" readonly/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Price Date </div>
            <div class="col-sm-8">
            <input type="text" name="price_date" id="price_date" class="datepicker"/>
            </div>
            </div>

            </code>
            </div>
            </div>
            <div id="fttargetprice" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostTargetPrice.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcosttargetpriceFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostTargetPrice.remove()" >Delete</a>
            </div>

            </form>
            </div>
            <div data-options="region:'center'" style="padding:10px;">
                <table id="mktcosttargetpriceTbl" width="180">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'price_date'" width="80">Price Date</th>
                            <th data-options="field:'target_price'" width="70" align="right">Price</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
	</div>
</div>

<div id="w" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
                <div class="easyui-layout" data-options="fit:true">
                    <div id="body">
                        <code>
                            <form id="stylesearch">
                                <div class="row">
                                    <div class="col-sm-2">Buyer :</div>
                                    <div class="col-sm-4">
                                   {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                     <div class="col-sm-2 req-text">Style Ref. </div>
                                    <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                                </div>
                                <div class="row middle">
                                     <div class="col-sm-2">Style Des.  </div>
                                     <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                                </div>
                            </form>
                        </code>
                    </div>
                        <p class="footer">
                         <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsMktCost.showStyleGrid()">Search</a>
                        </p>
                </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
		<table id="styleTbl" style="width:610px">
           <thead>
              <tr>
                  <th data-options="field:'id'" width="50">ID</th>
                  <th data-options="field:'buyer'" width="70">Buyer</th>
                  <th data-options="field:'receivedate'" width="80">Receive Date</th>
                  <th data-options="field:'style_ref'" width="100">Style Refference</th>
                  <th data-options="field:'style_description'" width="150">Style Description</th>
                  <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                  <th data-options="field:'productdepartment'" width="80">Product Department</th>
                  <th data-options="field:'season'" width="80">Season</th>
             </tr>
          </thead>
        </table>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
				<a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#w').window('close')" style="width:80px">Close</a>
			</div>
		</div>
	</div>
<div id="consWindow" class="easyui-window" title="Consumption Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <form id="mktcostfabricconFrm">
        <code id="scs">
        </code>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFabricCon.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostfabricconFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostFabricCon.remove()" >Delete</a>
        </div>
    </form>
</div>
<div id="mktcostyarnWindow" class="easyui-window" title="Add Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostyarnpopupTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'yarn_des'" width="70">Yarn</th>
        <th data-options="field:'yarn_ratio'" width="70" align="right">Ratio</th>
        <th data-options="field:'yarn_cons'" width="70" align="right">Cons</th>
        <th data-options="field:'yarn_rate'" width="70" align="right">Rate</th>
        <th data-options="field:'yarn_amount'" width="70" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add New Yarn',footer:'#ftyarn'" style="width:350px; padding:2px">
        <form id="mktcostyarnFrm">
        <div id="container">
        <div id="body">
        <code  id="yarndiv">
        </code>
        </div>
        </div>
        <div id="ftyarn" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostYarn.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostyarnFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostYarn.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
</div>
    
<div id="mktcostyarnsearchWindow" class="easyui-window" title="Search Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="mktcostyarnsearchTbl" style="width:100%">
        <thead>
        <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'itemclass_name'" width="70">Item Class</th>
            <th data-options="field:'count'" width="70">Count</th>
            <th data-options="field:'yarn_type'" width="70">Type</th>
            <th data-options="field:'composition_name'" width="70">Compositions</th>
        </tr>
        </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ftyarnSearch'" style="width:350px; padding:2px">
    <form id="mktcostyarnsearchFrm">
        <div id="container">
        <div id="body">
        <code>
            <div class="row">
            <div class="col-sm-4 req-text">Count  </div>
            <div class="col-sm-8"><input type="text" name="count_name" id="count_name"/></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Type  </div>
            <div class="col-sm-8"><input type="text" name="type_name" id="type_name" /></div>
            </div>
        </code>
        </div>
        </div>
        <div id="ftyarnSearch" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostYarn.yarnsearch()">Search</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostyarnsearchFrm')" >Reset</a>
        </div>

        </form>
    </div>
    </div>
</div>

<div id="itemWindow" class="easyui-window" title="Add Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:550px;padding:2px;">
</div>

<div id="buyerdevelopmentorderqtyWindow" class="easyui-window" title="Order Forcasting Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="buyerdevelopmentorderqtysearch">
                            <div class="row middle">
                                <div class="col-sm-2">Ext.ShipDate</div>
                                <div class="col-sm-2" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                </div>
                                <div class="col-sm-2" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsMktCost.showBuyerDevelopmentOrderQtyGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="buyerdevelopmentorderqtyTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'buyer_name'" width="120">Buyer</th>
                        <th data-options="field:'brand_name'" width="120">Brand</th>
                        <th data-options="field:'style_description'" width="100">Style Description</th>
                        <th data-options="field:'est_ship_date'" width="80">Est Shipdate</th>
                        <th data-options="field:'qty'" width="70">Qty</th>
                        <th data-options="field:'rate'" width="60">Rate</th>
                        <th data-options="field:'amount',align:'right'" width="70">Amount</th>
                        <th data-options="field:'rcv_qty',align:'right'" width="70">Receive Qty</th>
                        <th data-options="field:'rcv_rate'" width="70">Receive Rate</th>
                        <th data-options="field:'rcv_amount'" width="70">Receive Amount</th>
                        <th data-options="field:'team'" width="120">Team</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#buyerdevelopmentorderqtyWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<div id="mktCmGmtItemWindow" class="easyui-window" title="Gmts. Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="mktcostgmtsTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'itemaccount'" width="100">Item Account</th>
    <th data-options="field:'gmtqty'" width="70" align="right">GMT Qty Ratio</th>
    <th data-options="field:'itemcomplexity'" width="100">Item Complexity</th>
    <th data-options="field:'gmtcategory'" width="100">GMT Category</th>
    <th data-options="field:'created_by_user'" width="100">Entry By</th>
    <th data-options="field:'created_at'" width="100">Entry At</th>
    <th data-options="field:'updated_by_user'" width="100">Updated By</th>
    <th data-options="field:'updated_at'" width="100">Updated At</th>
    <th data-options="field:'article'" width="100">Article</th>
    <th data-options="field:'smv'" width="100">SMV</th>
    <th data-options="field:'sewing_effi_per'" width="100">Sewing Effi. %</th>
    <th data-options="field:'no_of_man_power'" width="100">No of Manpower </th>
    <th data-options="field:'prod_per_hour'" width="100">Prod/Hour</th>
    <th data-options="field:'remarks'" width="100">Remarks</th>
    <th data-options="field:'cpm'" width="100">CPM</th>
    </tr>
    </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllMktCostController.js"></script>
<script>
    (function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
        $('#stylesearch [id="buyer_id"]').combobox();
    })(jQuery);
</script>