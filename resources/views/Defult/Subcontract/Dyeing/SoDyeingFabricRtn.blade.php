<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="sodyeingfabricrtntabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="sodyeingfabricrtnTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'return_date'" width="100">Returned Date</th>
                            <th data-options="field:'driver_name'" width="100">Driver Name</th>
                            <th data-options="field:'driver_contact_no'" width="100">Driver Contact</th>
                            <th data-options="field:'driver_license_no'" width="100">Driver License No</th>
                            <th data-options="field:'lock_no'" width="100">Lock No</th>
                            <th data-options="field:'truck_no'" width="100">Truck No</th>
                            <th data-options="field:'recipient'" width="100">Recipient</th>                           
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Dyeing Return to Customer',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="sodyeingfabricrtnFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>                                                                                             
                               
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Returned Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="return_date" id="return_date" class="datepicker" placeholder="yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Driver Name </div>
                                    <div class="col-sm-7">
                                    <input type="text" name="driver_name" id="driver_name" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Driver Contact</div>
                                    <div class="col-sm-7">
                                    <input type="text" name="driver_contact_no" id="driver_contact_no" placeholder="(+88) 01234567890" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Driver License No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="driver_license_no" id="driver_license_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Lock No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lock_no" id="lock_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Truck No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="truck_no" id="truck_no" value="" placeholder=" write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Recipient</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="recipient" id="recipient" value="" placeholder=" Write" />
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRtn.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingfabricrtnFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingFabricRtn.remove()">Delete</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingFabricRtn.pdf()">Challan</a>
                </div>
            </div>
        </div>
    </div>

    <div title="Returned Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soknititemFrmft'" style="width:450px; padding:2px">
                <form id="sodyeingfabricrtnitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_dyeing_fabric_rtn_id" id="so_dyeing_fabric_rtn_id" value="" /> 
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Description </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabrication" id="fabrication" value=""  ondblclick="MsSoDyeingFabricRtnItem.itemWindow()"  placeholder="Double Click" />
                                          <input type="hidden" name="so_dyeing_ref_id" id="so_dyeing_ref_id" />                                 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" disabled />
                                    </div>
                                </div>
                                                                                              
                                <div class="row middle">
                                    <div class="col-sm-4 ">Fabric Looks </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabriclooks" id="fabriclooks" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Fabric Shape</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabricshape" id="fabricshape" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">GSM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsm_weight" id="gsm_weight" disabled/>
                                    </div>
                                </div> 
                                                 
                                <div class="row middle">
                                    <div class="col-sm-4 ">Dyeing Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_color_id" id="fabric_color_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Color Range</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="colorrange_id" id="colorrange_id" disabled />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Returned Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoDyeingFabricRtnItem.calculate()"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Recovery Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoDyeingFabricRtnItem.calculate()" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">No of Roll </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_roll" id="no_of_roll" value="" class="number integer"/>
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
                    <div id="soknititemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRtnItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingfabricrtnitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingFabricRtnItem.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="sodyeingfabricrtnitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'fabric_color_id'" width="80" align="right">Dyeing Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
    


<div id="sodyeingfabricrtnitemWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#sodyeingfabricrtnitemWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="sodyeingfabricrtnitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sales_order_no" id="sales_order_no">
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                 <div id="sodyeingfabricrtnitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoDyeingFabricRtnItem.getitem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#sodyeingfabricrtnitemsearchTblFt'" style="padding:10px;">
            <table id="sodyeingfabricrtnitemsrchTbl" style="width:100%">
                <thead>
                    <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'so_dyeing_id'" width="80">Sales Order ID</th>
                            <th data-options="field:'sales_order_no'" width="80">Dyeing Sales<br/> Order No</th>
                            <th data-options="field:'fabrication'" width="300">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'fabric_color'" width="80" align="right">Dyeing Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
 
                    </tr>
                </thead>
            </table>
             <div id="sodyeingfabricrtnitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#sodyeingfabricrtnitemWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingFabricRtnController.js"></script>
 <script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingFabricRtnItemController.js"></script>
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
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});

$('#sodyeingfabricrtnFrm [id="buyer_id"]').combobox();
</script>
    