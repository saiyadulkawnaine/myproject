<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="subinboundtabs">
    <div title="Start up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="subinbmarketingTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'production_area_id'" width="80">Prod.Area</th>
                            <th data-options="field:'team_name'" width="100">Team</th>
                            <th data-options="field:'teammember'" width="100">Marketing Member</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'refered_by'" width="100">Referred By</th>
                            <th data-options="field:'currency_code'" width="60">Currency</th>
                            <th data-options="field:'mkt_date'" width="80">MKT.Date</th>
                            <th data-options="field:'contact'" width="100">Contact</th>
                            <th data-options="field:'contact_no'" width="200">Contact Number</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Textile Marketing',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="subinbmarketingFrm">
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
                                    <div class="col-sm-4 req-text">Prod. Area</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Team</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('team_id', $team,'',array('id'=>'team_id','onchange'=>'MsSubInbMarketing.getTeamMember(this.value)')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Marketing Member</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text"> Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="mkt_date" id="mkt_date" class="datepicker" placeholder="YY-MM-DD" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Currency</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Referred By</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="refered_by" id="refered_by" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Contact Person</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" placeholder="Double Click Browse" ondblclick="MsSubInbMarketing.BuyerBranchWindowOpen()"  value="" readonly/>
                                        <input type="hidden" name="buyer_branch_id" id="buyer_branch_id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Email</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="email" id="email" value="" disabled/>         
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Designation</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="designation" id="designation"  value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Address</div>
                                    <div class="col-sm-8">
                                        <textarea name="address" id="address" value="" disabled></textarea>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbMarketing.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinbmarketingFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbMarketing.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Meeting" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="subinbeventTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'meeting_date'" width="80">Meeting Date</th>
                            <th data-options="field:'meeting_type_id'" width="80">Meeting Type</th>
                            <th data-options="field:'self_participants'" width="150">Our Participants</th>
                            <th data-options="field:'customer_participants'" width="80">Customer's Participants</th>
                            <th data-options="field:'next_meeting_date'" width="80">Next Meeting Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'General',footer:'#buyerdevelopmenteventFrmFt'" style="width: 450px; padding:2px">
                <form id="subinbeventFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Meeting Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="meeting_date" id="meeting_date" class="datepicker" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Meeting Type</div>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" id="id" value="" readonly  />
                                            <input type="hidden" name="sub_inb_marketing_id" id="sub_inb_marketing_id" value="" readonly  />
                                            {!! Form::select('meeting_type_id', $meetingtype,'',array('id'=>'meeting_type_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        </div>
                                </div>                      
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Our Participants</div>
                                    <div class="col-sm-8">
                                        <textarea name="self_participants" id="self_participants"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Customer's Participants</div>
                                    <div class="col-sm-8">
                                        <textarea name="customer_participants" id="customer_participants"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Next Meeting Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="next_meeting_date" id="next_meeting_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Minutes</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="buyerdevelopmenteventFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbEvent.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbEvent.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"   plain="true" id="delete"  zdy90 onClick="MsSubInbEvent.remove()">Delete</a>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Forcasting" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#serviceft'" style="width:450px; padding:2px">
                <form id="subinbserviceFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="sub_inb_marketing_id" id="sub_inb_marketing_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Color Range </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Forcasted Qty </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSubInbService.calculate()"  />
                                    </div>
                                    <div class="col-sm-4">
                                        {!! Form::select('uom_id',$uom,'8',array('id'=>'uom_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Forcasted Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSubInbService.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Est Delv Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="est_delv_date" id="est_delv_date" value="" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sample Req.Quantity </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sample_req_qty" id="sample_req_qty" value="" class="number integer" />
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
                    <div id="serviceft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbService.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinbserviceFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbService.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="subinbserviceTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'colorrange'" width="100">Color Range</th>
                            <th data-options="field:'qty'" width="100">Forcasted Qty</th>
                            <th data-options="field:'rate'" width="100">Forcasted Rate</th>
                            <th data-options="field:'amount'" width="100">Amount</th>
                            <th data-options="field:'est_delv_date'" width="100">Est Delv Date</th>
                            <th data-options="field:'uom_id'" width="100">Uom</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Image/File Upload" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Image',iconCls:'icon-more',footer:'#assetimageft'" style="width:450px; padding:2px">
                <form id="subinbimageFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="sub_inb_marketing_id" id="sub_inb_marketing_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Image</div>
                                    <div class="col-sm-8">
                                        <input type="file" id="file_src" name="file_src" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Files</div>
                                    <div class="col-sm-8">
                                        <input type="file" id="doc_file_src" name="doc_file_src" />
                                    </div>
                                </div>                              
                            </code>
                        </div>
                    </div>
                    <div id="assetimageft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbImage.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinbimageFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbImage.remove()">Delete</a>
                    </div>
                </form>   
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="subinbimageTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            {{-- <th data-options="field:'file_src'" width="80">Image</th> --}}
                            <th data-options="field:'file_src',halign:'center',align:'center'" formatter="MsSubInbImage.formatimage" width="100">Image</th>
                            <th data-options="field:'doc_file_src',halign:'center',align:'center'" formatter="MsSubInbImage.formatfile" width="100">Files</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-------==============Asset Image Pop Up===================-------------->
<div id="assetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="assetImageWindowoutput" src=""/>
</div>

{{--   Dyeing service order Window --}}
<div id="buyerbranchsearchWindow" class="easyui-window" title="Buyer Information" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#sodyeingposearchTblft'" style="padding:10px;">
            <table id="buyerbranchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'contact_person'" width="100">Contact Person</th>
                        <th data-options="field:'email'" width="100">Email</th>
                        <th data-options="field:'designation'" width="150">Designation</th>
                        <th data-options="field:'address'" width="250">Address</th>
                        <th data-options="field:'country'" width="100">Country</th>
                    </tr>
                </thead>
            </table>
            <div id="sodyeingposearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#buyerbranchsearchWindow').window('close')" style="border-radius:1px">Close</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Inbound/MsAllSubInboundController.js"></script>
<script>
    (function(){
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

    $('#subinbmarketingFrm [id="buyer_id"]').combobox();
})(jQuery);     
</script>
    