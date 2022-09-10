<style>
.threedotv:after {content: '\2807';font-size: 13px;color:white}
</style>
<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="sodyeingbomtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="sodyeingbomTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'costing_date'" width="100">Costing Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Dyeing Budget',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="sodyeingbomFrm">
                                <div class="row">
                                    <div class="col-sm-5">Budget ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="id" id="id" readonly />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Sales Order No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoDyeingBom.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_dyeing_id" id="so_dyeing_id" />
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
                                    <div class="col-sm-5 req-text">Order Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="order_val" id="order_val"  class="number integer" disabled  />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Currency</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                               
                                                                  
                               
                                <div class="row middle">
                                    <div class="col-sm-5"> Costing Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="costing_date" id="costing_date" class="datepicker" />
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
                <div id="soknitFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingBom.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingbomFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBom.remove()">Delete</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="pdf" onClick="MsSoDyeingBom.pdf()" >PDF</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Fabrication" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#sodyeingbomfabricFrmFt'" style="width:450px; padding:2px">
                <form id="sodyeingbomfabricFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_dyeing_bom_id" id="so_dyeing_bom_id" value="" />
                                    <input type="hidden" name="so_dyeing_ref_id" id="so_dyeing_ref_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabrication</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabrication" id="fabrication" placeholder="Double Click" ondblclick="MsSoDyeingBomFabric.import()" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GMTS Part</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmtspart" id="gmtspart" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Looks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabriclooks" id="fabriclooks" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Shape</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabricshape" id="fabricshape" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GSM/Weight/</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsm_weight" id="gsm_weight" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="colorrange_id" id="colorrange_id" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Dyeing Type</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dyeingtype" id="dyeingtype" disabled />
                                    </div>
                                </div>
                                
                                
                                
                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt"  class="number integer" disabled  />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Value</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="order_val" id="order_val"  class="number integer" disabled  />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Liqure Ratio</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="liqure_ratio" id="liqure_ratio"  class="number integer" onchange="MsSoDyeingBomFabric.calculate()"  />
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
                    <div id="sodyeingbomfabricFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingBomFabric.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingbomfabricFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomFabric.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists',footer:'#sodyeingbomfabricTblFt'" style="padding:2px">
                <table id="sodyeingbomfabricTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            
                            <th data-options="field:'gmtspart'" width="70">GMT Part</th>
                            <th data-options="field:'construction_name'" width="100">Constructions</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'fabric_color'" width="80" align="right">Fabric Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'dyeingtype'" width="80">Dye Type</th>
                            <th data-options="field:'uom_name'" width="80">Uom</th>
                            <th data-options="field:'qty'" width="70" align="right">Fabric Weight</th>
                            <th data-options="field:'amount'" width="100" align="right">Order Value</th>
                            <th data-options="field:'liqure_ratio'" width="70" align="right">Liqure Ratio</th>
                            <th data-options="field:'liqure_wgt'" width="100" align="right">Liqure Weight</th>
                            <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
                            <th data-options="field:'buyer_name'" width="80">Buyer</th>
                            <th data-options="field:'style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
                    <div id="sodyeingbomfabricTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomFabric.import()">Import</a>
                    </div>
            </div>
        </div>
    </div>
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#sodyeingbomfabricitemFrmFt'" style="width:400px; padding:2px">
                <form id="sodyeingbomfabricitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                
                                <div class="row">
                                    <div class="col-sm-4" ></div>
                                    <div class="col-sm-8">
                                        
                                        <input type="hidden" name="id" id="id" readonly />
                                        <input type="hidden" name="so_dyeing_bom_fabric_id" id="so_dyeing_bom_fabric_id" readonly />
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
                                        
                                        <input type="text" name="item_account_id" id="item_account_id" ondblclick="MsSoDyeingBomFabricItem.openitemWindow()" readonly placeholder="Double Click"    />
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
                                        <input type="text" name="per_on_fabric_wgt" id="per_on_fabric_wgt" value="" class="number integer" onchange="MsSoDyeingBomFabricItem.calculate_qty('per_on_fabric_wgt')" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Gram/L. Liqure</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gram_per_ltr_liqure" id="gram_per_ltr_liqure" value="" class="number integer" onchange="MsSoDyeingBomFabricItem.calculate_qty('gram_per_ltr_liqure')" />
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
                                    <div class="col-sm-4 req-text">Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer"  onchange="MsSoDyeingBomFabricItem.calculate_amount()" 
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
                                        <input type="text" name="master_fab_id" id="master_fab_id" ondblclick="MsSoDyeingBomFabricItem.openitemCopyWindow()" />
                                    </div>
                                    <div class="col-sm-3">
                                        
                                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingBomFabricItem.itemCopy()">Copy</a>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>

                    <div id="sodyeingbomfabricitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingBomFabricItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomFabricItem.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomFabricItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#sodyeingbomfabricitemTblFt'" style="padding:2px">
                <table id="sodyeingbomfabricitemTbl" style="width:100%">
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
    <div title="Overheads" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#sodyeingbomoverheadFrmFt'" style="width:450px; padding:2px">
                <form id="sodyeingbomoverheadFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_dyeing_bom_id" id="so_dyeing_bom_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Value</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="order_val" id="order_val"  class="number integer" disabled  />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Account Head</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('acc_chart_ctrl_head_id', $ctrlHead,'',array('id'=>'acc_chart_ctrl_head_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                    
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cost %</div>
                                    <div class="col-sm-7" style="padding-right:0px">
                                        <input type="text" name="cost_per" id="cost_per"  class="number integer" onchange="MsSoDyeingBomOverhead.calculate()" readonly />
                                        
                                    </div>
                                    <div class="col-sm-1" style="padding-left:0px;text-align:center;" ><a class="threedotv" style="background-color:#0DAB33; padding-top:1px" href="javascript:void(0)" onclick="MsSoDyeingBomOverhead.getCost();"></a></div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cost</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount"  class="number integer"  readonly/>
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="sodyeingbomoverheadFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingBomOverhead.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomOverhead.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomOverhead.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="sodyeingbomoverheadTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'acc_head'" width="250">Account Head</th>
                            <th data-options="field:'cost_per'" width="60" align="right">Cost %</th>
                            <th data-options="field:'amount'" width="80" align="right">Cost</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
    


