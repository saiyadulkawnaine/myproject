<p></p>
<h2 align="center">BENEFICIARY CERTIFICATE</h2>
<p></p>
<p></p>
<p></p>
<table border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td width="630">WE DO HEREBY CERTIFY THAT GOODS SUPPLIED ARE STRICTLY IN ACCORDANCE WITH <strong>THE PI NO:</strong>&nbsp;&nbsp;{{ strtoupper($localexpdocaccept['pi_no']) }}   
        </td>
    </tr>
    <tr>
        <td width="630"><strong>L/C NO:&nbsp;&nbsp;</strong>{{ strtoupper($localexpdocaccept['local_lc_no']) }}, DATE:{{ strtoupper($localexpdocaccept['lc_date']) }}</td>
    </tr>
    <tr>
        <td width="630"><strong>EXPORT L/C CONT. NO. & DATE :</strong>&nbsp;&nbsp;{{ strtoupper($localexpdocaccept['customer_lc_sc']) }}</td>
    </tr>
    <tr><td width="630"></td></tr>
    <tr>
        <td width="630">@if ($localexpdocaccept['production_area_id']==20)THIS IS TO CERTIFY THAT WE HAVE SENT ORIGINAL DELIVERY CHALLAN<br/><br/>TO<br/>{{ strtoupper($localexpdocaccept['buyer_name']), strtoupper($localexpdocaccept['buyer_address'])}}<br/><br/>WE ALSO CERTIFY THAT THE ABOVE NOTED FABRICS ARE OF BANGLADESH ORIGIN.<br/><br/>THIS IS TO CERTIFY THAT WE DID NOT OR WILL NOT AVAIL BONDED WAREHOUSE FACITILY AND DUTY DRAWBACK FACILITY AND HAVE NOT OR WILL NOT APPLY FOR CASH INCENTIVE FACILITY AGAINST THE RAW MATERIALS USED IN MANUFACTURING THE SUPPLIED DYEING
        @endif
        </td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table border="0">
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="center" colspan="2">{{ strtoupper($localexpdocaccept['beneficiary']) }}</td>
    </tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="center" colspan="2">AUTHORISED SIGNATURE</td>
    </tr>
</table>