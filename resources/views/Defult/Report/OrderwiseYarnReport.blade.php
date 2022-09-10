<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Orderwise Yarn Progress'" style="padding:2px">
        <table id="orderwiseyarnreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" width="60">1<br/>Company</th>
                    <th data-options="field:'produced_company_code',halign:'center'" width="60">2<br/>Producing <br/> Unit</th>
                    <th data-options="field:'org_ship_date',halign:'center'" width="80">3<br/>Original <br/>Ship Date</th>
                    <th data-options="field:'ship_date',halign:'center'" width="80">4<br/>Ship Date</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="180">5<br/>Buyer</th>
                    <th data-options="field:'style_ref',halign:'center'" width="80" formatter="MsOrderwiseYarnReport.formatopfiles">6<br/>Style No</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80">7<br/>Order No</th>
                    <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsOrderwiseYarnReport.formatimage" width="50">8<br/>Image</th>
                    <th data-options="field:'qty',halign:'center'" width="100" align="right">9<br/>Order Qty<br/>(PCS)</th>
                    <th data-options="field:'amount',halign:'center'" width="100" align="right">10<br/>Selling Value</th>
                    <th data-options="field:'yarn_req_qty',halign:'center'" width="100" align="right">11<br/>Rq Qty</th>
                    <th data-options="field:'po_yarn_qty',halign:'center'" width="100" align="right">12<br/>PO Qty</th>
                    <th data-options="field:'po_yarn_bal_qty',halign:'center'" width="100" align="right">13<br/>Pending<br/>PO Qty</th>
                    <th data-options="field:'po_yarn_lc_qty',halign:'center'" width="100" align="right">14<br/>L/C Yarn Qty</th>
                    <th data-options="field:'po_yarn_lc_bal_qty',halign:'center'" width="100" align="right">15<br/>Pending<br/>L/C Yarn Qty</th>
                    <th data-options="field:'yarn_rcv_qty',halign:'center'" width="100" align="right">16<br/>Yarn Rcv Qty</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="orderwiseyarnreportFrm">
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
                            <div class="col-sm-4">Ship Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Original Ship Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="original_date_from" id="original_date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="original_date_to" id="original_date_to" class="datepicker"  placeholder="To" />
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
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsOrderwiseYarnReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsOrderwiseYarnReport.resetForm('orderwiseyarnreportFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsOrderwiseYarnReport.showExcel('orderwiseyarnreportTbl','orderwiseyarnreportTbl')">XLS</a>
            </div>
      </form>
    </div>
</div>



{{-- File Src --}}
<div id="opfilesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opfilesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsOrderwiseYarnReport.formatShowOpFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>

<div id="orderProgressImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="orderProgressImageWindowoutput" src=""/>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsOrderwiseYarnReportController.js"></script>
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
        $('#orderwiseyarnreportFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>