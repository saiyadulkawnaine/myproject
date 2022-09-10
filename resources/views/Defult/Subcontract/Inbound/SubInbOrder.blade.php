<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="subinbworkrcvtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="subinborderTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'production_area_id'" width="100">Prod. Capacity</th>
                            <th data-options="field:'sub_inb_marketing_id'" width="100">MKT Ref</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'recv_date'" width="100">Order Rcv.Date</th>
                            <th data-options="field:'buyer'" width="100">Custm.Buyer</th>
                            <th data-options="field:'style_ref'" width="100">Custm.Style Ref</th>
                            <th data-options="field:'order_no'" width="100">Custm.Order No</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="subinborderFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod. Area</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">MKT Ref.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sub_inb_marketing_id" id="sub_inb_marketing_id" ondblclick="MsSubInbOrder.openSubInbMktWindow()" placeholder=" Double click" />
                                    </div>
                                </div>                                   
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Rcv Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="recv_date" id="recv_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Custm's Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer" id="buyer" />
                                    </div>
                                </div>              
                                <div class="row middle">
                                    <div class="col-sm-4">Custm's StyleRef</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" />           
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Custm's Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="order_no" id="order_no" />
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
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbOrder.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbOrder.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Product Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#serviceft'" style="width:450px; padding:2px">
                <form id="subinborderproductFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="sub_inb_order_id" id="sub_inb_order_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item description </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_description" id="item_description" value=""  ondblclick="MsSubInbOrderProduct.openItemDesWindow()"  placeholder="Double Click to Select" />
                                        <input type="hidden" name="item_account_id" id="item_account_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">GSM </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsm" id="gsm" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Dia  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dia" id="dia" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Color </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="color" id="color" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Size </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="size" id="size" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">SMV  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="smv" id="smv" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order UOM </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSubInbOrderProduct.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSubInbOrderProduct.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Value </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_date" id="delivery_date" value="" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Delivery Point </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_point" id="delivery_point" value="" />
                                    </div>
                                </div>      
                            </code>
                        </div>
                    </div>
                    <div id="serviceft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbOrderProduct.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderproductFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbOrderProduct.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="subinborderproductTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'item_account_id'" width="100">Item description</th>
                            <th data-options="field:'gsm'" width="80" align="right">GSM</th>
                            <th data-options="field:'dia'" width="80" align="right">Dia</th>
                            <th data-options="field:'color'" width="100">Color</th>
                            <th data-options="field:'size'" width="80">Size</th>      
                            <th data-options="field:'smv'" width="100" align="right">SMV</th>      
                            <th data-options="field:'uom_id'" width="80">Order Uom</th>
                            <th data-options="field:'qty'" width="100" align="right">Order Qty</th>
                            <th data-options="field:'rate'" width="80" align="right">Unit/Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Order Value</th>
                            <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Upload File" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add File',iconCls:'icon-more',footer:'#assetimageft'" style="width:450px; padding:2px">
                <form id="subinborderfileFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="sub_inb_order_id" id="sub_inb_order_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">File Upload</div>
                                    <div class="col-sm-8">
                                        <input type="file" id="file_src" name="file_src" />
                                    </div>
                                </div>                               
                            </code>
                        </div>
                    </div>
                    <div id="assetimageft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbOrderFile.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderfileFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbOrderFile.remove()">Delete</a>
                    </div>
                </form>   
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="subinborderfileTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'file_src',halign:'center',align:'center'" width="100" formatter="MsSubInbOrderFile.formatimage">Uploaded File</th>
                            <th data-options="field:'id'"  formatter="MsSubInbOrderFile.formatFile"  width="100">Download</th>
                            
                           {{-- <th data-options="field:'file_src',halign:'center',align:'center'" width="100"><a href={{ asset('images/1532461639.png') }}>Uploaded File</a></th>
 --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Subcontract Inbound Marketing Window --}}
<div id="subinbmktwindow" class="easyui-window" title="Subcontract Inbound Marketing" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#marketingwindowft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="subinbmktsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Prod. Area</div>
                                <div class="col-sm-8">
                                    {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Customer</div>
                                <div class="col-sm-8">
                                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4"> Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="mkt_date" id="mkt_date" class="datepicker" placeholder="YY-MM-DD" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; border-radius:1px" onClick="MsSubInbOrder.showMktGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="subinbmktsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'production_area_id'" width="100">Prod. Capacity</th>
                        <th data-options="field:'team_name'" width="100">Team</th>
                        <th data-options="field:'teammember'" width="100">Marketing Member</th>
                        <th data-options="field:'buyer_id'" width="100">Customer</th>
                        <th data-options="field:'contact'" width="100">Contact</th>
                        <th data-options="field:'contact_no'" width="100">Contact No</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#subinbmktwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Item Account Window --}}
<div id="OpenItemWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="itemsearchFrm">
                            <div class="row">
                                <div class="col-sm-4">Item Category</div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}
                                </div>
                            </div>

                            <div class="row middle">
                                <div class="col-sm-4">Item Class </div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsSubInbOrderProduct.showItemDescription()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="itemsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'name'" width="100">Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'item_description'" width="100">Item Description</th>
                        <th data-options="field:'reorder_level '" width="100">Reorder Level  </th>        
                        
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#OpenItemWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<!-------==============Work Order File Pop Up===================-------------->
<div id="assetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <div id="assetImageWindowoutput"></div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Inbound/MsAllSubInbOrderReceivedController.js"></script>
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

    $('#subinborderFrm [id="buyer_id"]').combobox();
    $('#subinborderproductFrm [id="uom_id"]').combobox();

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });


    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/subinborder/getstyleref?q=%QUERY%',
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
        url: msApp.baseUrl()+'/subinborder/getbuyer?q=%QUERY%',
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
        url: msApp.baseUrl()+'/subinborder/getorder?q=%QUERY%',
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
        url: msApp.baseUrl()+'/subinborderproduct/getsize?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#size').typeahead({
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
    