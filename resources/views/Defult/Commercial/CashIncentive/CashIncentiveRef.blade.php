<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="cashincentivetabs">
    <div title="Referrence Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="cashincentiverefTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'incentive_no'" width="70">Incentive ID</th>
                            <th data-options="field:'company_name'" width="100">Exporter</th>
                            <th data-options="field:'buyer_name'" width="150">Buyer</th>
                            <th data-options="field:'lc_sc_no'" width="160">LC/SC No</th>
                            <th data-options="field:'replaced_lc_sc_no'" width="200">Replaces LC/SC</th>
                            <th data-options="field:'bank_file_no'" width="100">Bank File No</th>
                            <th data-options="field:'region_id'" width="80">Region</th>
                            <th data-options="field:'claim_sub_date'" width="80">Claim Sub Date</th>
                            <th data-options="field:'remarks'" width="150">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
 
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="cashincentiverefFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" class="display:none;">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-5">Incentive ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="incentive_no" id="incentive_no" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">LC/SC No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsCashIncentiveRef.openCashIncentiveLcScWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bank File No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bank_file_no" id="bank_file_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Region</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('region_id', $region,'',array('id'=>'region_id')) !!}
                                    </div>
                                </div>                            
                                <div class="row middle">
                                    <div class="col-sm-5">Claim Sub. Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="claim_sub_date" id="claim_sub_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Internal File No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="file_no" id="file_no" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">File Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lc_sc_value" id="lc_sc_value" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buyer</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="buyer_id" id="buyer_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="currency_id" id="currency_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Lien Bank</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exporter_branch_name" id="exporter_branch_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Company</div>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="company_id" id="company_id" value=""/>
                                        <input type="text" name="company_name" id="company_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveRef.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('cashincentiverefFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveRef.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.khaForm()">খ-ফর্ম</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.getCop()">COP</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.forwardLetter()">F.Letter</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.declareLetter()">Declaration</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.netwgt()">Net.Wgt</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.getBtbCertificate()">BTB Certifiate</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveRef.undertaking()">Undertaking</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Doc Preparation" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Document Preparation',footer:'#ft3'" style="width: 480px; padding:2px">
                <form id="cashincentivedocprepFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                 <div class="row middle" style="display:none">
                                     <input type="hidden" name="id" id="id" value="" />
                                     <input type="hidden" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" />
                                 </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">
                                        <strong>Doccument Name</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong>Arranged</strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Comments</strong>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Export LC / Sales Contract</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('exp_lc_sc_arranged',
                                            $yesno,0,array('id'=>'exp_lc_sc_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="exp_lc_sc_remarks" id="exp_lc_sc_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Export Commercial Invoice</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('exp_invoice_arranged',
                                            $yesno,0,array('id'=>'exp_invoice_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="exp_invoice_remarks" id="exp_invoice_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Export Packing List</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('exp_packinglist_arranged',
                                            $yesno,0,array('id'=>'exp_packinglist_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="exp_packinglist_remarks" id="exp_packinglist_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Bill of Lading</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('bill_of_loading_arranged',
                                            $yesno,0,array('id'=>'bill_of_loading_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="bill_of_loading_remarks" id="bill_of_loading_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Bill of Entry - Export</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('exp_bill_of_entry_arranged',
                                            $yesno,0,array('id'=>'exp_bill_of_entry_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="exp_bill_of_entry_remarks" id="exp_bill_of_entry_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">EXP Form</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('exp_form_arranged',
                                            $yesno,0,array('id'=>'exp_form_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="exp_form_remarks" id="exp_form_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">GSP/CO</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('gsp_co_arranged',
                                            $yesno,0,array('id'=>'gsp_co_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="gsp_co_remarks" id="gsp_co_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">PRC - Bangladesh Format</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('prc_bd_format_arranged',
                                            $yesno,0,array('id'=>'prc_bd_format_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="prc_bd_format_remarks" id="prc_bd_format_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">UD Copy </div>
                                    <div class="col-sm-2">
                                        {!! Form::select('ud_copy_arranged',
                                            $yesno,0,array('id'=>'ud_copy_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="ud_copy_remarks" id="ud_copy_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">BTB LC</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('btb_lc_arranged',
                                            $yesno,0,array('id'=>'btb_lc_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="btb_lc_remarks" id="btb_lc_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Import PI</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('import_pi_arranged',
                                            $yesno,0,array('id'=>'import_pi_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="import_pi_remarks" id="import_pi_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">GSP Facility Certificate from BTMA</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('gsp_certify_btma_arranged',
                                            $yesno,0,array('id'=>'gsp_certify_btma_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="gsp_certify_btma_remarks" id="gsp_certify_btma_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">VAT - 11 from Spinning Mill</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('vat_eleven_arranged',
                                            $yesno,0,array('id'=>'vat_eleven_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="vat_eleven_remarks" id="vat_eleven_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Receive Challan</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('rcv_yarn_challan_arranged',
                                            $yesno,0,array('id'=>'rcv_yarn_challan_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="rcv_yarn_challan_remarks" id="rcv_yarn_challan_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Import Commercial Invoice</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('imp_invoice_arranged',
                                            $yesno,0,array('id'=>'imp_invoice_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="imp_invoice_remarks" id="imp_invoice_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Import Packing List</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('imp_packing_list_arranged',
                                            $yesno,0,array('id'=>'imp_packing_list_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="imp_packing_list_remarks" id="imp_packing_list_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Beneficiary Certificate from Spinning Mill</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('bnf_certify_spin_mil_arranged',
                                            $yesno,0,array('id'=>'bnf_certify_spin_mil_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="bnf_certify_spin_mil_remarks" id="bnf_certify_spin_mil_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Certificate of Origin</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('certificate_of_origin_arranged',
                                            $yesno,0,array('id'=>'certificate_of_origin_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="certificate_of_origin_remarks" id="certificate_of_origin_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Alternate Cash Assistance - BGMEA</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('alt_cash_assist_bgmea_arranged',
                                            $yesno,0,array('id'=>'alt_cash_assist_bgmea_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="alt_cash_assist_bgmea_remarks" id="alt_cash_assist_bgmea_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Cash Assistance Certificate from BTMA</div>
                                    <div class="col-sm-2">
                                        {!! Form::select('cash_certify_btma_arranged',
                                            $yesno,0,array('id'=>'cash_certify_btma_arranged')) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- <textarea name="exp_lc_sc_remarks" id="exp_lc_sc_remarks"></textarea> --}}
                                        <input type="text" name="cash_certify_btma_remarks" id="cash_certify_btma_remarks" value=""  placeholder="  Write"/>
                                    </div>
                                </div>   
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveDocPrep.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveDocPrep.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveDocPrep.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveDocPrep.pdf()">Forward Letter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Related Yarn BTB LC" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="cashincentiveyarnbtblcTbl" style="width:100%">
                    <thead>
                      <tr>
                         <th data-options="field:'id'" width="40">ID</th>
                         <th data-options="field:'lc_no'" width="90">SC / LC</th>
                         <th data-options="field:'supplier_name'" width="90">Supplier</th>
                         <th data-options="field:'item_description'" width="100">Yarn Description</th>
                         <th data-options="field:'lc_yarn_qty'" width="100" align="right">LC Yarn Qty</th>
                         <th data-options="field:'lc_yarn_amount'" width="100" align="right">LC Yarn Value</th>
                         <th data-options="field:'consumed_qty'" width="80" align="right">Consumed Qty</th>
                         <th data-options="field:'comsumed_amount'" width="100" align="right">Consumed Amount</th>
                         <th data-options="field:'prev_used_qty'" width="100" align="right">Previous Used Total Qty</th>                       
                         <th data-options="field:'balance_qty'" width="100" align="right">Balance Qty</th>                       
                      </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Related Back to Back LC',footer:'#ft4'" style="width: 350px; padding:2px">
                <form id="cashincentiveyarnbtblcFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" style="display:none">
                                    <input type="hidden" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">BTB LC No</div>
                                    <div class="col-sm-7">
                                         <input type="text" name="lc_no" id="lc_no" ondblclick="MsCashIncentiveYarnBtbLc.openBtbLcYarnWindow()" placeholder=" double click" readonly value="" />
                                         <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
                                    </div>
                                 </div>
                                <div class="row middle">
                                    <div class="col-sm-5">LC Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lc_date" id="lc_date" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Supplier</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="supplier_name" id="supplier_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Yarn Description</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="item_description" id="item_description" value="" ondblclick="MsCashIncentiveYarnBtbLc.openBtbPoYarnItemDescWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="po_yarn_item_id" id="po_yarn_item_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">LC Yarn Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lc_yarn_qty" id="lc_yarn_qty" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                    <div class="row middle">
                                    <div class="col-sm-5">Rate per Unit</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" class="number integer" onchange="MsCashIncentiveYarnBtbLc.calculateConsumedValue()" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">LC Yarn Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lc_yarn_amount" id="lc_yarn_amount" value="" class="integer number" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Consumed Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="consumed_qty" id="consumed_qty" class="integer number" value="" onchange="MsCashIncentiveYarnBtbLc.calculateConsumedValue()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Yarn Consumed Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="comsumed_amount" id="comsumed_amount" class="number integer" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Previous Used Total Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="prev_used_qty" id="prev_used_qty" class="number integer" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Balance Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="balance_qty" id="balance_qty" class="number integer" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div> 
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveYarnBtbLc.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveYarnBtbLc.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveYarnBtbLc.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Incentive Claim" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="cashincentiveclaimTbl" style="width:100%">
                    <thead>
                      <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'bank_bill_no'" width="70">Bank Bill No</th>
                        <th data-options="field:'invoice_no'" width="100" align="right">Invoice No</th>
                        <th data-options="field:'exch_rate'" width="60" align="right">Conversion Rate</th>
                        <th data-options="field:'invoice_qty'" width="80" align="right">Invoice Qty</th>
                        <th data-options="field:'invoice_amount'" width="80" align="right">Invoice Amount</th>
                        <th data-options="field:'net_wgt_exp_qty'" width="80" align="right">Net Wgt Export</th>
                        <th data-options="field:'realized_amount'" width="80" align="right">Realized Amount</th>
                        <th data-options="field:'cost_of_export'" width="80" align="right">Cost Of <br/>Export</th>
                        <th data-options="field:'freight'" width="60" align="right">Freight</th>                       
                        <th data-options="field:'net_realized_amount'" width="80" align="right">Net Realized <br/>Value</th>
                        <th data-options="field:'claim_amount'" width="80" align="right">Claim Amount</th>             
                        <th data-options="field:'local_cur_amount'" width="80" align="right">Local Currency<br/> Amount</th>
                        <th data-options="field:'total_net_wgt_qty'" width="80" align="right">Gross Net Wgt</th>                     
                        <th data-options="field:'knit_charge'" width="80" align="right">Knit Charge</th>                     
                        <th data-options="field:'dye_charge'" width="80" align="right">Dye Charge</th>                     
                      </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Cash Incentive Claims',footer:'#ft5'" style="width: 400px; padding:2px">
                <form id="cashincentiveclaimFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Invoice No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="invoice_no" id="invoice_no" ondblclick="MsCashIncentiveClaim.openDocInvoiceWindow()" placeholder=" double click" readonly value="" />
                                        <input type="hidden" name="exp_doc_sub_invoice_id" id="exp_doc_sub_invoice_id" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Bank Bill No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bank_bill_no" id="bank_bill_no" value="" readonly />
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Exp Form No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exp_form_no" id="exp_form_no" value="" readonly />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5">Exp Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exp_date" id="exp_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" readonly />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">BL Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bl_date" id="bl_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" readonly />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Invoice Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="invoice_qty" id="invoice_qty" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateInvoiceRate()" readonly />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Price/ Unit</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" value="" placeholder="  number" class="number integer"  readonly />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Invoice Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="invoice_amount" id="invoice_amount" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateInvoiceRate()"  readonly />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Net Wgt of Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="net_wgt_exp_qty" id="net_wgt_exp_qty" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateCostOfExport()" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Process Loss%</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="process_loss_per" id="process_loss_per" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateCostOfExport()" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Avg Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="avg_rate" id="avg_rate" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateCostOfExport()" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Knit&Dyeing Charge/kg</div>
                                    <div class="col-sm-7">
                                       <input type="text" name="knitting_charge_per_kg" id="knitting_charge_per_kg" style="width: 94px" class="number integer" placeholder="  knit/kg" onchange="MsCashIncentiveClaim.calculateCostOfExport()" />
                                       <input type="text" name="dyeing_charge_per_kg" id="dyeing_charge_per_kg" style="width: 94px" class="number integer" placeholder="  dyeing/kg" onchange="MsCashIncentiveClaim.calculateCostOfExport()" />
                                    </div>
                                 </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Realized Date</div>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="realized_year" id="realized_year" value="" />
                                        <input type="text" name="realized_date" id="realized_date" value="" placeholder="  YYYY-mm-dd" class="datepicker" />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Realized Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="realized_amount" id="realized_amount" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateNetRealized()" />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5">Freight</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="freight" id="freight" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateNetRealized()" />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Net Realized Value</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="net_realized_amount" id="net_realized_amount" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateNetRealized()"  readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Cost of Export</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="cost_of_export" id="cost_of_export" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateClaimAmount()" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Cost of Realization</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="cost_of_realization" id="cost_of_realization" value="" placeholder="  number" class="number integer" {{-- onchange="MsCashIncentiveClaim.calculateClaimAmount()" --}} readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Claim %</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="claim" id="claim" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateClaimAmount()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Claim Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="claim_amount" id="claim_amount" value="" placeholder="  number" class="number integer" onchange="MsCashIncentiveClaim.calculateLocalCurrencyAmount()" readonly  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Conv. Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exch_rate" id="exch_rate" value="" placeholder="   number" onchange="MsCashIncentiveClaim.calculateLocalCurrencyAmount()" class="number integer" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Local Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="local_cur_amount" id="local_cur_amount" value="" placeholder="  number" class="number integer" readonly />
                                    </div>
                                </div>    
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>   
                            </code>
                        </div>
                    </div>
                    <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveClaim.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveClaim.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveClaim.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Loan On Incentive Claim" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="cashincentiveloanTbl" style="width:600px">
                    <thead>
                      <tr>
                        <th data-options="field:'id'" width="40px">ID</th>
                        <th data-options="field:'advance_date'" width="70px">Advance Date</th>
                        <th data-options="field:'loan_ref_no'" width="100px" align="right">Loan Ref</th>
                        <th data-options="field:'local_cur_amount'" width="60px" align="right">Claim <br/>Amount-Tk</th>
                        <th data-options="field:'claim_amount'" width="80px" align="right">Claim <br/>Amount-USD</th>
                        <th data-options="field:'advance_per'" width="80px" align="right">Advance %</th>
                        <th data-options="field:'advance_amount_tk'" width="80px" align="right">Advance <br/>Amount-Tk</th>                       
                        <th data-options="field:'advance_amount_usd'" width="80px" align="right">Advance <br/>Amount-USD</th> 
                        <th data-options="field:'remarks'" width="60px" align="right">Remarks</th>                 
                      </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Cash Incentive Claims',footer:'#ft6'" style="width: 400px; padding:2px">
                <form id="cashincentiveloanFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                 <div class="row middle" style="display:none">
                                     <input type="hidden" name="id" id="id" value="" />
                                     <input type="hidden" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" />
                                 </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Advance Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_date" id="advance_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Loan Ref.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="loan_ref_no" id="loan_ref_no" value="" placeholder="  write" />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Claim-Tk</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="local_cur_amount" id="local_cur_amount" value="" class="number integer"  onchange="MsCashIncentiveLoan.calculateAdvance()" disabled />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Claim-USD</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="claim_amount" id="claim_amount" value=""  class="number integer" onchange="MsCashIncentiveLoan.calculateAdvance()"  disabled />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Advance %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_per" id="advance_per" value="" placeholder="  write" class="number integer" onchange="MsCashIncentiveLoan.calculateAdvance()"  />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Adv. Amount-TK</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_amount_tk" id="advance_amount_tk" value="" placeholder="  number" class="number integer" readonly  />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Adv. Amount-USD</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_amount_usd" id="advance_amount_usd" value="" placeholder="  number" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Interest Rate %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" placeholder="  write" class="number integer" />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Financial Bank</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exporter_branch_name" id="exporter_branch_name" value=""  disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>      
                            </code>
                        </div>
                    </div>
                    <div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveLoan.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveLoan.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveLoan.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="File Movement" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="cashincentivefileTbl" style="width:600px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40px">ID</th>
                            <th data-options="field:'audit_submit_date'" width="100px">Submitted Date<br/>for Audit</th>
                            <th data-options="field:'audit_report_date'" width="100px">Audit Report<br/>Received Date</th>
                            <th data-options="field:'bb_submit_date'" width="100px">Submitted Date<br/>to B.Bank</th>
                            <th data-options="field:'progress_bd_bank'" width="100px" align="right">Progress in<br/>Bangladesh Bank</th>
                            <th data-options="field:'bb_sanction_date'" width="100px">B.Bank <br/>Sanction Date</th>
                            <th data-options="field:'amount',halign:'center'" width="100px" align="right">Bank/ <br/>Audit Decided</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Cash Incentive Claims',footer:'#ftfile'" style="width:450px; padding:2px">
                <form id="cashincentivefileFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Submitted for Audit</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="audit_submit_date" id="audit_submit_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bank/Audit Decided Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" value=""  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Audit Queries</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="" id="" value="" placeholder="  Double Click" ondblclick="MsCashIncentiveFile.openQueryWindow()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Audit Report Received</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="audit_report_date" id="audit_report_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Submitted to B.Bank</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bb_submit_date" id="bb_submit_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Progress in Bangladesh Bank</div>
                                    <div class="col-sm-7">
                                        <textarea name="progress_bd_bank" id="progress_bd_bank"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">B.Bank Sanction Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bb_sanction_date" id="bb_sanction_date" value="" placeholder="  yyyy-mm-dd" class="datepicker"/>
                                    </div>
                                </div>     
                            </code>
                        </div>
                    </div>
                    <div id="ftfile" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveFile.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveFile.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveFile.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Export Lc Window --}}
 <div id="openCashIncentivelcscWindow" class="easyui-window" title="Cash Incentive Reference /Export Lc Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
     <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="cashincentivelcsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">LC No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_sc_no" id="lc_sc_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_sc_date" id="lc_sc_date" value="" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Beneficiary</div>
                                <div class="col-sm-8">
                                    {!! Form::select('beneficiary_id',$company,'',array('id'=>'beneficiary_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsCashIncentiveRef.searchIncentiveLcGrid()">Search</a>
                </p>
            </div>
         </div>
         <div data-options="region:'center'" style="padding:10px;">
             <table id="cashincentivelcsearchTbl" style="width:100%">
                 <thead>
                     <tr>
                         <th data-options="field:'id'" width="80">ID</th>
                         <th data-options="field:'replace_lc_sc_no'" width="200"> Related<br/>Contract Number</th>
                         <th data-options="field:'company_name'" width="100">Exporter</th>
                         <th data-options="field:'lc_sc_no'" width="140">LC/Contract No</th>
                         <th data-options="field:'lc_sc_value'" width="100" align="right">Contract Value</th>
                         <th data-options="field:'buyer_id'" width="180">Buyer</th>    
                         <th data-options="field:'lc_sc_nature_id'" width="100">Contract Nature</th>    
                         <th data-options="field:'lc_sc_date'" width="90">LC/Contract<br/> Date</th> 
                         <th data-options="field:'last_delivery_date'" width="90" align="right">Last Delivery<br/> Date</th>  
                         <th data-options="field:'sc_or_lc'" width="30">SC\LC</th>
                     </tr>
                 </thead>
             </table>
         </div>
         <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
             <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openCashIncentivelcscWindow').window('close')" style="width:80px">Close</a>
         </div>
     </div>
 </div>
{{-- Import Lc Window --}}
<div id="openbtbyarnlcwindow" class="easyui-window" title="Cash Return BTB Yarn Lc / Import LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="btbyarnlcsearchFrm">
                            <div class="row middle">
                               <div class="col-sm-4">Company</div>
                               <div class="col-sm-8">
                                  {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Supplier</div>
                               <div class="col-sm-8">
                                  {!! Form::select('supplier_id',
                                  $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Last Delivery Date</div>
                                <div class="col-sm-8">
                                   <input type="text" name="last_delilvery_date" id="last_delilvery_date" value="" class="datepicker" />
                                </div>
                             </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsCashIncentiveYarnBtbLc.searchBtbYarnLc()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="btbyarnlcsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'lc_no'" width="100">LC No</th>
                        <th data-options="field:'pay_term_id'" width="100">Pay Term</th>
                        <th data-options="field:'supplier_name'" width="100">Supplier</th>
                        <th data-options="field:'company_name'" width="100">Importer</th>
                        <th data-options="field:'lc_type_id '" width="100">L/C Type</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openbtbyarnlcwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Po Yarn Description Window --}}
<div id="openpoyarnitemdescwindow" class="easyui-window" title="Cash Return BTB Yarn Lc / PO Yarn Item Description Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="poyarnitemdescsearchFrm">
                            <div class="row middle">
                               <div class="col-sm-4">Company</div>
                               <div class="col-sm-8">
                                  {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Supplier</div>
                               <div class="col-sm-8">
                                  {!! Form::select('supplier_id',
                                  $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">PO No</div>
                               <div class="col-sm-8">
                                  <input type="text" name="po_no" id="po_no" value="" />
                               </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsCashIncentiveYarnBtbLc.searchBtbPoYarnItemDesc()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="poyarnitemdescsearchTbl" style="width:660px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40px">ID</th>
                        <th data-options="field:'item_description',halign:'center'" width="400">Yarn</th>
                        <th data-options="field:'lc_yarn_qty',halign:'center'" width="70" align="right">Qty</th>
                        <th data-options="field:'uom_code',halign:'center'" width="80">UOM</th>
                        <th data-options="field:'rate',halign:'center'" width="30" align="right">Rate</th>
                        <th data-options="field:'lc_yarn_amount',halign:'center'" width="70" align="right">Amount</th>
                        <th data-options="field:'pay_mode'" width="100">Pay Mode</th>
                        <th data-options="field:'supplier_name'" width="100">Supplier</th>
                        <th data-options="field:'company_name'" width="100">Importer</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openpoyarnitemdescwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Doc Submission Invoice Details Window --}}
<div id="opendocsubinvoicewindow" class="easyui-window" title="Cash Incentive Claim / Doc Submission Invoice Details Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="docinvoicesearchFrm">
                            <div class="row middle">
                               <div class="col-sm-4">Bank Bill No</div>
                               <div class="col-sm-8">
                                    <input type="text" name="bank_ref_bill_no" id="bank_ref_bill_no" value="" />
                               </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Invoice No</div>
                               <div class="col-sm-8">
                                  <input type="text" name="invoice_no" id="invoice_no" value="" />
                               </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsCashIncentiveClaim.searchDocInvoice()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="docinvoicesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'lc_sc_no'" width="80" align="right">LC/SC No</th>
                        <th data-options="field:'invoice_no'" width="80" align="right">Invoice No</th>
                        <th data-options="field:'bank_ref_bill_no'" width="100">Bank Bill No</th>
                        <th data-options="field:'invoice_value'" width="80" align="right">Gross Value</th>
                        <th data-options="field:'invoice_date'" width="80" align="right">Invoice Date</th>
                        <th data-options="field:'bl_cargo_no'" width="80" align="right">BL No</th>
                        <th data-options="field:'discount_amount'" width="80" align="right">Discount</th>
                        <th data-options="field:'bonus_amount'" width="80" align="right">Bonus</th>
                        <th data-options="field:'claim_amount'" width="80" align="right">Claim Adjustment</th>
                        <th data-options="field:'commission'" width="80" align="right">Commission</th>
                        <th data-options="field:'net_inv_value'" width="80" align="right">Net Invoice Value</th>
                        <th data-options="field:'file_no'" width="80" align="right">File</th>
                        <th data-options="field:'avg_rate'" width="80" align="right">Avg Yarn Rate</th>
                        <th data-options="field:'process_loss_per'" width="80" align="right">Process Loss%</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opendocsubinvoicewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Cash Incentive File Window --}}
<div id="filequerywindow" class="easyui-window" title="File Movement / Queries Details Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:450px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="cashincentivefilequeryFrm">
                            <div class="row">
                                <input type="hidden" name="cash_incentive_file_id" id="cash_incentive_file_id" value="" />
                                <input type="hidden" name="id" id="id" value="" />
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4"> Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="query_date" id="query_date" value="" placeholder="  yyyy-mm-dd" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                               <div class="col-sm-4">Queries</div>
                               <div class="col-sm-8">
                                  {{-- {!! Form::select('query_detail_id',
                                  $filequery,'',array('id'=>'query_detail_id')) !!} --}}
                                  <textarea name="query_remarks" id="query_remarks"></textarea>
                               </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveFileQuery.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveFileQuery.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveFileQuery.remove()">Delete</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="cashincentivefilequeryTbl" style="width:660px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40px">ID</th>
                        <th data-options="field:'query_date',halign:'center'" width="100">Date</th>
                        <th data-options="field:'query_remarks',halign:'center'" width="250" align="left">Query</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#filequerywindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/CashIncentive/MsAllCashIncentiveController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/CashIncentive/MsCashIncentiveFileQueryController.js"></script>
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
})(jQuery);

</script>
 