@foreach ($data as $rows)
    

<h3 align="center">Beneficiary Company: {{ $rows->company_code }}<br>Sewing Company: {{ $rows->produced_company_code }}</h3>
<p></p>
<p align="center">Buyer: {{ $rows->buyer_name }}; Buying House: {{ $rows->buying_agent_name }}; Latest Ship Date: {{ $rows->ship_date }}</p>
<p></p>
<table style="margin: 10px auto" border="0">
    <tr>
        <td>Style : {{ $rows->style_ref }}</td>
        <td>Marketer : {{ $rows->team_name }}</td>
        <td>Contract / LC No : {{ $exp_lc_sc_no }}</td>
        <td rowspan="5">
            @if ($rows->flie_src)
                <?php
                $impath=url('/')."/images/".$rows->flie_src;
                ?>
                <img src="<?php echo $impath;?>" height="100" width="100">
            @endif
        </td>
    </tr>
    <tr>
        <td>Order No : {{ $rows->sale_order_no }}</td>
        <td>Dealing Merchant : {{ $rows->team_member_name }}</td>
        <td>File No : {{ $file_no }}</td>
    </tr>
    <tr>
        <td>Original Ship date : {{ $rows->org_ship_date }}</td>
        <td>Phone : {{ $rows->contact }}</td>
        <td>Contract/LC Value : {{ $lc_sc_value }}</td>
    </tr>
    <tr>
        <td>Lead Time : {{ $rows->lead_time }}</td>
        <td></td>
        <td>Country : {{ $country_name }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<p align="right">SMV: {{ $rows->smv }}  Tgt Effi:{{ $rows->sewing_effi_per }}%</p>
<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td width="30" align="center"><strong>SL</strong></td>
        <td width="100" align="center"><strong>Particulars</strong></td>
        <td width="100" align="center"><strong>Pcs/Kg</strong></td>
        <td width="100" align="center"><strong>TNA Close</strong></td>
        <td width="100" align="center"><strong>Actual Close</strong></td>
        <td width="100" align="center"><strong>Early By</strong></td>
        <td width="100" align="center"><strong>Late By</strong></td>
    </tr>
    <tr>
        <td width="30" align="center">1</td>
        <td width="100" align="left">Garment Qty</td>
        <td width="100" align="right">{{ $rows->qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">2</td>
        <td width="100" align="left">Ship Out</td>
        <td width="100" align="right">{{ $rows->ship_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">3</td>
        <td width="100" align="left">Balance</td>
        <td width="100" align="right">{{ $rows->balance_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">4</td>
        <td width="100" align="left">Yarn Req</td>
        <td width="100" align="right">{{ $rows->yarn_req }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">5</td>
        <td width="100" align="left">Yarn LC Qty</td>
        <td width="100" align="right">{{ $rows->poyarnlc_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">6</td>
        <td width="100" align="left">Yarn Receive</td>
        <td width="100" align="right">{{ $rows->yarn_rcv }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">7</td>
        <td width="100" align="left">Yarn Issued </td>
        <td width="100" align="right">{{ $rows->yarn_isu_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">8</td>
        <td width="100" align="left">Knitted Qty</td>
        <td width="100" align="right">{{ $rows->knit_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">9</td>
        <td width="100" align="left">Batch Qty</td>
        <td width="100" align="right">{{ $rows->batch_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">10</td>
        <td width="100" align="left">Dyeing Qty</td>
        <td width="100" align="right">{{ $rows->dyeing_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">11</td>
        <td width="100" align="left">Fin Fab. Req</td>
        <td width="100" align="right">{{ $rows->fin_fab_req }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">12</td>
        <td width="100" align="left">Finishing Done</td>
        <td width="100" align="right">{{ $rows->finish_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">13</td>
        <td width="100" align="left">Cut Qty</td>
        <td width="100" align="right">{{ $rows->cut_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">14</td>
        <td width="100" align="left">Cut %</td>
        <td width="100" align="right">{{ number_format(($rows->cut_qty/$rows->qty)*100,2) }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">15</td>
        <td width="100" align="left">Screen Print Req</td>
        <td width="100" align="right">{{ $rows->req_scr_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">16</td>
        <td width="100" align="left">Screen Print Done</td>
        <td width="100" align="right">{{ $rows->rcv_scr_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">17</td>
        <td width="100" align="left">Emb Req</td>
        <td width="100" align="right">{{ $rows->req_emb_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">18</td>
        <td width="100" align="left">Emb Done</td>
        <td width="100" align="right">{{ $rows->rcv_emb_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">19</td>
        <td width="100" align="left">Line Input Done</td>
        <td width="100" align="right">{{ $rows->sew_line_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">20</td>
        <td width="100" align="left">Sewing Qty</td>
        <td width="100" align="right">{{ $rows->sew_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">21</td>
        <td width="100" align="left">Iron Done</td>
        <td width="100" align="right">{{ $rows->iron_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">22</td>
        <td width="100" align="left">Poly Done</td>
        <td width="100" align="right">{{ $rows->poly_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">23</td>
        <td width="100" align="left">Carton Done</td>
        <td width="100" align="right">{{ $rows->car_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
    <tr>
        <td width="30" align="center">24</td>
        <td width="100" align="left">Inspection Done</td>
        <td width="100" align="right">{{ $rows->insp_pass_qty }}</td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
    </tr>
</table>
@endforeach