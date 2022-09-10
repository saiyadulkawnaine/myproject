<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="pldyeingtabs">
    <div title="Dyeing Plan" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="pldyeingTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'pl_no'" width="100">Plan No</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'supplier_id'" width="100">Dyeing Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'floor'" width="100">Floor</th>
                            <th data-options="field:'fabric_wo'" width="100">Fabric & WO</th>
                            <th data-options="field:'machine_no'" width="100">Machine</th>
                            <th data-options="field:'pl_date'" width="100">Plan Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Dyeing Plan',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#pldyeingFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="pldyeingFrm">

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
                                    <div class="col-sm-4 req-text">Plan Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="pl_date" id="pl_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                	<div class="col-sm-4 req-text">Dyeing Company</div>
	                                <div class="col-sm-8">
	                                	{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
	                                </div>
                                </div>
                                <div class="row middle">
	                                <div class="col-sm-4 req-text">Machine</div>
	                                <div class="col-sm-8">
	                                	<input type="text" name="machine_no" id="machine_no" ondblclick="MsPlDyeing.pldyeingmachineWindowOpen()"  placeholder="Double Click to Select" readonly  />
	                                	<input type="hidden" name="machine_id" id="machine_id"  readonly  />
	                                </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="brand" id="brand"  class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Capacity</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="prod_capacity" id="prod_capacity"  class="number integer" disabled />
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
                <div id="pldyeingFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlDyeing.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pldyeingFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlDyeing.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Product Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="pldyeingitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <!-- <th data-options="field:'pdf'" formatter="MsPlDyeingItem.formatPdf" width="45"></th> -->
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'colorrange_id'" width="100">Colorrange</th>
                            <th data-options="field:'dia'" width="100">Dia</th>
                            <th data-options="field:'measurment'" width="100">Measurment</th>
                            <th data-options="field:'fabric_color'" width="100">Fabric Color</th>
                            <th data-options="field:'gsm_weight'" width="100">GSM</th>
                            
                            <th data-options="field:'machine_no'" width="100">Machine No</th>
                            <th data-options="field:'brand'" width="100">Brand</th>
                            <th data-options="field:'capacity'" width="100">Tgt/Day</th>
                            <th data-options="field:'qty'" width="100">Qty</th>
                            <th data-options="field:'pl_start_date'" width="100">Plan Start Date</th>
                            <th data-options="field:'pl_end_date'" width="100">Plan End Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Dyeing Plan',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#pldyeingitemFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="pldyeingitemFrm">
                                <div class="row">
                                    <div class="col-sm-4">Fabrication</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="fabrication" id="fabrication" ondblclick="MsPlDyeingItem.pldyeingitemWindowOpen()"  placeholder="Double Click to Select" readonly />
                                    <input type="hidden" name="so_dyeing_ref_id" id="so_dyeing_ref_id" />
                                    <input type="hidden" name="pl_dyeing_id" id="pl_dyeing_id" />
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
                                    <div class="col-sm-4 req-text">Dia</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="dia" id="dia" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Measurment</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="measurment" id="measurment" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Shape</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id','disabled'=>'disabled',)) !!}
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
                                    <input type="text" name="gsm_weight" id="gsm_weight" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Tgt/Day</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="capacity" id="capacity"  class="number integer" onchange="MsPlDyeingItem.setPlEndDate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Total Plan Qty</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="qty" id="qty"  class="number integer" onchange="MsPlDyeingItem.setPlEndDate()"/>
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
                                    	<input type="text" name="pl_end_date" id="pl_end_date" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Dyeing Sales Order </div>
                                <div class="col-sm-8">
                                    <input type="text" name="dyeing_sales_order" id="dyeing_sales_order" disabled />
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
                <div id="pldyeingitemFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlDyeingItem.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pldyeingitemFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlDyeingItem.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Edit Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="pldyeingitemqtyTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="100">ID</th>
                            <th data-options="field:'pl_date'" width="80">Plan Date</th>
                            <th data-options="field:'qty'" width="100" align="right">Plan Qty</th>
                            <th data-options="field:'prod_qty'" width="100" align="right">Prod Qty</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Dyeing Plan',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#pldyeingitemqtyFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="pldyeingitemqtyFrm">
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Plan Date</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="pl_date" id="dyeing_pl_date" class="datepicker" />
                                     <input type="hidden" name="pl_dyeing_item_id" id="pl_dyeing_item_id" />
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
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                    <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="pldyeingitemqtyFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlDyeingItemQty.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pldyeingitemqtyFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlDyeingItemQty.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    
    
</div>



<div id="pldyeingmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#pldyeingmachinesearchTblft'" style="padding:2px">
            <table id="pldyeingmachinesearchTbl" style="width:100%">
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
            <div id="pldyeingmachinesearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#pldyeingmachineWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#pldyeingmachinesearchFrmft'" style="padding:2px; width:350px">
            <form id="pldyeingmachinesearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4">Machine No</div>
                            <div class="col-sm-8"> <input type="text" name="machine_no" id="machine_no" /> </div>
                            </div>
                            <div class="row middle ">
                            <div class="col-sm-4">Brand</div>
                            <div class="col-sm-8"> <input type="text" name="brand" id="brand" /> </div>
                            </div>
                            
                            
                        </code>
                    </div>
                </div>
                <div id="pldyeingmachinesearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsPlDyeing.searchMachine()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="pldyeingitemWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#pldyeingitemsearchTblft'" style="padding:2px">
            <table id="pldyeingitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'dyeing_sales_order'" width="80">Dyeing Sale Order No</th>
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
            <div id="pldyeingitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#pldyeingitemWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#pldyeingitemsearchFrmft'" style="padding:2px; width:350px">
            <form id="pldyeingitemsearchFrm">
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
                <div id="pldyeingitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" onClick="MsPlDyeingItem.searchItem()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsAllPlDyeingController.js"></script>

<script>

$(document).ready(function() {
    $('#pldyeingitemsearchFrm [id="buyer_id"]').combobox();
    $('#pldyeingFrm [id="supplier_id"]').combobox();
    
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
        MsPlDyeingItem.setPlEndDate();
      });


    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/pldyeing/getstyleref?q=%QUERY%',
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
        url: msApp.baseUrl()+'/pldyeing/getbuyer?q=%QUERY%',
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
        url: msApp.baseUrl()+'/pldyeing/getorder?q=%QUERY%',
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
        url: msApp.baseUrl()+'/pldyeingitemnarrowfabric/getsize?',
        replace: function(url, uriEncodedQuery) {
        //url = url + 'code=' + uriEncodedQuery;
        // How to change this to denominator for denominator queries?
        val = $('#pldyeingitemnarrowfabricFrm [name=pl_dyeing_item_id]').val(); 
        if (!val) return url;
        return url + 'pl_dyeing_item_id=' + encodeURIComponent(val)
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
    