<div id="sodyeingbomsoWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#sodyeingbomsoWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
            <div id="body">
            <code>
            <form id="sodyeingbomsosearchFrm">
            <div class="row middle">
            <div class="col-sm-4">Sales Order No</div>
            <div class="col-sm-8">
            <input type="text" name="so_no" id="so_no">
            </div>
            </div>
            </form>
            </code>
            </div>
            <div id="sodyeingbomsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoDyeingBom.getsubconorder()">Search</a>
            </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#sodyeingbomsosearchTblFt'" style="padding:10px;">
            <table id="sodyeingbomsosearchTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'id'" width="30">ID</th>
            <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
            <th data-options="field:'company_name'" width="100">Company</th>
            <th data-options="field:'buyer_name'" width="100">Buyer</th>
            <th data-options="field:'order_val'" width="100">Order Value</th>
            <th data-options="field:'currency_code'" width="100">Currency</th>
            <th data-options="field:'remarks'" width="120">Remarks</th>
            </tr>
            </thead>
            </table>
            <div id="sodyeingbomsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#sodyeingbomsoWindow').window('close')" style="border-radius:1px">Close</a>
            </div>
        </div>
    </div>
</div>

<div id="sodyeingbomfabricWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="sodyeingbomfabricsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="30">ID</th>
                    <th data-options="field:'buyer_name'" width="80">Buyer</th>
                    <th data-options="field:'style_ref'" width="100">Style Ref</th>
                    <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                    <th data-options="field:'gmtspart'" width="70">GMT Part</th>
                    <th data-options="field:'construction_name'" width="100">Constructions</th>
                    <th data-options="field:'fabrication'" width="100">Fabrication</th>
                    <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                    <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                    <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                    <th data-options="field:'fabric_color'" width="80" align="right">Fabric Color</th>
                    <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                    <th data-options="field:'dyeingtype'" width="80">Dye Type</th>
                    <th data-options="field:'uom_name'" width="80">Uom</th>
                    <th data-options="field:'qty'" width="70" align="right">Qty</th>
                    <th data-options="field:'pcs_qty'" width="70" align="right">EQV Qty</th>
                    <th data-options="field:'rate'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount'" width="100" align="right">Amount</th>
                    <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
                    </tr>
                </thead>
            </table>
        </div>  
    </div>
</div>


<div id="sodyeingbomfabricitemWindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#sodyeingbomfabricitemsearchFrmFt'" style="width:350px;height:130px">
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
                <div id="sodyeingbomfabricitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingBomFabricItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#sodyeingbomfabricitemsearchTblFt'" style="padding:10px;">
            <table id="sodyeingbomfabricitemsearchTbl" style="width:100%">
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
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="sodyeingbomfabricMasterCopyWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="sodyeingbomfabricMasterCopyTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'gmtspart'" width="70">GMT Part</th>
                        <th data-options="field:'construction_name'" width="100">Constructions</th>
                        <th data-options="field:'fabrication'" width="100">Fabrication</th>
                        <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                        <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                        <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                        <th data-options="field:'fabric_color'" width="80" align="right">Fabric Color</th>
                        <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                        <th data-options="field:'dyeingtype'" width="80">Dye Type</th>
                        <th data-options="field:'uom_name'" width="80">Uom</th>
                        <th data-options="field:'qty'" width="70" align="right">Fabric Weight</th>
                        <th data-options="field:'amount'" width="100" align="right">Order Value</th>
                        <th data-options="field:'liqure_ratio'" width="70" align="right">Liqure Ratio</th>
                        <th data-options="field:'liqure_wgt'" width="100" align="right">Liqure Weight</th>
                        <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
                        <th data-options="field:'buyer_name'" width="80">Buyer</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>  
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingBomController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingBomFabricController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingBomFabricItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingBomOverheadController.js"></script>
 
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
    $('#sodyeingbomfabricFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
$('#sodyeingbomoverheadFrm [id="acc_chart_ctrl_head_id"]').combobox();
</script>
    