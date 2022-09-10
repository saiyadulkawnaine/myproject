<h3 align="center"><strong>COMMERCIAL INVOICE</strong></h3>
<table border="1" cellpadding="2">
    <tr>
        <td rowspan="3"><strong><u>Exporter/Shipper</u></strong><br/>{{$rows->company_name}}<br/>{{$rows->company_address}}<br/>Email: lithe@lithegroup.com</td>
        <td><strong>Invoice No</strong><br/>{{ $rows->invoice_no }}</td>
        <td><strong>Invoice Date</strong><br/>{{ date('d.m.Y',strtotime($rows->invoice_date)) }}</td>
    </tr>
    <tr>
        <td><strong>{{ $rows->sc_or_lc_name }}</strong><br/>{{ $rows->lc_sc_no }}</td>
        <td><strong>{{ $rows->sc_or_lc_name }} Date</strong><br/>{{ date('d.m.Y',strtotime($rows->lc_sc_date)) }}</td>
    </tr>
    <tr>
        <td><strong>LC Issuing Bank</strong><br/>{{ $rows->buyers_bank }}</td>
        <td><strong>Transferring Bank</strong><br/>{!! nl2br(e($rows->transfer_bank)) !!}<br/><strong>Advising Bank</strong><br/>{!! nl2br(e($rows->advise_bank)) !!}</td>
    </tr>
    <tr>
        <td rowspan="2"><strong>For Account & Risk of Messers</strong><br/>{{ $rows->buyer_name }}<br/>{{ $rows->buyer_address }}</td>
        <td><strong>EXP Form No</strong><br/>{{ $rows->exp_form_no }}</td>
        <td><strong>EXP Date</strong><br/>{{ $rows->exp_form_date }}</td>
    </tr>
    <tr>
        <td><strong>Port of Loading</strong><br/>{{ $rows->port_of_loading }}</td>
        <td><strong>Port & Final Destination</strong><br/>{{ $rows->port_of_discharge }}, {{ $rows->final_destination }}</td>
    </tr>
    <tr>
        <td><strong>Unto The Order Of</strong><br/>{{ $rows->bank_name }}<br/>{{ $rows->branch_name }}<br/>{{ $rows->bank_address }}<br/>Swift Code: {{ $rows->swift_code }}<br/>A/C No: {{ $rows->account_no }}
        </td>
        <td><strong>Shipment Mode</strong><br/>{{ $rows->ship_mode_id }}</td>
        <td><strong>Terms of Payment</strong><br/>{{ $rows->tenor }} Days {{ $rows->pay_term_id }}</td>
    </tr>
   
    <tr>
        <td><strong>Notify Party </strong><br/>{{ $rows->notifying_party_name }}<br/>{{ $rows->notify_branch_address }}<br/><strong>Consignee:</strong><br/>{{ $rows->consignee_name }}<br/>{{ $rows->consignee_branch_address }}</td>
        <td><strong>Other Info</strong><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<strong>Country of Origin:</strong> BANGLADESH<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<strong>Incoterms & Places: {{ $rows->incoterm_id }} {{ $rows->incoterm_place }}</strong><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<strong>ETD(CTG): {{ $rows->etd_port }}</strong><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<strong>ETA(HAM): {{ $rows->eta_port }}</strong><br/>
        </td>
        <td><strong>ERC NO: {{ $rows->erc_no }}</strong><br/>
            <strong>VAT NO: {{ $rows->vat_number }}</strong><br/>
            <strong>EPB REG NO: {{ $rows->epb_reg_no }}</strong>
        </td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <td rowspan="2" align="center" width="147px"><strong>MARKS & NUMBERS OF PKGS</strong></td>
            <td colspan="6" align="center" width="490px"><strong>DESCRIPTION OF GOODS</strong></td>
        </tr>
        <tr>
            <td align="center" width="80px"><strong>STYLE NO/<br/>ARTICLE NO</strong></td>
            <td align="center" width="80px"><strong>PO NO/<br/>ORDER NO</strong></td>
            <td align="center" width="120px"><strong>GARMENTS ITEM & FABRICATION</strong></td>
            <td align="center" width="70px"><strong>QUANTITY IN<br/> SET/PCS</strong></td>
            <td align="center" width="70px"><strong>PRICE PER<br/> SET/PCS</strong></td>
            <td align="center" width="70px"><strong>AMOUNT<br/> IN USD</strong></td>
        </tr>
    </thead>
    <?php
        $total_qty_pcs=0;
        $totalInvoiceAmount=0;
        $i=0;
    ?> 
    <tbody>
        <tr>
            <td width="147px">{!! nl2br(e($rows->shipping_mark)) !!}</td>
            <td colspan="6" style="margin-left: 0px" width="490px">@foreach($data as $item)<table border="1" cellspacing="0" cellpadding="2" style="margin-left: 0px">
                    <tr>
                        <td style="margin-left: 0px" width="80px">{{ $item->style_ref }}</td>
                        <td style="margin-left: 0px" width="80px">{{ $item->sale_order_no }}</td>
                        <td style="margin-left: 0px" width="120px">{{ $item->commodity }}</td>
                        <td style="margin-left: 0px" align="right" width="70px">{{ number_format($item->invoice_qty,0) }}</td>
                        <td style="margin-left: 0px" align="right" width="70px">{{ number_format($item->invoice_rate,4) }}</td>
                        <td style="margin-left: 0px" align="right" width="70px">{{ number_format($item->invoice_amount,2) }}</td>
                    </tr>
                </table>
                <?php
                    $total_qty_pcs+=$item->invoice_qty;
                    $totalInvoiceAmount+=$item->invoice_amount;
                ?>
                @endforeach
            </td>
        </tr>
    </tbody>
    <tfoot>
        <?php 
            $netInvoiceValue=$totalInvoiceAmount+$rows->up_charge_amount-($rows->discount_amount+$rows->bonus_amount+$rows->claim_amount+$rows->commission);
        ?>
        <tr>
            <td colspan="4" width="427px"></td>
            <td align="right" width="70px">{{ number_format($total_qty_pcs,0) }} PCS</td>
            <td width="70px"></td>
            <td align="right" width="70px">{{ $rows->currency_code }}&nbsp;{{ number_format($totalInvoiceAmount,2) }}</td>
        </tr>
        <tr>
            <td colspan="6" align="right" width="567px"><strong>Say {{ $rows->inword }}</strong></td>
            <td align="right" width="70px">{{ number_format($netInvoiceValue,2) }}</td>  
        </tr>
    </tfoot>
