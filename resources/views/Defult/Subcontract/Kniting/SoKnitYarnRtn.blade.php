<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soknityarnrtntabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soknityarnrtnTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'return_date'" width="100">Return Date</th>
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
            <div data-options="region:'west',border:true,title:'Yarn Return to Customer',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soknityarnrtnFrm">
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
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Return Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="return_date" id="return_date" class="datepicker" placeholder=" yy-mm-dd"/>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitYarnRtn.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soknityarnrtnFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitYarnRtn.remove()">Delete</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitYarnRtn.pdf()">Challan</a>
                </div>
            </div>
        </div>
    </div>

    <div title="Returned Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soknititemFrmft'" style="width:450px; padding:2px">
                <form id="soknityarnrtnitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_knit_yarn_rtn_id" id="so_knit_yarn_rtn_id" value="" /> 
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoKnitYarnRtnItem.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_knit_id" id="so_knit_id" />
                                    </div>
                                </div>                             
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Yarn ID </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="so_knit_yarn_rcv_item_id" id="so_knit_yarn_rcv_item_id" value=""  ondblclick="MsSoKnitYarnRtnItem.itemWindow()"  placeholder="Double Click" />                                    
                                    </div>
                                </div>
                                                                                              
                                <div class="row middle">
                                    <div class="col-sm-4 ">Y. Count </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="count" id="count" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_description" id="item_description" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Lot No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot" disabled/>
                                    </div>
                                </div> 
                                    <div class="row middle">
                                    <div class="col-sm-4 req-text">Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" disabled />
                                    </div>
                                </div>               
                                <div class="row middle">
                                    <div class="col-sm-4 ">Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="color_id" id="color_id" disabled />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Returned Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoKnitYarnRtnItem.calculate()"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Recovery Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoKnitYarnRtnItem.calculate()" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">No of Bag </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_bag" id="no_of_bag" value="" class="number integer" />
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
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitYarnRtnItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soknityarnrtnitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitYarnRtnItem.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="soknityarnrtnitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'composition'" width="100">Composition</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'lot'" width="100">Lot</th>
                            <th data-options="field:'supplier_name'" width="100">Supplier</th>
                            <th data-options="field:'color_name'" width="100">Yarn Color</th>
                            <th data-options="field:'qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'rate'" width="80" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
    
<div id="soknityarnrtnsoWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soknityarnrcvsoWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soknityarnrtnsosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="so_no" id="so_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soknityarnrcvsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoKnitYarnRtnItem.getrtnitem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soknityarnrcvsosearchTblFt'" style="padding:10px;">
            <table id="soknityarnrtnitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'receive_date'" width="100">Receive Date</th>

                    </tr>
                </thead>
            </table>
             <div id="soknityarnrcvsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soknityarnrtnsoWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>

<div id="soknityarnrtnitemWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soknityarnrcvitemWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soknityarnrtnitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Yarn id</div>
                                <div class="col-sm-8">
                                    <input type="text" name="count_name" id="count_name">
                                </div>
                            </div>
                           <div class="row middle">
                                <div class="col-sm-4">Type</div>
                                <div class="col-sm-8">
                                    <input type="text" name="type_name" id="type_name">
                                </div>
                            </div> 
                           
                        </form>
                    </code>
                </div>
                 <div id="soknityarnrcvitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoKnitYarnRtnItem.getitem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soknityarnrcvitemsearchTblFt'" style="padding:10px;">
            <table id="soknityarnrtnitemsrchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                        <th data-options="field:'count'" width="100">Count</th>
                        <th data-options="field:'composition_name'" width="200">Composition</th>
                        <th data-options="field:'lot'" width="200">lot</th>
                        <th data-options="field:'supplier_name'" width="200">Supplier Name</th>
                        <th data-options="field:'color_id'" width="200">Color </th>
                        <th data-options="field:'rate'" width="200">Rate</th>
                    </tr>
                </thead>
            </table>
             <div id="soknityarnrcvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soknityarnrtnitemWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitYarnRtnController.js"></script>
 <script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitYarnRtnItemController.js"></script>
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
$('#soknityarnrtnFrm [id="buyer_id"]').combobox();
</script>
    