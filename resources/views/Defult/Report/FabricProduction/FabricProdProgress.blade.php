<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Fabric Production Progress'" style="padding:2px">
        <table id="fabricprodprogressTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" width="60">1<br/>Company</th>
                    <th data-options="field:'produced_company_code',halign:'center'" width="60">2<br/>Sewing <br/> Unit</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="80">3<br/>GMT <br/>Buyer</th>
                    <th data-options="field:'style_ref',halign:'center'" width="80">4<br/>GMT <br/>Style</th>
                    <th data-options="field:'job_no',halign:'center'" width="80">5<br/>Job No</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80">6<br/>GMT Order</th>
                    <th data-options="field:'gmt_part_name',halign:'center'" width="70">7<br/>GMT <br/>Part</th>
                    <th data-options="field:'fabrication',halign:'center'" width="250">8<br/>Fabrication</th>
                    <th data-options="field:'fabric_color_name',halign:'center'" width="60">9<br/>Fabric<br/>Color</th>
                    <th data-options="field:'gsm_weight',halign:'center'"  width="70">10<br/>Req<br/>GSM</th>
                    <th data-options="field:'dia',halign:'center'" width="60">11<br/>Req<br/>Dia</th>

                    <th data-options="field:'fabricshape',halign:'center'" width="80">12<br/>Shape</th>
                    <th data-options="field:'fabriclook',halign:'center'" width="80">13<br/>Looks</th>
                    <th data-options="field:'kwo_qty',halign:'center'" width="80" align="right">14<br/>KWO Qty</th>
                   
                    <th data-options="field:'dwo_qty',halign:'center'" width="80" align="right">15<br/>DWO Qty</th>
                    <th data-options="field:'grey_fab',halign:'center'" width="80" align="right">16<br/>Req<br/>Qty</th>
                    <th data-options="field:'yisu_qty',halign:'center'" width="80" align="right">17<br/>Yarn<br/>Issued</th>
                    <th data-options="field:'yisu_bal',halign:'center'" width="100" align="right">18<br/>Yarn Issue<br/>Balance</th>
                    <th data-options="field:'prod_knit_qty',halign:'center'" width="100" align="right">19<br/>Knit Qty</th>

                    <th data-options="field:'knit_wip',halign:'center'" width="100" align="right">20<br/>Knit WIP</th>
                    <th data-options="field:'knit_bal',halign:'center'" width="100" align="right">21<br/>Knit Bal</th>

                    <th data-options="field:'knit_qty',halign:'center'" width="100" align="right">22<br/>Knit QC Qty</th>
 
                    
                    <th data-options="field:'knit_dlv_to_st_qty',halign:'center'" width="100" align="right">23<br/>Knit Dlv to Store</th>
                    <th data-options="field:'knit_rcv_by_st_qty',halign:'center'" width="100" align="right">24<br/>Knit Rcv By Store</th>
                    <th data-options="field:'rcv_by_batch_qty',halign:'center'" align="right" width="100">25<br/>Rcv By<br/>Batch</th>
                    <th data-options="field:'rcv_by_batch_bal',halign:'center'"  align ="right" width="100">26<br/>Rcv Bal</th>
                    <th data-options="field:'batch_qty',halign:'center'"  align ="right" width="100">27<br/>Batch<br/> Qty</th>

                    <th data-options="field:'batch_wip',halign:'center'" align="right" width="80">28<br/>Batch<br/>WIP</th>
                    <th data-options="field:'batch_bal',halign:'center'" align="right" width="100">29<br/>Batch<br/> Bal</th>
                    <th data-options="field:'load_qty',halign:'center'" align="right" width="100">30<br/>Load<br/> Qty</th>
                    <th data-options="field:'load_wip',halign:'center'" align="right" width="100">31<br/>Load<br/> WIP</th>
                    <th data-options="field:'load_bal',halign:'center'" align="right" width="100">32<br/>Load<br/> Bal</th>
                    <th data-options="field:'dyeing_qty',halign:'center'" align="right" width="100">33<br/>Dyeing<br/> Qty</th>
                    <th data-options="field:'dyeing_wip',halign:'center'" align="right" width="100">34<br/>Dyeing WIP</th>
                    <th data-options="field:'dyeing_bal',halign:'center'" align="right" width="100">35<br/>Dyeing <br/> Bal</th>

                    <th data-options="field:'aop_fab',halign:'center'" align="right" width="100">36<br/>AOP<br/>Req</th>
                    <th data-options="field:'aop_qc_qty',halign:'center'" align="right" width="80">37<br/>AOP<br/>Done</th>
                    <th data-options="field:'aop_bal',halign:'center'" align="right" width="100">38<br/>AOP<br/>Balance</th>
                    <th data-options="field:'fin_fab',halign:'center'" align="right" width="100">39<br/>Fin Fab<br/> Req</th>
                    <th data-options="field:'finish_qty',halign:'center'" align="right" width="100">40<br/>Finish<br/> Qty</th>
                    <th data-options="field:'finish_wip',halign:'center'" align="right" width="80">41<br/>Finish<br/> WIP</th>
                    <th data-options="field:'finish_bal',halign:'center'" align="right" width="100">42<br/>Finish<br/>Balance</th>
                    <th data-options="field:'finish_dlv_to_store_qty',halign:'center'" align="right" width="100">43<br/>Finish<br/>Dlv</th>
                    <th data-options="field:'finish_rcv_to_store_qty',halign:'center'" align="right" width="100">44<br/>Finish<br/>Rcv</th>
                    <th data-options="field:'finish_isu_to_cut_qty',halign:'center'" align="right" width="100">45<br/>Delv to<br/>Cut</th>
                    <th data-options="field:'finish_isu_to_cut_wip',halign:'center'" align="right" width="80"> 46<br/>Delv<br/> WIP</th>
                    <th data-options="field:'finish_isu_to_cut_bal',halign:'center'" align="right" width="80">47<br/>Delv<br/>Balance</th>

                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="fabricprodprogressFrm">
            <div id="container">
                <div id="body">
                   <code>
                       	<div class="row">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4"> Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div> 
                        <div class="row middle">
                            <div class="col-sm-4">Pro. Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                            </div>
                        </div>    
                        <div class="row middle">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" onDblClick="MsFabricProdProgress.openOrdStyleWindow()" placeholder=" Double Click" readonly />
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Dl.Marchant</div>
                            <div class="col-sm-8">
                                <input type="text" name="team_member_name" id="team_member_name" onDblClick="MsFabricProdProgress.openTeammemberDlmWindow()" placeholder=" Double Click" readonly/>
                                <input type="hidden" name="factory_merchant_id" id="factory_merchant_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Ship Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Status </div>
                            <div class="col-sm-8">{!! Form::select('order_status', $status,'1',array('id'=>'order_status')) !!}</div>
                        </div>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsFabricProdProgress.showExcel()">XL</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsFabricProdProgress.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFabricProdProgress.resetForm('fabricprodprogressFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onclick="MsFabricProdProgress.getsummery()">Summery</a> 
            </div>
      </form>
    </div>
    {{-- <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Summary'" style="width:980px; padding:2px">
        <div id="summeryTblFt" >
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onclick="MsFabricProdProgress.getsummery()">Show</a>
        </div>
        <div id="companybuyersummeryContainer">

        </div>
    </div> --}}
