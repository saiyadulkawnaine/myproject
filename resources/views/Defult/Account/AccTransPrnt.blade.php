<style>
.threedotv:after {content: '\2807';font-size: 13px;color:white}


 /* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #FF0000;
  margin-top: 10%; /* 15% from the top and centered */
  margin-left: 350px;
  
  border: 1px solid #888;
  width: 50%; /* Could be more or less, depending on screen size */
  height: 70%;
  text-align: center;
}

/* The Close Button */
 
</style>
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
  	<img src="<?php echo url('/');?>/images/wrong.gif" width="750" height="400" style="margin-top: 3px">
    <p style="margin: auto; height: 100%;text-align: center;  font-weight: bold;font-size: 30px;color: #FFFFFF">Please communicate with administrator</p>
  </div>

</div>

<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utiltranstabs">
    <div title="Transection Parent" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Transection Table'" style="padding:2px">
                <table id="transchldTbl" style="width:100%">
                    <thead>
                       <tr>
                            <th data-options="field:'acc_chart_ctrl_head_name'" width="100">A/C Head</th> 
                            <th data-options="field:'code'" width="80">Code</th>
                            <th data-options="field:'bill_no'" width="100">Invoice/Bill No </th>
                            <th data-options="field:'amount_debit'" width="100" align="right">Debit</th>                           
                            <th data-options="field:'amount_credit'" width="100" align="right">Credit</th>
                            <th data-options="field:'exch_rate'" width="100" align="right">Exchange Rate </th>
                            <th data-options="field:'amount_foreign_debit'" width="100" align="right">Dr. Foreign Currency</th>
                            <th data-options="field:'amount_foreign_credit'" width="100" align="right">Cr.Foreign Currency </th>
                            <th data-options="field:'profitcenter_name'" width="100">Profit Center</th>
                            <th data-options="field:'location_name'" width="100">Location </th>
                            <th data-options="field:'division_name'" width="100">Division</th>
                            <th data-options="field:'department_name'" width="100">Department</th>
                            <th data-options="field:'section_name'" width="100">Section</th>
                            <th data-options="field:'loan_ref_no'" width="100">Loan Ref</th>
                            <th data-options="field:'chld_narration'" width="100">Narration</th>
                            <th data-options="field:'action'" formatter="MsTransChld.formatcancel" width="100"></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true" style="width:450px; padding:2px">
                <div class="easyui-layout" data-options="fit:true" id="accpnanel">
                    <div data-options="region:'north',border:true,title:'New Transection',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false" style="height:200px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="transprntFrm">
                                
                                <div class="row">
                                    <div class="col-sm-4">Transaction No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="trans_no" id="trans_no" value="" class="integer" onchange="MsTransPrnt.opentransPrntWindow()" />
                                         <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsTransPrnt.getYear(this.value)')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Accounting Year </div>
                                    <div class="col-sm-8">
                                        <select id="acc_year_id" name="acc_year_id">
                                            <option value="">-Select-</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Transaction Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="trans_date" id="trans_date" class="datepicker"/>
                                    </div>
                                </div>
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Transaction Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('trans_type_id', $journalType,'',array('id'=>'trans_type_id','onchange'=>'MsTransPrnt.transtypechange()')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Narration </div>
                                    <div class="col-sm-8"><textarea name="narration" id="narration" onchange="MsTransPrnt.setinchldnarration(this.value)"></textarea></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bank </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('bank_id', $bank,'',array('id'=>'bank_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Instrument No. </div>
                                    <div class="col-sm-8"><input type="text" name="instrument_no" id="instrument_no" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Pay To </div>
                                    <div class="col-sm-8"><input type="text" name="pay_to" id="pay_to" /></div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Place Date </div>
                                    <div class="col-sm-8"><input type="text" name="place_date" id="place_date" class="datepicker" onblur="MsTransChld.setfucusonchld()"/></div>
                                </div>
                                
                            </form>
                        </code>
                    </div>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Transection',footer:'#ft2'" style="padding:2px">
                <!---==========Form2===========------------>
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="transchldFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Account Head </div>
                                    <div class="col-sm-7" style="padding-right:0px">
                                        <input type="hidden" name="edit_index" id="edit_index" value="" />
                                        <input type="text" name="acc_chart_ctrl_head_id" id="acc_chart_ctrl_head_id" style="width: 100%; border-radius:2px" />
                                    </div>
                                    <div class="col-sm-1" style="padding-left:0px;text-align:center;" ><a class="threedotv" style="background-color:#0DAB33; padding-top:1px" href="javascript:void(0)" onclick="reloadhead()"></a></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">A/C Code </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" id="code" onchange="MsTransChld.codeChange()" class="integer" />
                                    </div>
                                </div>
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Debit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount_debit" id="amount_debit" class="integer number" onchange="MsTransChld.changeDebit()" />
                                    </div>
                                </div>

                                 <div class="row middle">
                                    <div class="col-sm-4">Credit </div>
                                     <div class="col-sm-8">
                                        <input type="text" name="amount_credit" id="amount_credit" class="integer number" onchange="MsTransChld.changeCredit()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Narration </div>
                                    <div class="col-sm-8">
                                        <textarea name="chld_narration" id="chld_narration"></textarea>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Ex. Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exch_rate" id="exch_rate" class="integer number" onchange="MsTransChld.changeExchRate()" />
                                    </div>
                                </div>


                                
                               
                                <div class="row middle">
                                    <div class="col-sm-4">Dr. FC </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount_foreign_debit" id="amount_foreign_debit" class="integer number" readonly  />
                                    </div>
                                    
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Cr. FC </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount_foreign_credit" id="amount_foreign_credit" class="integer number" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bill No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bill_no" id="bill_no"/>
                                    </div>
                                    
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Party </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="party_id" id="party_id" style="width: 100%; border-radius:2px"/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Employee </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="employee_id" id="employee_id" style="width: 100%; border-radius:2px"/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Loan Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="loan_ref_no" id="loan_ref_no" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Other Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="other_ref_no" id="other_ref_no" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Import LC Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="import_lc_ref_no" id="import_lc_ref_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Export LC Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="export_lc_ref_no" id="export_lc_ref_no" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Profit Center </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('profitcenter_id', $profitcenter,'',array('id'=>'profitcenter_id')) !!}
                                    </div>
                                </div>
                                <!--Cost Center---->
                                <div class="row middle">
                                    <div class="col-sm-4">Location </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="location_id" id="location_id" style="width: 100%; border-radius:2px"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Division </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="division_id" id="division_id" style="width: 100%; border-radius:2px"/>
                                        
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Department </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="department_id" id="department_id" style="width: 100%; border-radius:2px"/>
                                       
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Section </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="section_id" id="section_id" style="width: 100%; border-radius:2px"/>
                                    </div>
                                </div>

                               
                                
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTransChld.add()" accesskey="a"><u>A</u>dd</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTransChld.submit()" accesskey="s"><u>S</u>ave</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTransPrnt.resetForm()" accesskey="r"><u>R</u>eset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTransChldss.remove()" accesskey="d"><u>D</u>elete</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTransPrnt.opentransPrntWindow()" accesskey="e">S<u>e</u>arch</a>
                </div>
            </div>
            </div>
            </div>
        </div>
    </div>  
</div>

<div id="acctransprntsearchWindow" class="easyui-window" title="Search Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="transprntTbl" style="width:100%">
        <thead>
            <tr>  
                <th data-options="field:'print'" formatter="MsTransPrnt.formatjournalpdf" width="80"></th>
                <th data-options="field:'trans_no'" width="70">Trans. No</th>
                <th data-options="field:'company_id'" width="70">Company</th>
                <th data-options="field:'trans_type_id'" width="70">Trans. Type</th>
                <th data-options="field:'trans_date'" width="70">Trans. Date</th>
                <th data-options="field:'amount'" width="70" align="right">Amount</th>
                <th data-options="field:'is_locked'" width="70">Is Locked</th>
                <th data-options="field:'narration'" width="70">Narration</th>
                <th data-options="field:'user_name'" width="80">Created By</th>
                <th data-options="field:'created_at'" width="80">Created At</th>
                <th data-options="field:'updated_by'" width="80">Updated By</th>
                <th data-options="field:'updated_at'" width="80">Updated At</th>
                <th data-options="field:'id'" width="80">ID</th>
            </tr>
        </thead>
      </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ftacctransprntSearch'" style="width:350px; padding:2px">
    <form id="acctransprntsearchFrm">
        <div id="container">
        <div id="body">
        <code>
            <div class="row middle">
                <div class="col-sm-4">Trans. No </div>
                <div class="col-sm-8">
                <input type="text" name="trans_no" id="trans_no" value="" class="number integer" />
                <input type="hidden" name="id" id="id" value="" />
                </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Company </div>
            <div class="col-sm-8">
            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
            </div>
            </div>

            <div class="row middle">
                <div class="col-sm-4 req-text">Accounts Year </div>
                <div class="col-sm-8">
                {!! Form::select('acc_year_id', $accyear,'',array('id'=>'acc_year_id')) !!}
                </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Trans Date </div>
            <div class="col-sm-8">
            <input type="text" name="trans_date" id="trans_date" class="datepicker"/>
            </div>
            </div>


            <div class="row middle">
            <div class="col-sm-4 req-text">Trans Type </div>
            <div class="col-sm-8">
            {!! Form::select('trans_type_id', $journalType,'',array('id'=>'trans_type_id')) !!}
            </div>
            </div>
        </code>
        </div>
        </div>
        <div id="ftacctransprntSearch" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTransPrnt.transsearch()">Search</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('acctransprntsearchFrm')" >Reset</a>
        </div>

        </form>
    </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAllTransController.js"></script>


<script>
	var modal = document.getElementById("myModal");
	//modal.style.display = "block";

    function reloadhead()
    {
        
        $('#acc_chart_ctrl_head_id').combotree('reload',msApp.baseUrl()+"/accchartctrlhead/getroot");
    }

    

$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.-]/g, '')) {
       this.value = this.value.replace(/[^0-9\.-]/g, '');
    }
});
$('#acc_chart_ctrl_head_id').combotree({
    method:'get',
    url: msApp.baseUrl()+"/accchartctrlhead/getroot",
    editable:true,
	enableFiltering:true,
    lines:true,
    filter: function(q, node){
        if (node.text.toLowerCase()== q.toLowerCase()){
			
            $('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', node.id);
            var data={};
            data.id=node.id
            MsTransChld.getChart(data);
        }
		if(node.text.toLowerCase().indexOf(q.toLowerCase()) >= 0)
		{
		var t = $('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('tree');
		t.tree('select', node.target);
		}
        return node.text.toLowerCase().indexOf(q.toLowerCase()) >= 0;
    },
    onClick:function(node){
        var data={};
        data.id=node.id
        MsTransChld.getChart(data);
        var t = $('#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('textbox').focus();
    },
   
});

    $('#party_id').combobox({
        valueField:'id',
        textField:'name',
        onClick:function(node){
            var t = $('#transchldFrm [id="party_id"]').combotree('textbox').focus();
        }
    });
    $('#employee_id').combobox({
        valueField:'id',
        textField:'name',
        onClick:function(node){
            var t = $( '#transchldFrm [id="employee_id"]').combotree('textbox').focus();
        }
    });

    $('#location_id').combobox({
        valueField:'id',
        textField:'name',
        onClick:function(node){
            var t = $('#transchldFrm [id="location_id"]').combotree('textbox').focus();
        }
    });

    $('#division_id').combobox({
        valueField:'id',
        textField:'name',
        onClick:function(node){
            var t = $('#transchldFrm [id="division_id"]').combotree('textbox').focus();
        }
    });

    $('#department_id').combobox({
        valueField:'id',
        textField:'name',
        onClick:function(node){
            var t = $('#transchldFrm [id="department_id"]').combotree('textbox').focus();
        }
    });
    $('#section_id').combobox({
        valueField:'id',
        textField:'name',
        onClick:function(node){
            var t = $('#transchldFrm [id="section_id"]').combotree('textbox').focus();
        }
    });


  




$(document).ready(function() {
	
	  

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/acctranschld/getbillno?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });
    
    $('#bill_no').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'transchld',
        limit:1000,
        source: bloodhound,
        display: function(data) {
            return data.name  //Input value to be set when you select a suggestion. 
        },
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function(data) {
                return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
            }
        }
    });


    var importlc = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/acctranschld/getimportlc?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });
    
    $('#import_lc_ref_no').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'transchld',
        limit:1000,
        source: importlc,
        display: function(data) {
            return data.name  //Input value to be set when you select a suggestion. 
        },
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function(data) {
                return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
            }
        }
    });

    var exportlc = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/acctranschld/getexportlc?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });
    
    $('#export_lc_ref_no').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'transchld',
        limit:1000,
        source: exportlc,
        display: function(data) {
            return data.name  //Input value to be set when you select a suggestion. 
        },
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function(data) {
                return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
            }
        }
    });


    var loanref = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/acctranschld/getloanref?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });
    
    $('#loan_ref_no').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'transchld',
        limit:1000,
        source: loanref,
        display: function(data) {
            return data.name  //Input value to be set when you select a suggestion. 
        },
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function(data) {
                return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
            }
        }
    });


    var otherref = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/acctranschld/getotherref?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });
    
    $('#other_ref_no').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'transchld',
        limit:1000,
        source: otherref,
        display: function(data) {
            return data.name  //Input value to be set when you select a suggestion. 
        },
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function(data) {
                return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
            }
        }
    });


    $.extend($.fn.combotree.methods,{
        nav:function(jq,dir){
            return jq.each(function(){
                var opts = $(this).combotree('options');
                var t = $(this).combotree('tree');
                var nodes = t.tree('getChildren');
                if (!nodes.length){return}
                var node = t.tree('getSelected');
                if (!node){
                    t.tree('select', dir>0 ? nodes[0].target : nodes[nodes.length-1].target);
                } else {
                    var index = 0;
                    for(var i=0; i<nodes.length; i++){
                        if (nodes[i].target == node.target){
                            index = i;
                            break;
                        }
                    }
                    if (dir>0){
                        while (index < nodes.length-1){
                            index++;
                            if ($(nodes[index].target).is(':visible')){break}
                        }
                    } else {
                        while (index > 0){
                            index--;
                            if ($(nodes[index].target).is(':visible')){break}
                        }
                    }
					
					

                    t.tree('select',nodes[index].target);
					t.tree('scrollTo',nodes[index].target);//added by me
					
                }
                if (opts.selectOnNavigation){
                    var node = t.tree('getSelected');
                    $(node.target).trigger('click');
                    $(this).combotree('showPanel');
                }
            });
        }
    });

    $.extend($.fn.combotree.defaults.keyHandler,{
        up:function(){
            $(this).combotree('nav',-1);
        },
        down:function(){
            $(this).combotree('nav',1);
        },
        enter:function(){
            var t = $(this).combotree('tree');
            var node = t.tree('getSelected');
            if (node){
                $(node.target).trigger('click');
            }
            $(this).combotree('hidePanel');
            $( '#transchldFrm [id="acc_chart_ctrl_head_id"]').combotree('textbox').focus();
        }
    });
});



</script>

