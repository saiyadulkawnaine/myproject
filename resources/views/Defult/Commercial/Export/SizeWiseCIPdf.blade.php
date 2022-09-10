<h3 align="center"><strong>COMMERCIAL INVOICE</strong></h3>
<table border="1" cellpadding="2">
    <tr>
        <td rowspan="3"><strong><u>Exporter/Shipper:</u></strong><br/>{{$rows->company_name}}<br/>{{$rows->company_address}}<br/>Email: lithe@lithegroup.com<br/>Post Code: {{$rows->post_code}}</td>
        <td><strong><u>Invoice No:</u></strong><br/>{{ $rows->invoice_no }}</td>
        <td><strong><u>Invoice Date:</u></strong><br/>{{ date('d.m.Y',strtotime($rows->invoice_date)) }}</td>
    </tr>
    <tr>
        <td><strong><u>{{ $rows->sc_or_lc_name }} No:</u></strong><br/>{{ $rows->lc_sc_no }}</td>
        <td><strong><u>{{ $rows->sc_or_lc_name }} Date:</u></strong><br/>{{ date('d.m.Y',strtotime($rows->lc_sc_date)) }}</td>
    </tr>
    <tr>
        <td>@if ($rows->buyers_bank)<strong><u>LC Issuing Bank:</u></strong><br/>{{ $rows->buyers_bank }}
        @endif</td>
        <td>@if ($rows->transfer_bank)<strong><u>Transferring Bank:</u></strong><br/>{!! nl2br(e($rows->transfer_bank)) !!}@endif<br/>@if ($rows->advise_bank)
            <strong><u>Advising Bank:</u></strong><br/>{!! nl2br(e($rows->advise_bank)) !!}
        @endif 
        </td>
    </tr>
    <tr>
        <td rowspan="2"><strong><u>For Account & Risk of Messers:</u></strong><br/>{{ $rows->buyer_name }}<br/>{{ $rows->buyer_address }}</td>
        <td><strong><u>EXP Form No:</u></strong><br/>{{ $rows->exp_form_no }}</td>
        <td><strong><u>EXP Date:</u></strong><br/>{{ $rows->exp_form_date }}</td>
    </tr>
    <tr>
        <td><strong><u>Port of Loading:</u></strong><br/>{{ $rows->port_of_loading }}</td>
        <td><strong><u>Port & Final Destination:</u></strong><br/>{{ $rows->port_of_discharge }} {{ $rows->final_destination }}</td>
    </tr>
   <tr>
        <td rowspan="2"><strong><u>Unto The Order Of:</u></strong><br/> @if($rows->advise_bank){!! nl2br(e($rows->advise_bank)) !!} @else {{ $rows->bank_name }}<br/>{{ $rows->branch_name }}<br/>{{ $rows->bank_address }}<br/>Swift Code: {{ $rows->swift_code }}<br/>A/C No: {{ $rows->account_no }}@endif 
        </td>
        <td><strong><u>Shipment Mode:</u></strong><br/>{{ $rows->ship_mode_id }}</td>
        <td><strong><u>Terms of Payment:</u></strong><br/>{{ $rows->tenor }} Days {{ $rows->pay_term_id }}</td>
    </tr>
    <tr> 
        <td><strong><u>Bill of Lading No :</u><br/>{{ $rows->bl_cargo_no }}<br/></strong><u><strong>BL Date :</strong></u><br/>{{ $rows->bl_cargo_date }}</td>
        <td><strong><u>Vessel No:</u></strong><br/> {{ $rows->feeder_vessel }}</td>
    </tr>
    <tr>
        <td>@if ($rows->notifying_party_name)<strong><u>Notify Party :</u></strong><br/>{{ $rows->notifying_party_name }}<br/>{{ $rows->notify_branch_address }}@endif<br/>@if ($rows->second_notifying_party_name)<strong><u>Second Notify Party :</u></strong><br/>{{ $rows->second_notifying_party_name }}<br/>{{ $rows->second_notify_branch_address }}
            @endif<br/>@if ($rows->consignee_name)<strong><u>Consignee:</u></strong><br/>{{ $rows->consignee_name }}<br/>{{ $rows->consignee_branch_address }}
            @endif
        </td>

        <td><strong>Other Info</strong><br/><strong>Country of Origin:</strong> BANGLADESH<br/><strong>Incoterms & Places: {{ $rows->incoterm_id }}<br/>{{ $rows->incoterm_place }}</strong><br/><strong>ETD(CTG): {{ $rows->etd_port }}</strong><br/><strong>ETA: {{ $rows->eta_port }}</strong><br/>
        </td>
        <td><strong>VAT Reg No:</strong> {{ $rows->vat_number }}<br/><strong>ERC No: {{ $rows->erc_no }}</strong>
        </td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <td rowspan="2" align="center"><strong>MARKS & NUMBERS OF PKGS</strong></td>
            <td colspan="7" align="center"><strong>DESCRIPTION OF GOODS</strong></td>
        </tr>
        <tr>
            <td align="center"><strong>STYLE NO/<br/>ARTICLE NO</strong></td>
            <td align="center"><strong>PO NO/<br/>ORDER NO</strong></td>
            <td align="center"><strong>GARMENTS ITEM {{-- & FABRICATION --}}</strong></td>
            <td align="center"><strong>SIZE</strong></td>
            <td align="center"><strong>QUANTITY IN PCS<br/>/SET</strong></td>
            <td align="center"><strong>PRICE PER PCS/<br/>SET</strong></td>
            <td align="center"><strong>AMOUNT IN USD</strong></td>
        </tr>
    </thead>
    <?php
        $total_qty_pcs=0;
        $totalInvoiceAmount=0;
        $i=0;
    ?> 
    <tbody>
        <tr>
            <td>{!! nl2br(e($rows->shipping_mark)) !!}</td>
            <td colspan="7" style="margin-left: 0px">@foreach($data as $item)<table border="1" cellspacing="0" cellpadding="2" style="margin-left: 0px">
                        <tr>
                            <td style="margin-left: 0px">{{ $item->style_ref }}<br/>{{ $item->article_no }}</td>
                            <td style="margin-left: 0px">{{ $item->sale_order_no }}</td>
                            <td style="margin-left: 0px">{{ $item->fabrication }}</td>
                            <td align="center">{{ $item->size_name }}</td>
                            <td style="margin-left: 0px" align="right">{{ number_format($item->invoice_qty,0) }}</td>
                            <td style="margin-left: 0px" align="right">{{ number_format($item->invoice_rate,4) }}</td>
                            <td style="margin-left: 0px" align="right">{{ number_format($item->invoice_amount,2) }}</td>
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
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right">{{ number_format($total_qty_pcs,0) }}</td>
            <td></td>
            <td align="right">{{ number_format($totalInvoiceAmount,2) }}</td>
        </tr>
    </tfoot>
</table>
<p></p>
<p><strong>IN WORDS:{{ $rows->inword }}</strong></p>
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
        <td align="right" width="90"><strong>{{ number_format($rows->net_wgt_exp_qty,0) }}</strong></td>
        <td align="left" width="60"><strong>KGS</strong></td>
    </tr>
    <tr>
        <td align="left" width="130"><strong>TOTAL GR.WEIGHT</strong></td>
        <td align="right" width="90"><strong>{{ number_format($rows->gross_wgt_exp_qty,0) }}</strong></td>
        <td align="left" width="60"><strong>KGS</strong></td>
    </tr>
    <tr>
        <td align="left" width="130"><strong>TOTAL VOL</strong></td>
        <td align="right" width="90"><strong>{{ number_format($rows->cbm,2) }}</strong></td>
        <td align="left" width="60"><strong>CBM</strong></td>
    </tr>
</table>
<p></p>
<p></p>
@if ($rows->rex_declaration)
<table width="80%" border="1" cellpadding="2">
    <tr>
        <th><strong>{{ $rows->rex_declaration }}</strong>
        </th>
    </tr>
</table>   
@endif