<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilbuyertabs">
    <div title="Basic" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="buyerTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'code'" width="100">Code</th>
                            <th data-options="field:'vendor_code'" width="100">Vendor Code</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                       </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Buyer',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <form id="buyerFrm">
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
                                    <div class="col-sm-4">Vendor Code</div>
                                    <div class="col-sm-8"><input type="text" name="vendor_code" id="vendor_code" value=""/></div>
                               </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Team</div>
                                    <div class="col-sm-8">{!! Form::select('team_id', $team,'',array('id'=>'team_id','onchange'=>'MsBuyer.getTeamMember(this.value)')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buying Agent</div>
                                    <div class="col-sm-8">{!! Form::select('buying_agent_id', $buyinghouses,'',array('id'=>'buying_agent_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Factory Merchant</div>
                                    <div class="col-sm-8">{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Supplier</div>
                                    <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sew Effin Percent </div>
                                    <div class="col-sm-8"><input type="text" name="sew_effin_percent" id="sew_effin_percent" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Contact Person </div>
                                    <div class="col-sm-8"><input type="text" name="contact_person" id="contact_person" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Designation </div>
                                    <div class="col-sm-8"><input type="text" name="designation" id="designation" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Email </div>
                                    <div class="col-sm-8"><input type="text" name="email" id="email" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Cell No </div>
                                    <div class="col-sm-8"><input type="text" name="cell_no" id="cell_no" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Address </div>
                                    <div class="col-sm-8"><input type="text" name="address" id="address" value=""/></div>
                                </div>

                                

                                <div class="row middle">
                                    <div class="col-sm-4">Is Subcon Delivery Secured</div>
                                    <div class="col-sm-8"><input type="text" name="is_subcon_delv_secured" id="is_subcon_delv_secured" value="" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">CR Limit Amount</div>
                                    <div class="col-sm-8"><input type="text" name="cr_limit_amt" id="cr_limit_amt" value="" /></div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">CR Limit Day </div>
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
                                    <div class="col-sm-4">Is AIT Deduction</div>
                                    <div class="col-sm-8"><input type="text" name="is_ait_deduction" id="is_ait_deduction" value="" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Status  </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                                    </div>
                                </div>
                          </code>
                       </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyer.submit()">Save</a>
                         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyer.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Company" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Company',footer:'#utilcompanybuyerft'" style="width:450px; padding:2px">
            <form id="companybuyerFrm">
            <input type="hidden" name="buyer_id" id="buyer_id" value=""/>
            </form>
            <table id="companybuyerTbl">
            </table>
            <div id="utilcompanybuyerft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCompanyBuyer.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Company'" style="padding:2px">
            <table id="companybuyersavedTbl">
            </table>
            </div>
        </div>
    </div>
    <div title="Nature" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Nature',footer:'#utilbuyernatureft'" style="width:450px; padding:2px">
            <form id="buyernatureFrm">
            <input type="hidden" name="buyer_id" id="buyer_id" value=""/>
            </form>
            <table id="buyernatureTbl">
            </table>
            <div id="utilbuyernatureft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerNature.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Nature',footer:'#utilbuyernatureft1'" style="padding:2px">
            <table id="buyernaturesavedTbl">
            </table>
            
            </div>
        </div>
    </div>
    <div title="Contacts" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Cotacts',footer:'#utilbuyerbranceft'" style="width:450px; padding:2px">
                <form id="buyerbranchFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <div class="col-sm-4 req-text">Name </div>
                                    <div class="col-sm-8"><input type="text" name="name" id="name" value=""/></div>
                                </div>
                                <div class="row middle" style="display:none">
                                    <div class="col-sm-4">Code </div>
                                    <div class="col-sm-8"><input type="text" name="code" id="code" value="" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Contact Person  </div>
                                    <div class="col-sm-8"><input type="text" name="contact_person" id="contact_person" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Country </div>
                                    <div class="col-sm-8">
                                    {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                                    <input type="hidden" name="id" id="id" value=""/>
                                    <input type="hidden" name="buyer_id" id="buyer_id" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Email :</div>
                                    <div class="col-sm-8"><input type="text" name="email" id="email" value="" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Designation </div>
                                    <div class="col-sm-8"><input type="text" name="designation" id="designation" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Address :</div>
                                    <div class="col-sm-8">
                                        <textarea name="address" id="address" value="" ></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipment Day </div>
                                    <div class="col-sm-8"><input type="text" name="shipment_day" id="shipment_day" class="datepicker"/></div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="utilbuyerbranceft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerBranch.submit()">Save</a>
                         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerbranchFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerBranch.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#utilbuyernatureft1'" style="padding:2px">
            <table id="buyerbranchTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'contact_person'" width="100">Contact Person</th>
            <th data-options="field:'buyer'" width="100">Buyer</th>
            <th data-options="field:'country'" width="100">Country</th>
            </tr>
            </thead>
            </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllBuyerController.js"></script>
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
