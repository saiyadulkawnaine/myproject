<div class="easyui-layout animated rollIn"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Order Development'" style="padding:2px">
            <table id="samplerequirementTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'team_name',halign:'center'" width="60" formatter="MsSampleRequirement.formatteamleader">MKT Team<br/>Leader</th>

                        <th data-options="field:'team_member',halign:'center'" formatter="MsSampleRequirement.formatdlmerchant"  width="70">Dealing<br/> Merchant</th>

                        <th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>

                        <th data-options="field:'buying_agent_name',halign:'center'" formatter="MsSampleRequirement.formatbuyingAgent" width="70">Buying House</th>

                        <th data-options="field:'style_ref',halign:'center'" width="70" formatter="MsSampleRequirement.formatfile">Style <br>Tech Pack</th>

                        <th data-options="field:'receive_date',halign:'center'" width="80">Tech Pack<br/> Receive date</th>

                        <th data-options="field:'season_name',halign:'center'" width="70">Season</th>

                        <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>

                        <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsSampleRequirement.formatimage" width="30">Image</th>

                        <th data-options="field:'sample_name',halign:'center'" width="100">Sample Name</th>

                        <th data-options="field:'item_description',halign:'center'" width="100">Gmt Item </th>

                        <th data-options="field:'qty',halign:'center'" width="40" align="right">Qty</th>

                        <th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>

                        <th data-options="field:'fabric_description',halign:'center'" width="80">Fabric <br/> Description</th>

                        <th data-options="field:'yarn_description',halign:'center'" width="80">Yarn <br/> Description</th>

                        <th data-options="field:'fabric_cons',halign:'right'" width="50">Fabric <br/> Cons</th>

                        <th data-options="field:'pasedDays',halign:'center'" width="60" align="center"> Days <br/> Passed</th>

                        <th data-options="field:'delayDays',halign:'center'" width="60" align="center"> Delay <br/> Days</th>

                        <th data-options="field:'pattern_tna',halign:'center'" width="80" align="center"> Pattern <br/> TNA</th>

                        <th data-options="field:'sample_booking_tna',halign:'center'" width="80" align="center"> Sample Book <br/> TNA</th>

                        <th data-options="field:'yarn_inhouse_tna',halign:'center'" width="80" align="center"> Yarn In House<br/> TNA</th>

                        <th data-options="field:'yarn_dyeing_tna',halign:'center'" width="80" align="center"> Yarn Dyeing <br/> TNA</th>

                        <th data-options="field:'knitting_tna',halign:'center'" width="80" align="center"> Knitting  <br/> TNA</th>

                        <th data-options="field:'dyeing_tna',halign:'center'" width="80" align="center"> Dyeing  <br/> TNA</th>

                        <th data-options="field:'aop_tna',halign:'center'" width="80" align="center"> AOP <br/> TNA</th>

                        <th data-options="field:'finishing_tna',halign:'center'" width="80" align="center"> Finishing  <br/> TNA</th>

                        <th data-options="field:'cutting_tna',halign:'center'" width="80" align="center"> Cutting  <br/> TNA</th>

                        <th data-options="field:'print_emb_tna',halign:'center'" width="80" align="center"> Print <br/> TNA</th>

                        <th data-options="field:'emb_tna',halign:'center'" width="80" align="center"> Emb <br/> TNA</th>

                        <th data-options="field:'washing_tna',halign:'center'" width="80" align="center"> Washing <br/> TNA</th>

                        <th data-options="field:'trims_tna',halign:'center'" width="80" align="center"> Trims <br/> TNA</th>

                        <th data-options="field:'sewing_tna',halign:'center'" width="80" align="center"> Sewing  <br/> TNA</th>

                        <th data-options="field:'sub_tna',halign:'center',styler:MsSampleRequirement.stylebuyersub" width="80" align="center"> Buyer Submission <br/> TNA</th>
                        <th data-options="field:'app_tna',halign:'center'" width="80" align="center"> Approval <br/> TNA</th>
                        <th data-options="field:'remarks',halign:'center'" width="250" >Remarks</th>
                        <th data-options="field:'ship_date',halign:'center'" width="80"> Ship Date</th>
                        <th data-options="field:'fabric_instruction_id',halign:'center'" width="125">Fab. Instruction</th>
                        <th data-options="field:'rate',halign:'center'" width="60" align="right">Rate</th>
                        <th data-options="field:'amount',halign:'center'" width="60" align="right">Amount</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#ftsamplereport2'" style="width:350px; padding:2px">
            <form id="samplerequirementFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Team</div>
                            <div class="col-sm-8">
                            {!! Form::select('team_id', $team,'',array('id'=>'team_id')) !!}

                            </div>
                            </div>

                            <div class="row middle">
                            <div class="col-sm-4">Team Member</div>
                            <div class="col-sm-8">
                            {!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}

                            </div>
                            </div>

                            <div class="row middle">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" />

                            </div>
                            </div>

                            <div class="row middle">
                                <div class="col-sm-4">Est. Ship Date </div>
                                <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                </div>

                                <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                </div>
                            </div>

                            <div class="row middle">
                            <div class="col-sm-4">Sample Type</div>
                            <div class="col-sm-8">
                            {!! Form::select('orderstage_id', $orderstage,'',array('id'=>'orderstage_id')) !!}

                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="ftsamplereport2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSampleRequirement.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSampleRequirement.resetForm('samplereportFrm')" >Reset</a>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsSampleRequirementController.js"></script>
    <script>
    $(".datepicker" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
    });
</script>