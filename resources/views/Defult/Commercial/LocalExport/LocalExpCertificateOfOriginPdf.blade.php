<h2 align="center"><u>CERTIFICATE OF ORIGIN</u></h2>
<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="center"><strong>Date: </strong></td>
        <td>{{ $localexpdocaccept['local_invoice_date'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<table border="1" cellpadding="3">
    <tr>
        <td width="40">1</td>
        <td width="150">INVOICE NO & DATE</td>
        <td width="440" colspan="1"><strong>{{ $localexpdocaccept['invoice_no'] }}</strong></td>
    </tr>
    <tr>
        <td colspan="4" width="630"></td>
    </tr>
    <tr>
        <td width="40">2</td>
        <td width="150">VALUE</td>
        <td width="440" colspan="1"><strong>{{ $localexpdocaccept['currency_symbol'] }}&nbsp;&nbsp;{{ number_format($localexpdocaccept['cumulative_amount'],2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="4" width="630"></td>
    </tr>
    <tr>
        <td width="40">3</td>
        <td width="150">COMMODITY</td>
        <td width="440" colspan="1">WE VERTIFY THAT THE ORIGIN OF THE ABOVE MENTIONED GOODS IS BANGLADESH</td>
    </tr>
    <tr>
        <td colspan="4" width="630"></td>
    </tr>
    <tr>
        <td width="40">4</td>
        <td width="150">QUANTITY</td>
        <td width="440" colspan="1">{{ $localexpdocaccept['cumulative_qty'] }}</td>
    </tr>
    <tr>
        <td colspan="4" width="630"></td>
    </tr>
    <tr>
        <td width="40">5</td>
        <td width="150">L/C NO.</td>
        <td width="440" colspan="1"><strong>{{ $localexpdocaccept['local_lc_no'] }}, Date:{{ $localexpdocaccept['lc_date'] }}</strong></td>
    </tr>
    <tr>
        <td colspan="3" width="630"></td>
    </tr>
    <tr>
        <td colspan="4" width="630">Supplied strictly as per Proforma Invoice and Condition thereof has been complied with.<br/>Certify  that the above merchandise is of <strong>Bangladesh Origin</strong>, for 100% Export Oriented Garments Factory.
        </td>
    </tr>
    <tr>
        <td colspan="4" width="630">Import against Export L/C Cont. No. & Date :&nbsp;&nbsp;{{ $localexpdocaccept['customer_lc_sc'] }}
        </td>
    </tr>
</table>