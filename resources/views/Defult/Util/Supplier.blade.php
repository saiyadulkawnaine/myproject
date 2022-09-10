<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilsuppliertabs">
<div title="Basic" style="padding:2px">
<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="supplierTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="50">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'code'" width="100">Code</th>
        <th data-options="field:'address'" width="100">Address</th>
        <th data-options="field:'nature_name'" width="120">Nature</th>
        <th data-options="field:'company_id'" width="100">Company</th>
        <th data-options="field:'vendor_code'" width="100">Vendor Code</th>
        <th data-options="field:'contact_person'" width="100">Contact person</th>
        <th data-options="field:'status_id'" width="100">Status</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New Supplier',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
<form id="supplierFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Name</div>
                    <div class="col-sm-8">
                    <input type="text" name="name" id="name" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4 req-text">Code </div>
                    <div class="col-sm-8"><input type="text" name="code" id="code" value=""/></div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Vendor Code </div>
                    <div class="col-sm-8"><input type="text" name="vendor_code" id="vendor_code" value=""/></div>
               </div>

                <div class="row middle">
                    <div class="col-sm-4">Buyer </div>
                    <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Company</div>
                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Contact Person  </div>
                    <div class="col-sm-8"><input type="text" name="contact_person" id="contact_person" value=""/></div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Designation </div>
                    <div class="col-sm-8"><input type="text" name="designation" id="designation" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Country  </div>
                    <div class="col-sm-8">{!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}</div>
               </div>

                <div class="row middle">
                    <div class="col-sm-4">Address :</div>
                    <div class="col-sm-8">
                        {{-- <input type="text" name="address" id="address" value="" /> --}}
                        <textarea name="address" id="address"></textarea>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Factory Address:</div>
                    <div class="col-sm-8">
                        <textarea name="factory_address" id="factory_address"></textarea>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Email  </div>
                    <div class="col-sm-8"><input type="text" name="email" id="email" value=""/></div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">CR Limit Amount :</div>
                    <div class="col-sm-8"><input type="text" name="cr_limit_amt" id="cr_limit_amt" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">CR Limit Day  </div>
                    <div class="col-sm-8"><input type="text" name="cr_limit_Day" id="cr_limit_Day" value=""/></div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Discount Method </div>
                    <div class="col-sm-8">{!! Form::select('discount_method_Id', $discountmethod,'',array('id'=>'discount_method_Id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Is Sec Deduction  </div>
                    <div class="col-sm-8">{!! Form::select('is_sec_deduction', $yesno,'',array('id'=>'is_sec_deduction')) !!}</div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Is VAT Deduction </div>
                    <div class="col-sm-8">{!! Form::select('is_vat_deduction', $yesno,'',array('id'=>'is_vat_deduction')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Is AIT Deduction </div>
                    <div class="col-sm-8">{!! Form::select('is_ait_deduction', $yesno,'',array('id'=>'is_ait_deduction')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Status </div>
                    <div class="col-sm-8">
                        {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                    </div>
                </div>
          </code>
    </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSupplier.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('supplierFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSupplier.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
</div>
<div title="Company" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:true,title:'New Company',footer:'#utilcompanysupplierft'" style="width:450px; padding:2px">

            <form id="companysupplierFrm">
                <input type="hidden" name="supplier_id" id="supplier_id" value="" />
            </form>
            <table id="companysupplierTbl">

            </table>
            <div id="utilcompanysupplierft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCompanySupplier.submit()">Save</a>
            </div>
        </div>
        <div data-options="region:'center',border:true,title:'Taged Company'" style="padding:2px">
            <table id="companysuppliersavedTbl">

            </table>
        </div>
    </div>
</div>
<div title="Nature" style="padding:2px">
   <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:true,title:'New Nature',footer:'#utilsuppliernatureft'" style="width:450px; padding:2px">

            <form id="suppliernatureFrm">
                <input type="hidden" name="supplier_id" id="supplier_id" value="" />
            </form>
            <table id="suppliernatureTbl">

            </table>
            <div id="utilsuppliernatureft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSupplierNature.submit()">Save</a>
            </div>
        </div>
        <div data-options="region:'center',border:true,title:'Taged Nature'" style="padding:2px">
            <table id="suppliernaturesavedTbl">

            </table>
        </div>
    </div>

</div>

</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllSupplierController.js"></script>
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
