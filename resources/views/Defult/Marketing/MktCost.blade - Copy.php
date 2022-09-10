<div class="easyui-accordion" data-options="multiple:true" style="width:99%;" id="mktCostAccordion">
        <div title="Cost Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:550px">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add New Marketing Cost',footer:'#ft2'" style="width:400px; padding:2px">
            <form id="mktcostFrm">
                <div id="container">
                     <div id="body">
                       <code>

                         <div class="row">
                             <div class="col-sm-4 req-text">Style</div>
                             <div class="col-sm-8">
                             <input type="text" name="style_ref" id="style_ref" onDblClick="MsMktCost.openStyleWindow()" readonly/>
                             <input type="hidden" name="style_id" id="style_id" value=""/>
                             <input type="hidden" name="id" id="id" value=""/>
                             </div>
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
                             <div class="col-sm-4">Confirm Date</div>
                             <div class="col-sm-8"><input type="text" name="confirm_date" id="confirm_date" class="datepicker"/></div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4">Remarks</div>
                             <div class="col-sm-8"><input type="text" name="remarks" id="remarks" value=""/></div>
                         </div>

                      </code>
                   </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCost.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCost.remove()" >Delete</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsMktCost.pdf()" >Pdf</a>
                </div>

              </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="mktcostTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'style'" width="70">Style</th>
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
                        </tr>
                    </thead>
                </table>
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
        <div title="Yarn cost Summary" style="padding:1px; height:300px">
            <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                    <table id="mktcostyarnTbl" style="width:100%">
                        <thead>
                            <tr>
                                <th data-options="field:'yarn_des'" width="250px">Yarn</th>
                                <th data-options="field:'yarn_cons'" width="70px" align="right">Cons</th>
                                <th data-options="field:'yarn_rate'" width="70px" align="right">Rate</th>
                                <th data-options="field:'yarn_amount'" width="80px" align="right">Amount</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div title="Fabric Production Cost" style="padding:1px; height:250px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="mktcostfabricprodTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'mktcostfabric'" width="300">Fabric Description</th>
        <th data-options="field:'process_id'" width="150">Process</th>
        <th data-options="field:'cons'" width="70" align="right">Cons</th>
        <th data-options="field:'rate'" width="70" align="right">Rate</th>
        <th data-options="field:'amount'" width="70" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add New MktCostFabricProd',footer:'#ftfaprod'" style="width:350px; padding:2px">
        <form id="mktcostfabricprodFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric  </div>
        <div class="col-sm-8">
        <select id="mkt_cost_fabric_id" name="mkt_cost_fabric_id" onChange="MsMktCostFabricProd.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id" value=""/>
        <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" value=""/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Process  </div>
        <div class="col-sm-8">{!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons  </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsMktCostFabricProd.calculatemount()" readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate  </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostFabricProd.calculatemount()" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount</div>
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
            <table id="mktcosttrimTbl" width="500">
            <thead>
            <tr>
            <th data-options="field:'id'" width="50">ID</th>
            <th data-options="field:'item_account'" width="70">Item Class</th>
            <th data-options="field:'description'" width="150">Description</th>
            <th data-options="field:'uom'" width="70">Uom</th>
            <th data-options="field:'cons'" width="50" align="right">Cons</th>
            <th data-options="field:'rate'" width="50" align="right">Rate</th>
            <th data-options="field:'amount'" width="50" align="right">Amount</th>
            </tr>
            </thead>
            </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New MktCostTrim',footer:'#fttrim'" style="width:350px; padding:2px">
            <form id="mktcosttrimFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4 req-text">Item Class  </div>
            <div class="col-sm-8">
            {!! Form::select('itemclass_id', $trimgroup,'',array('id'=>'itemclass_id')) !!}
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" value=""/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Description  </div>
            <div class="col-sm-8"><input type="text" name="description" id="description" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Specification</div>
            <div class="col-sm-8"><input type="text" name="specification" id="specification" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Item Size</div>
            <div class="col-sm-8"><input type="text" name="item_size" id="item_size" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Sup Ref</div>
            <div class="col-sm-8"><input type="text" name="sup_ref" id="sup_ref" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">UOM  </div>
            <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Cons</div>
            <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsMktCostTrim.calculatemount()" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Rate</div>
            <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostTrim.calculatemount()"/></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount</div>
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
        <div title="Other Cost" style="padding:1px; height:200px">
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
                <div data-options="region:'west',border:true,title:'Add New MktCostOther',footer:'#ftother'" style="width:350px; padding:2px">
            <form id="mktcostotherFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4 req-text">Cost Head  </div>
            <div class="col-sm-8">
            {!! Form::select('cost_head_id', $othercosthead,'',array('id'=>'cost_head_id')) !!}
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" value=""/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount</div>
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
        <div title="CM Cost" style="padding:1px;;height:200px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="mktcostcmTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'method_id'" width="70" align="right">Method</th>
            <th data-options="field:'amount'" width="70" align="right">Amount</th>
            </tr>
            </thead>
            </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New MktCostCm',footer:'#ftcm'" style="width:350px; padding:2px">
            <form id="mktcostcmFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4 req-text">Method  </div>
            <div class="col-sm-8">
            {!! Form::select('method_id', $cmmethod,'',array('id'=>'method_id')) !!}
            <input type="hidden" name="id" id="id" readonly/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount</div>
            <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" /></div>
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
        <div title="Commercial Cost" style="padding:1px;height:200px">
           <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="mktcostcommercialTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'rate'" width="70" align="right">Rate</th>
            <th data-options="field:'amount'" width="70" align="right">Amount</th>
            </tr>
            </thead>
            </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New MktCostCommercial',footer:'#ftcommercial'" style="width:350px; padding:2px">
            <form id="mktcostcommercialFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4 req-text">Rate  </div>
            <div class="col-sm-8">
            <input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostCommercial.calculatemount()"/>
            <input type="hidden" name="id" id="id" readonly/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount</div>
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
                                    <div class="col-sm-6">Total Cost </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="total_cost" id="total_cost" class="number integer"/>
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
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'rate'" width="70" align="right">Rate</th>
            <th data-options="field:'amount'" width="70" align="right">Amount</th>
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
            <div class="col-sm-4 req-text">Rate  </div>
            <div class="col-sm-8">
            <input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostProfit.calculatemount()"/>
            <input type="hidden" name="id" id="id" readonly/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount</div>
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
         <div title="Price Before Commission" style="padding:1px; height:100px">
            <div class="easyui-layout"  data-options="fit:true">
                    <div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:350px; padding:12px">
                        <form id="mktcostpricebeforecommissionFrm">
                            <code>
                                <div class="row">
                                    <div class="col-sm-6">Price Before Commis. </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="price_before_commission" id="price_before_commission" class="number integer" readonly/>
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
            <th data-options="field:'rate'" width="70" align="right">Rate</th>
            <th data-options="field:'amount'" width="70" align="right">Amount</th>
            </tr>
            </thead>
            </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New MktCostCommercial',footer:'#ftcommission'" style="width:350px; padding:2px">
            <form id="mktcostcommissionFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row">
            <div class="col-sm-4 req-text">For  </div>
            <div class="col-sm-8">
            {!! Form::select('for_id', $commissionfor,'',array('id'=>'for_id')) !!}
            <input type="hidden" name="id" id="id" readonly/>
            <input type="hidden" name="mkt_cost_id" id="mkt_cost_id" readonly/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Rate  </div>
            <div class="col-sm-8">
            <input type="text" name="rate" id="rate" class="number integer" onChange="MsMktCostCommission.calculatemount()"/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount</div>
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
        <div title="Price After Commission" style="padding:1px; height:100px">
            <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:350px; padding:12px">
                    <form id="mktcostpriceaftercommissionFrm">
                        <code>
                            <div class="row">
                                <div class="col-sm-6">Price After Commission </div>
                                <div class="col-sm-6">
                                    <input type="text" name="price_after_commission" id="price_after_commission" class="number integer" readonly/>
                                </div>
                            </div>
                        </code>
                    </form>
                </div>
            </div>
		</div>
        <div title="Quoted & Target Price" style="padding:1px;height:140px">
          <div class="easyui-layout"  data-options="fit:true">
              <div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:350px; padding:2px">
              <form id="mktcostquotetargetpriceFrm">
              <div id="container">
              <div id="body">
              <code>
              <div class="row">
              <div class="col-sm-4 req-text">Quoted Price  </div>
              <div class="col-sm-8">
              <input type="text" name="quote_price" id="quote_price" class="number integer"/>
              <input type="hidden" name="id" id="id" readonly/>
              </div>
              </div>
              <div class="row middle">
              <div class="col-sm-4 req-text">Target Price  </div>
              <div class="col-sm-8">
              <input type="text" name="target_price" id="target_price" class="number integer"/>
              </div>
              </div>

              </code>
              </div>
              </div>
              <div id="ftmcqtp" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostQuoteTargetPrice.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostquotetargetpriceFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostQuoteTargetPrice.remove()" >Delete</a>
              </div>

              </form>
              </div>
              </div>
		</div>
	</div>

<div id="w" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                    <div id="body">
                        <code>
                            <form id="stylesearch">
                                <div class="row">
                                    <div class="col-sm-2">Buyer</div>
                                    <div class="col-sm-4">
                                   {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                     <div class="col-sm-2 req-text">Style Ref.  </div>
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
    <div id="consWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
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

     <div id="yarnWindow" class="easyui-window" title="Add Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
        <form id="mktcostyarnFrm">
        <code id="yarndiv">
        </code>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostYarn.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('mktcostyarnFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostYarn.remove()" >Delete</a>
        </div>
        </form>
    </div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllMktCostController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});




</script>