</div>
{{-- Summery --}}
<div id="companybuyersummeryWindow" class="easyui-window" title="Textile Summery Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="companybuyersummeryContainer" style="padding:2px;">

    </div>
</div>
{{-- Style Filtering Search Window --}}
<div id="ordstyleWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="ordstylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
                                <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                    <div class="col-sm-2 req-text">Style Ref. </div>
                                <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsFabricProdProgress.searchOrdStyleGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordstylesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'receivedate'" width="80">Receive Date</th>
                        <th data-options="field:'style_ref'" width="120">Style Refference</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                        <th data-options="field:'productdepartment'" width="80">Product Department</th>
                        <th data-options="field:'season'" width="80">Season</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#ordstyleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Dealing Merchant / Teammember Search Window --}}
<div id="teammemberDlmWindow" class="easyui-window" title="Dealing Merchant Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="teammemberdlmFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Team</div>
                                <div class="col-sm-8">
                                    {!! Form::select('team_id', $team,'',array('id'=>'team_id')) !!}
                                </div>
                            </div> 
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsFabricProdProgress.searchTeammemberDlmGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="teammemberdlmTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'team_name'" width="120">Team</th>
                        <th data-options="field:'dlm_name'" width="120">Dealing Marchant</th>
                        <th data-options="field:'type_id'" width="130px">Member Type</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#teammemberDlmWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsFabricProdProgressController.js"></script>
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
        $('#ordstylesearchFrm [id="buyer_id"]').combobox();
        $('#fabricprodprogressFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>
