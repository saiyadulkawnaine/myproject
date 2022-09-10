<div class="easyui-layout "  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="itemaccountsupplierTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'supplier_id'" width="100">Supplier</th>
                    <th data-options="field:'custom_name'" width="100">Custom Name</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New ItemAccountSupplier',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft4'" style="width:380px; padding:2px">
        <form id="itemaccountsupplierFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row" style="display:none">
                            <input type="hidden" name="id" id="id" value=""/>
                            <input type="hidden" name="item_account_id" id="item_account_id" value=""/>
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
                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Custom Name  </div>
                            <div class="col-sm-8">
                                <textarea name="custom_name" id="custom_name"></textarea>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Country Origin</div>
                            <div class="col-sm-8">
                                {!! Form::select('country_id', $country,'',array('id'=>'country_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Supply Point</div>
                            <div class="col-sm-8">
                                {!! Form::select('supplier_point_id', $country,'',array('id'=>'supplier_point_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Prod Dosage</div>
                            <div class="col-sm-8">
                                <input type="text" name="prod_dosage" id="prod_dosage" value="" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">HS Code</div>
                            <div class="col-sm-8">
                                <input type="text" name="hs_code" id="hs_code" value="" />
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
            <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemAccountSupplier.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountSupplier.resetForm()" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountSupplier.remove()" >Delete</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountSupplierController.js"></script>
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

        $('#itemaccountsupplierFrm [id="supplier_id"]').combobox();
    
    })(jQuery);
    </script>