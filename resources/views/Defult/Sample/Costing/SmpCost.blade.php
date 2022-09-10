<style>
.threedotv:after {content: '\2807';font-size: 13px;color:white}
</style>
<div class="easyui-accordion" data-options="multiple:false" style="width:99%;" id="smpCostAccordion">

    <div title="Sample Costing" style="padding:1px;height:580px">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Sample Costing',footer:'#ft2'" style="width:400px; padding:2px">
            <form id="smpcostFrm">
                <div id="container">
                     <div id="body">
                       <code>

                         <div class="row">
                             <div class="col-sm-4 req-text">Style</div>
                             <div class="col-sm-8">
                             <input type="text" name="style_ref" id="style_ref" onDblClick="MsSmpCost.openStyleSampleWindow()" readonly placeholder=" Double Click"/>
                             <input type="hidden" name="style_sample_id" id="style_sample_id" value=""/>
                             <input type="hidden" name="id" id="id" value=""/>
                             </div>
                         </div>
                         <div class="row middle">
                            <div class="col-sm-4">Company</div>
                            <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Costing Date </div>
                            <div class="col-sm-8"><input type="text" name="costing_date" id="costing_date" class="datepicker"/></div>
                        </div>
                         <div class="row middle">
                             <div class="col-sm-4 req-text">Costing Unit </div>
                             <div class="col-sm-8">{!! Form::select('costing_unit_id', $costingunit,12,array('id'=>'costing_unit_id')) !!}</div>
                         </div>
                         
                         <div class="row middle">
                             <div class="col-sm-4 req-text">Currency</div>
                             <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                         </div>
                         
                         
                         
                         <div class="row middle">
                             <div class="col-sm-4">Exchange Rate </div>
                             <div class="col-sm-8"><input type="text" name="exchange_rate" id="exchange_rate" class="number integer"/></div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4">Remarks</div>
                             <div class="col-sm-8"><input type="text" name="remarks" id="remarks" value=""/></div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4 req-text">Buyer</div>
                             <div class="col-sm-8">
                              <input type="text" name="buyer_id" id="buyer_id" disabled />
                              <!-- {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!} -->
                            </div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4 req-text">Qty </div>
                             <div class="col-sm-8"><input type="text" name="qty" id="qty" class="number integer" disabled /></div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4 req-text">UOM</div>
                             <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4 req-text">Team</div>
                             <div class="col-sm-8">{!! Form::select('team_id', $team,'',array('id'=>'team_id','disabled'=>'disabled')) !!}</div>
                         </div>
                         
                         
                      </code>
                   </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCost.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCost.remove()" >Delete</a>
                </div>
              </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="smpcostTbl" width="930">
                    <thead>
                    <tr>
                    <th data-options="field:'id'" width="100">ID</th>
                    <th data-options="field:'style_ref'" width="100">Style</th>
                    <th data-options="field:'item_description'" width="100">Gmts. Item</th>
                    <th data-options="field:'gmtssample'" width="100">Gmts. Sample</th>
                    <th data-options="field:'qty'" width="50" align="right">Qty</th>
                    <th data-options="field:'rate'" width="50" align="right">Rate</th>
                    <th data-options="field:'amount'" width="50" align="right">Amount</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Fabric" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,footer:'#ftsmpfabric'" style="padding:2px">
                <form id="smpcostfabricFrm">
                    <code  id="fabricdiv">
                    </code>
                    <div id="ftsmpfabric" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostFabric.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostfabricFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostFabric.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Yarn" style="padding:1px;height:580px">
        <code  id="fabricyarndiv">
        </code>
    </div>
    <div title="Yarn Dyeing" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="smpcostyarndyeingTbl" style="width:500px">
        <thead>
        <tr>
        <th data-options="field:'add_con'" formatter="MsSmpCostDyeing.formatAddCons" width="70">Breakdown</th>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'smpcostfabric'" width="300px">Fabric Description</th>
        <th data-options="field:'process_id'" width="100px">Process</th>
        <th data-options="field:'cons'" width="50px" align="right">Cons</th>
        <th data-options="field:'rate'" width="50px" align="right">Rate</th>
        <th data-options="field:'amount'" width="50px" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Yarn Dyeing',footer:'#ftfaprod'" style="width:350px; padding:2px">
        <form id="smpcostyarndyeingFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric: </div>
        <div class="col-sm-8">
        <select id="smp_cost_fabric_id" name="smp_cost_fabric_id" onChange="MsSmpCostYarnDyeing.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id"  readonly/>
        <input type="hidden" name="smp_cost_id" id="smp_cost_id" readonly/>
        <input type="hidden" name="production_area_id" id="production_area_id" readonly value="5" />
        <input type="hidden" name="production_process_id" id="production_process_id" readonly value="22" />
        </div>
        </div>
        
        <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_colorrange_id">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','onChange'=>'MsSmpCostYarnDyeing.colorRangeChange()')) !!}</div>
          </div>
          <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_yarncount_id">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','onChange'=>'MsSmpCostYarnDyeing.countChange()')) !!}
          </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Req. Cons: </div>
        <div class="col-sm-8"><input type="text" name="req_cons" id="req_cons" class="number integer"  readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons: </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsSmpCostYarnDyeing.calculatemount()"  /></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsSmpCostYarnDyeing.calculatemount()" readonly /></div>
       
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftfaprod" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostYarnDyeing.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostyarndyeingFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostYarnDyeing.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
    </div>
    <div title="Knitting" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="smpcostknittingTbl" style="width:500px">
        <thead>
        <tr>
        <th data-options="field:'add_con'" formatter="MsSmpCostDyeing.formatAddCons" width="70">Breakdown</th>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'smpcostfabric'" width="300px">Fabric Description</th>
        <th data-options="field:'process_id'" width="100px">Process</th>
        <th data-options="field:'cons'" width="50px" align="right">Cons</th>
        <th data-options="field:'rate'" width="50px" align="right">Rate</th>
        <th data-options="field:'amount'" width="50px" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Knitting',footer:'#ftsmpknitting'" style="width:350px; padding:2px">
        <form id="smpcostknittingFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric: </div>
        <div class="col-sm-8">
        <select id="smp_cost_fabric_id" name="smp_cost_fabric_id" onChange="MsSmpCostKnitting.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id"  readonly/>
        <input type="hidden" name="smp_cost_id" id="smp_cost_id" readonly/>
        <input type="hidden" name="production_area_id" id="production_area_id" readonly value="5" />
        <input type="hidden" name="production_process_id" id="production_process_id" readonly value="22" />
        </div>
        </div>
        
        <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_colorrange_id">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','onChange'=>'MsSmpCostKnitting.colorRangeChange()')) !!}</div>
          </div>
          <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_yarncount_id">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','onChange'=>'MsSmpCostKnitting.countChange()')) !!}</div>
          
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Req. Cons: </div>
        <div class="col-sm-8"><input type="text" name="req_cons" id="req_cons" class="number integer"  readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons: </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsSmpCostKnitting.calculatemount()"  /></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsSmpCostKnitting.calculatemount()" readonly/></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftsmpknitting" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostKnitting.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostknittingFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostKnitting.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
    </div>
    <div title="Dyeing" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="smpcostdyeingTbl" style="width:500px">
        <thead>
        <tr>
        <th data-options="field:'add_con'" formatter="MsSmpCostDyeing.formatAddCons" width="70">Breakdown</th>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'smpcostfabric'" width="300px">Fabric Description</th>
        <th data-options="field:'process_id'" width="100px">Process</th>
        <th data-options="field:'cons'" width="50px" align="right">Cons</th>
        <th data-options="field:'rate'" width="50px" align="right">Rate</th>
        <th data-options="field:'amount'" width="50px" align="right">Amount</th>
        
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Dyeing',footer:'#ftsmpdyeing'" style="width:350px; padding:2px">
        <form id="smpcostdyeingFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric: </div>
        <div class="col-sm-8">
        <select id="smp_cost_fabric_id" name="smp_cost_fabric_id" onChange="MsSmpCostDyeing.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id"  readonly/>
        <input type="hidden" name="smp_cost_id" id="smp_cost_id" readonly/>
        <input type="hidden" name="production_area_id" id="production_area_id" readonly value="5" />
        <input type="hidden" name="production_process_id" id="production_process_id" readonly value="22" />
        </div>
        </div>
        
        <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_colorrange_id">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','onChange'=>'MsSmpCostDyeing.colorRangeChange()')) !!}</div>
          </div>
          <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_yarncount_id">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','onChange'=>'MsSmpCostDyeing.countChange()')) !!}</div>
          
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Req. Cons: </div>
        <div class="col-sm-8"><input type="text" name="req_cons" id="req_cons" class="number integer"  readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons: </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsSmpCostDyeing.calculatemount()"  /></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsSmpCostDyeing.calculatemount()" readonly/></div>
       
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftsmpdyeing" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostDyeing.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostdyeingFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostDyeing.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
    </div>
    <div title="Aop" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="smpcostaopTbl" style="width:500px">
        <thead>
        <tr>
        <th data-options="field:'add_con'" formatter="MsSmpCostDyeing.formatAddCons" width="70">Breakdown</th>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'smpcostfabric'" width="300px">Fabric Description</th>
        <th data-options="field:'process_id'" width="100px">Process</th>
        <th data-options="field:'cons'" width="50px" align="right">Cons</th>
        <th data-options="field:'rate'" width="50px" align="right">Rate</th>
        <th data-options="field:'amount'" width="50px" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Aop',footer:'#ftsmpaop'" style="width:350px; padding:2px">
        <form id="smpcostaopFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric: </div>
        <div class="col-sm-8">
        <select id="smp_cost_fabric_id" name="smp_cost_fabric_id" onChange="MsSmpCostAop.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id"  readonly/>
        <input type="hidden" name="smp_cost_id" id="smp_cost_id" readonly/>
        <input type="hidden" name="production_area_id" id="production_area_id" readonly value="5" />
        <input type="hidden" name="production_process_id" id="production_process_id" readonly value="22" />
        </div>
        </div>
        
        <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_colorrange_id">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','onChange'=>'MsSmpCostAop.colorRangeChange()')) !!}</div>
          </div>
          <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_yarncount_id">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','onChange'=>'MsSmpCostAop.countChange()')) !!}</div>
          
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Req. Cons: </div>
        <div class="col-sm-8"><input type="text" name="req_cons" id="req_cons" class="number integer"  readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons: </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsSmpCostAop.calculatemount()"  /></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsSmpCostAop.calculatemount()" readonly/></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftsmpaop" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostAop.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostaopFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostAop.remove()" >Delete</a>
        </div>
        </form>
        </div>
        </div>
    </div>
    <div title="Finishing" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="smpcostfinishingTbl" style="width:500px">
        <thead>
        <tr>
        <th data-options="field:'add_con'" formatter="MsSmpCostDyeing.formatAddCons" width="70">Breakdown</th>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'smpcostfabric'" width="300px">Fabric Description</th>
        <th data-options="field:'process_id'" width="100px">Process</th>
        <th data-options="field:'cons'" width="50px" align="right">Cons</th>
        <th data-options="field:'rate'" width="50px" align="right">Rate</th>
        <th data-options="field:'amount'" width="50px" align="right">Amount</th>
        </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Finishing',footer:'#ftsmpfinishing'" style="width:350px; padding:2px">
        <form id="smpcostfinishingFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row">
        <div class="col-sm-4 req-text">Fabric: </div>
        <div class="col-sm-8">
        <select id="smp_cost_fabric_id" name="smp_cost_fabric_id" onChange="MsSmpCostFinishing.getFabricCons(this.value)"></select>
        <input type="hidden" name="id" id="id"  readonly/>
        <input type="hidden" name="smp_cost_id" id="smp_cost_id" readonly/>
        <input type="hidden" name="production_area_id" id="production_area_id" readonly  />
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Process: </div>
        <div class="col-sm-8">{!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id','onChange'=>'MsSmpCostFinishing.processChange()')) !!}</div>
        </div>
        
        <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_colorrange_id">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','onChange'=>'MsSmpCostFinishing.colorRangeChange()')) !!}</div>
          </div>
          <div class="row middle">
          <div class="col-sm-4 req-text" id="smpcost_fabprod_yarncount_id">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id','onChange'=>'MsSmpCostFinishing.countChange()')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Req. Cons: </div>
        <div class="col-sm-8"><input type="text" name="req_cons" id="req_cons" class="number integer"  readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Cons: </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsSmpCostFinishing.calculatemount()"  /></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsSmpCostFinishing.calculatemount()" readonly/></div>
        
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount : </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
        </div>
        </div>
        <div id="ftsmpfinishing" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostFinishing.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostfinishingFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostFinishing.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
    </div>
    <div title="Trims" style="padding:1px;height:580px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="smpcosttrimTbl" width="620">
            <thead>
            <tr>
            <th data-options="field:'add_con'" formatter="MsSmpCostTrim.formatAddCons" width="70">Breakdown</th>
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
            <div data-options="region:'west',border:true,title:'Add Trim Cost',footer:'#ftsmptrim'" style="width:350px; padding:2px">
            <form id="smpcosttrimFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4 req-text">Item Class: </div>
            <div class="col-sm-8">
            {!! Form::select('itemclass_id', $trimgroup,'',array('id'=>'itemclass_id','onChange'=>'MsSmpCostTrim.setUom(this.value)')) !!}
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="smp_cost_id" id="smp_cost_id" value=""/>
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
            <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsSmpCostTrim.calculatemount()" readonly/></div>
            </div>
            
            <div class="row middle">
            <div class="col-sm-4">Rate : </div>
            <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsSmpCostTrim.calculatemount()"/ readonly></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Amount : </div>
            <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
            </div>

            </code>
            </div>
            </div>
            <div id="ftsmptrim" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostTrim.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcosttrimFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostTrim.remove()" >Delete</a>
            </div>

            </form>
            </div>
            </div>
    </div>
    <div title="Embelishment" style="padding:1px;height:370px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,footer:'#ftsmpemb'" style="padding:2px">
                <form id="smpcostembFrm">
                    <code id="emb">
                    </code>
                    <div id="ftsmpemb" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostEmb.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostembFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostEmb.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="CM Cost" style="padding:1px;;height:200px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="smpcostcmTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'amount'" width="70" align="right">Cost/Unit</th>
                        <th data-options="field:'bom_amount'" width="70" align="right">Amount</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New CM Cost',footer:'#ftsmpcm'" style="width:400px; padding:2px">
                <form id="smpcostcmFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Method: </div>
                                <div class="col-sm-8">
                                {!! Form::select('method_id', $cmmethod,'1',array('id'=>'method_id')) !!}
                                <input type="hidden" name="id" id="id" readonly/>
                                <input type="hidden" name="smp_cost_id" id="smp_cost_id" readonly/>
                                </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Cost/Unit : </div>
                                <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" onchange="MsSmpCostCm.calculate()" readonly /></div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Bom Amount : </div>
                                <div class="col-sm-8"><input type="text" name="bom_amount" id="bom_amount" class="number integer" readonly/></div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ftsmpcm" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostCm.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostcmFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostCm.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Total Cost" style="padding:1px; height:150px">
            <div class="easyui-layout"  data-options="fit:true">
                    <div data-options="region:'east',border:true" style="width:350px; padding:12px">
                        <form id="smpcosttotalFrm">
                            <code>
                                <div class="row">
                                    <div class="col-sm-6 dzn-pcs">Total Cost </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="total_cost" id="total_cost" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-6">Total Cost/Pcs </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="total_cost_pcs" id="total_cost_pcs" class="number integer" readonly/>
                                    </div>
                                </div>
                            </code>
                        </form>
                    </div>
               </div>
		</div>
</div>

<div id="smpwindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                    <div id="body">
                        <code>
                            <form id="stylesamplesearch">
                                <div class="row">
                                    <div class="col-sm-2">Buyer :</div>
                                    <div class="col-sm-4">
                                   {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
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
                         <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsSmpCost.showStyleSampleGrid()">Search</a>
                        </p>
            </div>
        </div>
	   <div data-options="region:'center'" style="padding:10px;">
    		<table id="searchstylesampleTbl" style="width:610px">
              <thead>
                    <tr>
                    <th data-options="field:'id'" width="100">ID</th>
                    <th data-options="field:'style_ref'" width="100">Style</th>
                    <th data-options="field:'buyer_id'" width="100">Buyer</th>
                    <th data-options="field:'item_description'" width="100">Gmts. Item</th>
                    <th data-options="field:'gmtssample'" width="100">Gmts. Sample</th>
                    <th data-options="field:'qty'" width="50" align="right">Qty</th>
                    <th data-options="field:'rate'" width="50" align="right">Rate</th>
                    <th data-options="field:'amount'" width="50" align="right">Amount</th>
                    </tr>
              </thead>
            </table>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#smpwindow').window('close')" style="width:80px">Close</a>
		</div>
	</div>
</div>

<div id="samplecostconsWindow" class="easyui-window" title="Consumption Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <form id="smpcostfabricconFrm">
    <code id="scs">
    </code>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostFabricCon.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostfabricconFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostFabricCon.remove()" >Delete</a>
    </div>

    </form>
    </div>


    <div id="smpcostyarnWindow" class="easyui-window" title="Add Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="smpcostyarnpopupTbl" style="width:100%">
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
        <div data-options="region:'west',border:true,title:'Add New Yarn',footer:'#ftyarnsmp'" style="width:350px; padding:2px">
        <form id="smpcostyarnFrm">
        <div id="container">
        <div id="body">
        <code  id="smpyarndiv">
        </code>
        </div>
        </div>
        <div id="ftyarnsmp" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostYarn.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostyarnFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostYarn.remove()" >Delete</a>
        </div>

        </form>
        </div>
        </div>
    </div>


    <div id="smpcostyarnsearchWindow" class="easyui-window" title="Search Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="smpcostyarnsearchTbl" style="width:100%">
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
    <div data-options="region:'west',border:true,title:'Search',footer:'#ftsmpyarnSearch'" style="width:350px; padding:2px">
    <form id="smpcostyarnsearchFrm">
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
        <div id="ftsmpyarnSearch" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostYarn.yarnsearch()">Search</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostyarnsearchFrm')" >Reset</a>
        </div>

        </form>
    </div>
    </div>
</div>

<div id="SmpCostFabricProdConsWindow" class="easyui-window" title="Pop Up" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <form id="smpcostdyeingconFrm">
    <code id="smpcostfabricprodconscs">
    </code>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostDyeingCon.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostdyeingconFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostDyeingCon.remove()" >Delete</a>
    </div>

    </form>
    </div>

    <div id="smpcosttrimconsWindow" class="easyui-window" title="Trim PopUp" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <form id="smpcosttrimconFrm">
    <code id="smpcosttrimscs">
    </code>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostTrimCon.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcosttrimconFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostTrimCon.remove()" >Delete</a>
    </div>

    </form>
    </div>

    <div id="smpcostembconsWindow" class="easyui-window" title="Emb PopUp" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <form id="smpcostembconFrm">
    <code id="smpcostembscs">
    </code>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmpCostEmbCon.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('smpcostembconFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmpCostEmbCon.remove()" >Delete</a>
    </div>

    </form>
    </div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Sample/Costing/MsAllSmpCostController.js"></script>
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