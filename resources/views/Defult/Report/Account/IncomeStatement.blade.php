<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Income Statement',footer:'#ft3'" style="padding:2px" id="incomestatementcontainer">
    <!-- <table id="glTbl">
        <thead>
        <tr>
        <th data-options="field:'trans_type',halign:'center'" width="70" halign:'center'>Type</th>
        <th data-options="field:'trans_no',halign:'center'" width="70">#</th>
        <th data-options="field:'trans_date',halign:'center'" width="80">Date</th>
        <th data-options="field:'account',halign:'center'" width="100">Account</th>
        <th data-options="field:'cost_center',halign:'center'" width="80">Cost Center</th>
        <th data-options="field:'party_name',halign:'center'" width="80">Person/Item</th>

        <th data-options="field:'amount_debit',halign:'center'" width="100">Debit</th>
        <th data-options="field:'amount_credit',halign:'center'" width="80">Credit</th>
        <th data-options="field:'chld_narration',halign:'center'" width="80">Narration</th>
        </tr>
        </thead>
    </table> -->
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="incomestatementFrm">
    <div id="container">
         <div id="body">
           <code>

                
                <div class="row">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsIncomeStatement.getYear(this.value)')) !!}</div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4 req-text">Year </div>
                    <div class="col-sm-8">
                        <select id="acc_year_id" name="acc_year_id" onchange="MsIncomeStatement.getPeriods(this.value)">
                        </select>
                    </div>
                </div>
                
                
                <div class="row middle">
                    <div class="col-sm-4 req-text">Periods </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <select id="from_periods" name="from_periods">
                    </select>
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <select id="to_periods" name="to_periods">
                    </select>                    
                </div>
                </div>

               <div class="row middle">
                    <div class="col-sm-4 req-text">Level  </div>
                    <div class="col-sm-8">
                        <select id="level" name="level">
                            <option value="1">Sub Group</option>
                            <option value="2">Sub Sub Group</option>
                            <option value="3">Chart of Accounts</option>
                    </select>
                        
                    </div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Profit Center </div>
                <div class="col-sm-8">
                {!! Form::select('profitcenter_id', $profitcenter,'',array('id'=>'profitcenter_id')) !!}
                </div>
                </div>
                
               

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsIncomeStatement.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsIncomeStatement.pdf()">Pdf</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsIncomeStatement.resetForm()" >Reset</a>
    </div>

  </form>
</div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsIncomeStatementController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

