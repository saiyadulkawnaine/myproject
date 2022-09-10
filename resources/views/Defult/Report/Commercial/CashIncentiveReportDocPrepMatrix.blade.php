<h5 align="center">Document Preparation Window</h5>
<table border="0" style="border-style:dotted;"  cellspacing="3" cellpadding="3" align="center"  >
    <thead>
        <tr>
            <th width="250">Document Name</th>
            <th width="80" align="center">Arranged</th>
            <th width="300" align="center">Comment</th>
        </tr>
    </thead>

       
    
    <tbody>
            @foreach ($docprep as $item) 
        <tr>
            <td width="250">Export LC / Sales Contract</td>
            <td width="80" align="center">{{ $item->exp_lc_sc_arranged }}</td>
            <td width="300">{{ $item->exp_lc_sc_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Export Commercial Invoice</td>
            <td width="80" align="center">{{ $item->exp_invoice_arranged }}</td>
            <td width="300">{{ $item->exp_invoice_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Export Packing List</td>
            <td width="80" align="center">{{ $item->exp_packinglist_arranged }}</td>
            <td width="300">{{ $item->exp_packinglist_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Bill of Lading</td>
            <td width="80" align="center">{{ $item->bill_of_loading_arranged }}</td>
            <td width="300">{{ $item->bill_of_loading_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Bill of Entry - Export</td>
            <td width="80" align="center">{{ $item->exp_bill_of_entry_arranged }}</td>
            <td width="300">{{ $item->exp_bill_of_entry_remarks }}</td>
        </tr>
        <tr>
            <td width="250">EXP Form</td>
            <td width="80" align="center">{{ $item->exp_form_arranged }}</td>
            <td width="300">{{ $item->exp_form_remarks }}</td>
        </tr>
        <tr>
            <td width="250">GSP/CO</td>
            <td width="80" align="center">{{ $item->gsp_co_arranged }}</td>
            <td width="300">{{ $item->gsp_co_remarks }}</td>
        </tr>
        <tr>
            <td width="250">PRC - Bangladesh Format</td>
            <td width="80" align="center">{{ $item->prc_bd_format_arranged }}</td>
            <td width="300">{{ $item->prc_bd_format_remarks }}</td>
        </tr>
        <tr>
            <td width="250">UD Copy </td>
            <td width="80" align="center">{{ $item->ud_copy_arranged }}</td>
            <td width="300">{{ $item->ud_copy_remarks }}</td>
        </tr>
        <tr>
            <td width="250">BTB LC</td>
            <td width="80" align="center">{{ $item->btb_lc_arranged }}</td>
            <td width="300">{{ $item->btb_lc_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Import PI</td>
            <td width="80" align="center">{{ $item->import_pi_arranged }}</td>
            <td width="300">{{ $item->import_pi_remarks }}</td>
        </tr>
        <tr>
            <td width="250">GSP Facility Certificate from BTMA</td>
            <td width="80" align="center">{{ $item->gsp_certify_btma_arranged }}</td>
            <td width="300">{{ $item->gsp_certify_btma_remarks }}</td>
        </tr>
        <tr>
            <td width="250">VAT - 11 from Spinning Mill</td>
            <td width="80" align="center">{{ $item->vat_eleven_arranged }}</td>
            <td width="300">{{ $item->vat_eleven_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Yarn Receive Challan</td>
            <td width="80" align="center">{{ $item->rcv_yarn_challan_arranged }}</td>
            <td width="300">{{ $item->rcv_yarn_challan_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Import Commercial Invoice</td>
            <td width="80" align="center">{{ $item->imp_invoice_arranged }}</td>
            <td width="300">{{ $item->imp_invoice_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Import Packing List</td>
            <td width="80" align="center">{{ $item->imp_packing_list_arranged }}</td>
            <td width="300">{{ $item->imp_packing_list_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Beneficiary Certificate from Spinning Mill</td>
            <td width="80" align="center">{{ $item->bnf_certify_spin_mil_arranged }}</td>
            <td width="300">{{ $item->bnf_certify_spin_mil_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Certificate of Origin</td>
            <td width="80" align="center">{{ $item->certificate_of_origin_arranged }}</td>
            <td width="300">{{ $item->certificate_of_origin_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Alternate Cash Assistance - BGMEA</td>
            <td width="80" align="center">{{ $item->alt_cash_assist_bgmea_arranged }}</td>
            <td width="300">{{ $item->alt_cash_assist_bgmea_remarks }}</td>
        </tr>
        <tr>
            <td width="250">Cash Assistance Certificate from BTMA</td>
            <td width="80" align="center">{{ $item->cash_certify_btma_arranged }}</td>
            <td width="300">{{ $item->cash_certify_btma_remarks }}</td>
        </tr>
        @endforeach
    </tbody>
    
</table>