<div class="easyui-layout "  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="itemaccountsupplierrateTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'supplier_id'" width="100">Supplier</th>
                        <th data-options="field:'custom_name'" width="100">Custom Name</th>
                        <th data-options="field:'date_from'" width="100">Date From</th>
                        <th data-options="field:'date_to'" width="100">Date To</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add New ItemAccountSupplier',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft5'" style="width:390px; padding:2px">
            <form id="itemaccountsupplierrateFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row" style="display:none">
                                <input type="hidden" name="id" id="id" value=""/>
                                <input type="hidden" name="item_account_supplier_id" id="item_account_supplier_id" value=""/>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Item Description </div>
                                <div class="col-sm-8">
                                    <input type="text" name="item_description" id="item_description" value="" disabled />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Specification</div>
                                <div class="col-sm-8">
                                    <input type="text" name="specification" id="specification" value="" disabled/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Supplier</div>
                                <div class="col-sm-8">
                                    {!! Form::select('supplier_name', $supplier,'',array('id'=>'supplier_name', 'disabled'=>'disabled')) !!} 
                                    {{-- <select id="supplier_id" name="supplier_id" onchange="MsItemAccountSupplierRate.supplierCustomName(this.value)" ></select> --}}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Custom Name </div>
                                <div class="col-sm-8">
                                    <input type="text" name="custom_name" id="custom_name" value="" disabled/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Date</div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="firstdate" placeholder=" From" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="lastdate"  placeholder=" To" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Domestic Rate</div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="dom_rate" id="dom_rate" value="" class="number integer" placeholder=" number"/>
                                </div>
                                <div class="col-sm-4" style="padding:0px;width:111px;">
                                    {!! Form::select('dom_currency_id', $currency,'',array('id'=>'dom_currency_id','style'=>'height:20px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Import Rate</div>
                                <div class="col-sm-4" style="padding-right:0px;">
                                    <input type="text" name="foreign_rate" id="foreign_rate" value="" class="number integer" placeholder=" number"/>
                                </div>
                                <div class="col-sm-4" style="padding:0px;width:111px;">
                                    {!! Form::select('foreign_currency_id', $currency,'',array('id'=>'foreign_currency_id','style'=>'height:20px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Exchange Rate</div>
                                <div class="col-sm-8">
                                    <input type="text" name="exch_rate" id="exch_rate" value="" class="number integer" placeholder=" number "/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Remarks </div>
                                <div class="col-sm-8">
                                    <textarea name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemAccountSupplierRate.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountSupplierRate.resetForm()" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountSupplierRate.remove()" >Delete</a>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountSupplierRateController.js"></script>
    <script>  
       (function(){    
            $(".firstdate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                beforeShowDay: function(date) {

                    if (date.getDate() == 1) {
                        return [true, ''];
                    }
                    return [false, ''];
                }
            });

            function LastDayOfMonth(Year, Month) {
                return (new Date((new Date(Year, Month + 1, 1)) - 1)).getDate();
            }

            $('.lastdate').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                beforeShowDay: function(date) {
                    //getDate() returns the day (0-31)
                    if (date.getDate() == LastDayOfMonth(date.getFullYear(), date.getMonth())) {
                        return [true, ''];
                    }
                    return [false, ''];
                }
            });
            })(jQuery);
    </script>