</table>
<p></p>
<table border="1" cellpadding="2">
    <tr>
        <td align="left" width="130"><strong>TOTAL QTY</strong></td>
        <td align="right" width="90"><strong>{{ number_format($total_qty_pcs,0) }}</strong></td>
        <td align="left" width="60"><strong>PCS</strong></td>
    </tr>
    <tr>
        <td align="left" width="130"><strong>TOTAL CTN</strong></td>
        <td align="right" width="90"><strong>{{ number_format($rows->total_ctn_qty,0) }}</strong></td>
        <td align="left" width="60"><strong>CTNS</strong></td>
    </tr>
    <tr>
        <td align="left" width="130"><strong>TOTAL NET WEIGHT</strong></td>
        <td align="right" width="90"><strong>{{ number_format($rows->net_wgt_exp_qty,2) }}</strong></td>
        <td align="left" width="60"><strong>KGS</strong></td>
    </tr>
    <tr>
        <td align="left" width="130"><strong>TOTAL GR.WEIGHT</strong></td>
        <td align="right" width="90"><strong>{{ number_format($rows->gross_wgt_exp_qty,2) }}</strong></td>
        <td align="left" width="60"><strong>KGS</strong></td>
    </tr>
    <tr>
        <td align="left" width="130"><strong>TOTAL VOL</strong></td>
        <td align="right" width="90"><strong>{{ number_format($rows->cbm,2) }}</strong></td>
        <td align="left" width="60"><strong>CBM</strong></td>
    </tr>
</table>
<p></p>
<table width="80%" border="1" cellpadding="2">
    <tr>
        <th><strong>{{ $rows->rex_declaration }}</strong>
        </th>
    </tr>
</table>