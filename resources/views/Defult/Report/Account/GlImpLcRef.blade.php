<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'General Ledger',footer:'#ft3'" style="padding:2px" id="glcontainer">
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
    <form id="glimplcrefFrm">
        <div id="container">
             <div id="body">
               <code> 
                    <div class="row">
                        <div class="col-sm-4 req-text">Company </div>
                        <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsGlImpLcRef.getYear(this.value)')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Year </div>
                        <div class="col-sm-8">
                            <select id="acc_year_id" name="acc_year_id" onchange="MsGlImpLcRef.getDateRange(this.value)">
                            </select>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Trans Date </div>
                        <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From"  />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To"    />
                        </div>
                    </div>
    
                    <div class="row middle">
                        <div class="col-sm-4">Import Lc Reference</div>
                        <div class="col-sm-8">
                            <input type="text" name="import_lc_ref_name" id="import_lc_ref_name"  ondblclick="MsGlImpLcRef.openImpLcRefWindow()" />
                            <input type="hidden" name="import_lc_id" id="import_lc_id"/>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Code </div>
                        <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="code_from" id="code_from" ondblclick="MsGlImpLcRef.codefromWindow()" placeholder="From:: Double Click"/>
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="code_to" id="code_to"   ondblclick="MsGlImpLcRef.codetoWindow()" placeholder="To:: Double Click" />
                        </div>
                    </div>
              </code>
           </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlImpLcRef.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlImpLcRef.pdf()">Pdf</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGlImpLcRef.resetForm()" >Reset</a>
        </div>
    
      </form>
    </div>
    </div>
                
    <div id="glimplcrefsearchWindow" class="easyui-window" title="Search Import Lc Reference" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
         <table id="glimplcrefsearchTbl" style="width:100%">
            <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'lc_no'" width="150">Import Lc No</th>
                        {{-- <th data-options="field:'code'" width="100">Code</th>
                        <th data-options="field:'vendor_code'" width="100">Vendor Code</th>
                        <th data-options="field:'contact_person'" width="100">Contact person</th>
                        <th data-options="field:'country_id'" width="100">Country</th> --}}
                        <th data-options="field:'supplier_id'" width="100">Supplier</th>
                       <th data-options="field:'pay_term_id'" width="100">Pay Term</th>
                       <th data-options="field:'company_id'" width="100">Importer</th>
                       <th data-options="field:'lc_type_id'" width="100">L/C Type</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#glimplcrefsearchft'" style="width:350px; padding:2px">
        <form id="glimplcrefsearchFrm">
            <div id="container">
                    <div id="body">
                        <code>
                            <div class="row middle" style="display:none">
                                <input type="hidden" name="id" id="id" value="" />
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Name </div>
                                <div class="col-sm-8"><input type="text" name="name" id="name" /></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Code </div>
                                <div class="col-sm-8">
                                    <input type="text" name="code" id="code" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Vendor Code </div>
                                <div class="col-sm-8"><input type="text" name="vendor_code" id="vendor_code" value=""/></div>
                           </div>
                            <div class="row middle">
                                <div class="col-sm-4">Contact Person  </div>
                                <div class="col-sm-8"><input type="text" name="contact_person" id="contact_person" value=""/></div>
                            </div>
                        </code>
                    </div>
                </div>
            <div id="glimplcrefsearchft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlImpLcRef.implcrefsearch()">Search</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('glimplcrefsearchFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlImpLcRef.closeImpLcRefWindow('html')">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlImpLcRef.closeImpLcRefWindow('pdf')">Pdf</a>
            </div>
            </form>
        </div>
        </div>
    </div>
    
    <div id="glimplcrefcodefromwindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table  border="1" id="glimplcrefcodefromTbl">
        <thead>
        <tr>
        <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
        <th width="200" data-options="field:'name',halign:'center'">Head Name</th>
        <th width="100" data-options="field:'root_id',halign:'center'">Report Head</th>
        <th width="100" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
        <th width="100" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
        <th width="80" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
        <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
        <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
        <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
        <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
        <th width="60" data-options="field:'status',halign:'center'">Status</th>
        </tr>
        </thead>
        </table>
    </div>
    
    <div id="glimplcrefcodetowindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table  border="1" id="glimplcrefcodetoTbl">
        <thead>
        <tr>
        <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
        <th width="200" data-options="field:'name',halign:'center'">Head Name</th>
        <th width="100" data-options="field:'root_id',halign:'center'">Report Head</th>
        <th width="100" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
        <th width="100" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
        <th width="80" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
        <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
        <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
        <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
        <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
        <th width="60" data-options="field:'status',halign:'center'">Status</th>
        </tr>
        </thead>
        </table>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsGlImpLcRefController.js"></script>
    <script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    </script>
    
    