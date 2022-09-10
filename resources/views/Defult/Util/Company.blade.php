<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center', border:false">
        <div class="easyui-layout" data-options="fit:true" id="group_lay">
            <div data-options="region:'center',border:true,title:'Add New Company',footer:'#ft2'" style="padding:2px">
                <form id="companyFrm" enctype="multipart/form-data">
                    <div class="easyui-tabs" style="width:100%;height:280px; border:none">
                        <div title="Basic" style="padding:2px">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row">
                                        <div class="col-sm-2 req-text">Name</div>
                                        <div class="col-sm-3">
                                        <input type="text" name="name" id="name" value=""/>
                                        <input type="hidden" name="id" id="id" value=""/>
                                        </div>
                                        <div class="col-sm-2 req-text">Group Name</div>
                                        <div class="col-sm-5">{!! Form::select('cgroup_id', $cgroups,'',array('id'=>'cgroup_id')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2 req-text">Code </div>
                                        <div class="col-sm-3"><input type="text" name="code" id="code" value=""/></div>
                                         <div class="col-sm-2">CEO </div>
                                        <div class="col-sm-5"><input type="text" name="ceo" id="ceo" value="" /></div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">Email  </div>
                                        <div class="col-sm-3"><input type="text" name="email" id="email" value=""/></div>
                                        <div class="col-sm-2 req-text">Sequence </div>
                                        <div class="col-sm-5"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2 req-text">Legal Status ID </div>
                                            <div class="col-sm-3">
                                                {!! Form::select('legal_status_id', $legalstatus,'',array('id'=>'legal_status_id')) !!}
                                            </div>
                                            <div class="col-sm-2 req-text">Nature</div>
                                            <div class="col-sm-5">
                                                {!! Form::select('nature_id', $companynature,'',array('id'=>'nature_id')) !!}
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Status  </div>
                                            <div class="col-sm-3">
                                                {!! Form::select('status_id', $status,'',array('id'=>'status_id')) !!}
                                            </div>
                                            <div class="col-sm-2">Phone</div>
                                            <div class="col-sm-5">
                                                <input type="text" name="contact" id="contact" value=""/>
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Address  </div>
                                            <div class="col-sm-3">
                                                <textarea name="address" id="address"></textarea>
                                            </div>
                                            <div class="col-sm-2">Postcode</div>
                                            <div class="col-sm-5">
                                                <input type="text" name="post_code" id="post_code" value=""/>
                                            </div>
                                        </div> 
                                    </code>
                                </div>
                            </div>
                        </div>
                        <div title="Legality" style="padding:2px">
                        	<div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row">
                                        <div class="col-sm-2">Trade License No:</div>
                                        <div class="col-sm-3"><input type="text" name="trade_license_no" id="trade_license_no" value="" /></div>
                                        <div class="col-sm-2">Incorporation No</div>
                                        <div class="col-sm-5"><input type="text" name="incorporation_no" id="incorporation_no" value=""/></div>

                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">ERC No :</div>
                                        <div class="col-sm-3"><input type="text" name="erc_no" id="erc_no" value="" /></div>
                                        <div class="col-sm-2">IRC No</div>
                                        <div class="col-sm-5"><input type="text" name="irc_no" id="irc_no" value=""/></div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">TIN No :</div>
                                        <div class="col-sm-3"><input type="text" name="tin_number" id="tin_number" value="" /></div>
                                        <div class="col-sm-2">VAT No</div>
                                        <div class="col-sm-5"><input type="text" name="vat_number" id="vat_number" value=""/></div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">EPB Reg No :</div>
                                        <div class="col-sm-3"><input type="text" name="epb_reg_no" id="epb_reg_no" value="" /></div>
                                        <div class="col-sm-2">BAN Bank Reg No :</div>
                                        <div class="col-sm-5"><input type="text" name="ban_bank_reg_no" id="ban_bank_reg_no" value="" /></div>

                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">ERC Expiry Date :</div>
                                        <div class="col-sm-3"><input type="txt" name="erc_expiry_date" id="erc_expiry_date" value="" class="datepicker" /></div>
                                        <div class="col-sm-2">IRC Expiry Date</div>
                                        <div class="col-sm-5"><input type="txt" name="irc_expiry_date" id="irc_expiry_date" value="" class="datepicker"/></div>
                                        </div>


                                        <div class="row middle">
                                        <div class="col-sm-2">T/L Renew Date</div>
                                        <div class="col-sm-3"><input type="text" name="trade_lic_renew_date" id="trade_lic_renew_date" value="" class="datepicker"/></div>
                                        <div class="col-sm-2">BAN Bank Reg Date</div>
                                        <div class="col-sm-5"><input type="text" name="ban_bank_reg_date" id="ban_bank_reg_date" value="" class="datepicker"/></div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">REX No</div>
                                        <div class="col-sm-3"><input type="text" name="rex_no" id="rex_no" value=""/></div>
                                        <div class="col-sm-2">REX Date</div>
                                        <div class="col-sm-5"><input type="text" name="rex_date" id="rex_date" value="" class="datepicker"/></div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                        </div>
                        <div title="Standards" style="padding:2px">
                        	<div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row middle">
                                            <div class="col-sm-2">GMT Reject ( % )</div>
                                            <div class="col-sm-1"><input type="text" name="gmt_reject_per" id="gmt_reject_per" value=""/></div>
                                            <div class="col-sm-2">Cut Panel Reject ( % ) </div>
                                            <div class="col-sm-1"><input type="text" name="cut_panel_reject_per" id="cut_panel_reject_per" value="" /></div>
                                            
                                            <div class="col-sm-2">Knit Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="knitting_capacity_qty" id="knitting_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Knit Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="knitting_capacity_amount" id="knitting_capacity_amount" value="" /></div>
                                        </div>

                                        <div class="row middle">
                                            <div class="col-sm-2">GMT Alter ( % ) </div>
                                            <div class="col-sm-1"><input type="text" name="gmt_alter_per" id="gmt_alter_per" value="" /></div>
                                            <div class="col-sm-2">Man Machine Ratio</div>
                                            <div class="col-sm-1"><input type="text" name="man_machine_ratio" id="man_machine_ratio" value=""/></div>
                                            
                                            <div class="col-sm-2">Dyeing Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="dyeing_capacity_qty" id="dyeing_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Dyeing Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="dyeing_capacity_amount" id="dyeing_capacity_amount" value="" /></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Earning Per Minute </div>
                                            <div class="col-sm-1"><input type="text" name="earning_per_minute" id="earning_per_minute" value="" /></div>
                                            <div class="col-sm-2">Earning Per Macine</div>
                                            <div class="col-sm-1"><input type="text" name="earning_per_machine" id="earning_per_machine" value=""/></div>

                                            <div class="col-sm-2">Fabric Finish Cap.Qty</div>
                                            <div class="col-sm-1"><input type="text" name="fabric_finish_capacity_qty" id="fabric_finish_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Fabric Finish Cap.Amt</div>
                                            <div class="col-sm-1"><input type="text" name="fabric_finish_capacity_amount" id="fabric_finish_capacity_amount" value="" /></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Earning Per Employee </div>
                                            <div class="col-sm-1"><input type="text" name="earning_per_employee" id="earning_per_employee" value="" /></div>
                                            <div class="col-sm-2">Knit Process Loss ( % )</div>
                                            <div class="col-sm-1"><input type="text" name="knit_process_loss_per" id="knit_process_loss_per" value=""/></div>

                                            <div class="col-sm-2">Aop Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="aop_capacity_qty" id="aop_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Aop Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="aop_capacity_amount" id="aop_capacity_amount" value="" /></div>
                                        </div>

                                        <div class="row middle">
                                            <div class="col-sm-2">Dye Process Loss ( % ) :</div>
                                            <div class="col-sm-1"><input type="text" name="dye_process_loss_per" id="dye_process_loss_per" value="" /></div>
                                            <div class="col-sm-2">On Time Delv ( % )v</div>
                                            <div class="col-sm-1"><input type="text" name="on_time_delv_per" id="on_time_delv_per" value=""/></div>

                                            <div class="col-sm-2">Screen Printing Cap.Qty</div>
                                            <div class="col-sm-1"><input type="text" name="screen_print_capacity_qty" id="screen_print_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Screen Printing Cap.Amt</div>
                                            <div class="col-sm-1"><input type="text" name="screen_print_capacity_amount" id="screen_print_capacity_amount" value="" /></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Sew Effic ( % ) </div>
                                            <div class="col-sm-1"><input type="text" name="sew_effic_per" id="sew_effic_per" value="" /></div>
                                            <div class="col-sm-2">Order To Ship ( % )</div>
                                            <div class="col-sm-1"><input type="text" name="order_to_ship_per" id="order_to_ship_per" value=""/></div>

                                            <div class="col-sm-2">Emb Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="embroidery_capacity_qty" id="embroidery_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Emb Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="embroidery_capacity_amount" id="embroidery_capacity_amount" value="" /></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Cut To Ship ( % ) </div>
                                            <div class="col-sm-1"><input type="text" name="cut_to_ship_per" id="cut_to_ship_per" value="" /></div>
                                            <div class="col-sm-2">First Pass Yield</div>
                                            <div class="col-sm-1"><input type="text" name="first_pass_yield" id="first_pass_yield" value=""/></div>
                                            <div class="col-sm-2">Cutting Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="cutting_capacity_qty" id="cutting_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Cutting Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="cutting_capacity_amount" id="cutting_capacity_amount" value="" /></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-2">Poly Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="poly_capacity_qty" id="poly_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Poly Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="poly_capacity_amount" id="poly_capacity_amount" value="" /></div>
                                            <div class="col-sm-2">Iron Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="iron_capacity_qty" id="iron_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Iron Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="iron_capacity_amount" id="iron_capacity_amount" value="" /></div>
                                        </div>
                                        <div class="row middle">      
                                            <div class="col-sm-2">Margin Of Erosion </div>
                                            <div class="col-sm-1"><input type="text" name="margin_of_erosion" id="margin_of_erosion" value="" /></div>

                                            <div class="col-sm-2">Cartoning Capacity Qty</div>
                                            <div class="col-sm-1"><input type="text" name="cartoning_capacity_qty" id="cartoning_capacity_qty" value="" /></div>
                                            <div class="col-sm-2">Cartoning Capacity Amount</div>
                                            <div class="col-sm-1"><input type="text" name="cartoning_capacity_amount" id="cartoning_capacity_amount" value="" /></div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                        </div>
                        <div title="Accounting Info" style="padding:2px">
                                    <code style="padding:5px 14px; border:none">
                                        <div class="row middle">
                                        <div class="col-sm-2">ACC Code Length</div>
                                        <div class="col-sm-3"><input type="text" name="acc_code_length" id="acc_code_length" value=""/></div>
                                        <div class="col-sm-2">Post In Previous Year :</div>
                                        <div class="col-sm-5">{!! Form::select('post_in_previous_yr', $yesno,'',array('id'=>'post_in_previous_yr')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-2">Auto Service Cost Alloc. ID</div>
                                        <div class="col-sm-3">{!! Form::select('auto_service_cost_alloca_id', $yesno,'',array('id'=>'auto_service_cost_alloca_id')) !!}</div>
                                        <div class="col-sm-2">Profit Center :</div>
                                        <div class="col-sm-5">{!! Form::select('profit_center', $yesno,'',array('id'=>'profit_center')) !!}</div>
                                        </div>

                                        <div class="row middle">
                                        <div class="col-sm-2">Book Keeping Currency</div>
                                        <div class="col-sm-3">{!! Form::select('book_keeping_currency', $currency,'',array('id'=>'book_keeping_currency')) !!}</div>
                                        </div>
                                    </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCompany.submit()">Save</a>
                     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('companyFrm')" >Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCompany.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'south',title:'List',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false" style="height:240px; padding:2px">
                <table id="companyTbl">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'Code'" width="100">Code</th>
                            <th data-options="field:'email'" width="100">Email</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCompanyController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
