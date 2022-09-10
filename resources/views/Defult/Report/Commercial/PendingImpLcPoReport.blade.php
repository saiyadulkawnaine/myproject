<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="pendingimplcporeportTbl" style="width:100%">
            <thead>
                <tr>
                    <th width="40">1</th>
                    <th width="60">2</th>
                    <th width="160">3</th>
                    <th width="60">4</th>
                    <th width="100">5</th>
                    <th width="70">6</th>
                    <th width="50">7</th>
                    <th width="80">8</th>
                    <th width="80">9</th>
                    <th width="80">10</th>
                    <th width="70">11</th>
                    <th width="100">12</th>
                    <th width="70">13</th>
                    <th width="80">14</th>
                    <th width="80">15</th>
                    <th width="80">16</th>
                    <th width="100">17</th>
                </tr>
                <tr>
                    <th data-options="field:'id'" width="40" align="center">ID</th>
                    <th data-options="field:'company_code'" width="60"  align="center">Importer</th>
                    <th data-options="field:'supplier_code'" width="160" align="center">Supplier</th>
                    <th data-options="field:'menu_id'" width="60" align="center">Item</th>
                    <th data-options="field:'pi_no'" width="100" align="center">PI No</th>
                    <th data-options="field:'pi_date'" width="70" align="center">PI Date</th>
                    <th data-options="field:'days_taken'" width="50" align="center">Waiting<br/>Days</th>
                    <th data-options="field:'po_no'" width="80" align="center">PO No</th>
                    <th data-options="field:'po_date'" width="80" align="center">PO Date</th>
                    <th data-options="field:'amount'" width="80" align="right">Amount</th>
                    <th data-options="field:'currency_id'" width="70" align="center">Currency</th>
                    <th data-options="field:'paymode'" width="100" align="center">Pay Mode</th>
                    <th data-options="field:'exch_rate'" width="70" align="right">Exch.Rate</th>
                    <th data-options="field:'delv_start_date'" width="80" align="center">Delivery Start</th>
                    <th data-options="field:'delv_end_date'" width="80" align="center">Delivery End</th>
                    <th data-options="field:'indentor_id'" width="80" align="center">Indentor</th>
                    <th data-options="field:'remarks'" width="100" align="center">Remarks</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Waiting for Import',footer:'#ft2'" style="width:300px; padding:2px">
        <form id="pendingimplcporeportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-5">Company</div>
                            <div class="col-sm-7">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Supplier</div>
                            <div class="col-sm-7">
                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Item</div>
                            <div class="col-sm-7">
                                {!! Form::select('menu_id', $menu,'',array('id'=>'menu_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">PI No</div>
                            <div class="col-sm-7">
                                <input type="text" name="pi_no" id="pi_no" value="" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">PI Date Range</div>
                            <div class="col-sm-3" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPendingImpLcPoReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPendingImpLcPoReport.resetForm('pendingimplcporeportFrm')">Reset</a>
            </div>
        </form>
    </div>
</div>

      
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsPendingImpLcPoReportController.js"></script>
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
    $('#pendingimplcporeportFrm [id="supplier_id"]').combobox();
</script>