<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Balance Sheet',footer:'#ft3'" style="padding:2px" id="balancesheetcontainer">
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="balancesheetFrm">
    <div id="container">
         <div id="body">
           <code>

                
                <div class="row">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsBalanceSheet.getYear(this.value)')) !!}</div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4 req-text">Year </div>
                    <div class="col-sm-8">
                        <select id="acc_year_id" name="acc_year_id" onchange="MsBalanceSheet.getPeriods(this.value)">
                        </select>
                    </div>
                </div>
                
                
                <div class="row middle">
                    <div class="col-sm-4">As On </div>
                    
                    <div class="col-sm-8">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d') ?>"  />
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
                
               

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBalanceSheet.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBalanceSheet.pdf()">Pdf</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBalanceSheet.resetForm()" >Reset</a>
    </div>

  </form>
</div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsBalanceSheetController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

