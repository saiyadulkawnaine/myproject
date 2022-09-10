<h3 align="center">Lithe Group</h3>
<h5 align="center">Bank Support Pending</h5>
<p align="center" style="font-size: 15px;">{{ $data['issuing_bank_branch_id'] }}</p>
<table cellspacing="0" cellpadding="0" style="margin: auto;" border="0">
    <tr>
        <td colspan="3" style="font-size: 15px;font:bold #000" width="260px"><strong>1.Bank Acceptance Pending</strong></td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Yarn</td>
        <td width="100px" align="right">{{ number_format($data['yarn'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Accessories</td>
        <td width="100px" align="right">{{ number_format($data['accessories'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Dyes & Chemical</td>
        <td width="100px" align="right">{{ number_format($data['dyeschemical'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">Others</td>
        <td width="100px" align="right">{{ number_format($data['others'],2) }}</td>
    </tr>
    <tr>
        <td width="160px" colspan="2" style="border-top:2px solid #000"><strong>Total</strong></td>
        <td width="100px" align="right" style="border-top:2px solid #000"><strong>{{ number_format($data['totalpending'],2) }}</strong></td>
    </tr>
</table>
<p></p>
<table cellspacing="0" cellpadding="0" style="margin: auto;" border="0">
    <tr>
        <td colspan="3" style="font-size: 15px;font:bold #000" width="260px"><strong>2.Matured But Payment Pending</strong></td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Yarn</td>
        <td width="100px" align="right">{{ number_format($data['yarn_pp'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Accessories</td>
        <td width="100px" align="right">{{ number_format($data['accessories_pp'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Dyes & Chemical</td>
        <td width="100px" align="right">{{ number_format($data['dyeschemical_pp'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">Others</td>
        <td width="100px" align="right">{{ number_format($data['others_pp'],2) }}</td>
    </tr>
    <tr>
        <td width="160px" colspan="2" style="border-top:2px solid #000"><strong>Total</strong></td>
        <td width="100px" align="right" style="border-top:2px solid #000"><strong>{{ number_format($data['totalpending_pp'],2) }}</strong></td>
    </tr>
</table>
<p></p>
<table cellspacing="0" cellpadding="0" style="margin: auto;" border="0">
    <tr>
        <td colspan="3" style="font-size: 15px;font:bold #000" width="260px"><strong>3.LC Opening Pending</strong></td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Yarn</td>
        <td width="100px" align="right">{{ number_format($data['yarn_lcp'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Accessories</td>
        <td width="100px" align="right">{{ number_format($data['accessories_lcp'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">For Dyes & Chemical</td>
        <td width="100px" align="right">{{ number_format($data['dyeschemical_lcp'],2) }}</td>
    </tr>
    <tr>
        <td width="30px"></td>
        <td width="130px">Others</td>
        <td width="100px" align="right">{{ number_format($data['others_lcp'],2) }}</td>
    </tr>
    <tr>
        <td width="160px" colspan="2" style="border-top:2px solid #000"><strong>Total</strong></td>
        <td width="100px" align="right" style="border-top:2px solid #000"><strong>{{ number_format($data['totalpending_lcp'],2) }}</strong></td>
    </tr>
</table>
<?php
$grandtotal=$data['totalpending']+$data['totalpending_pp']+$data['totalpending_lcp'];
?>
<p></p>
<table cellspacing="0" cellpadding="0" style="margin: auto;" border="0">
    <tr>
        <td width="160px" colspan="2" style="border-top:2px solid #000"><strong>Grand Total</strong></td>
        <td width="100px" align="right" style="border-top:2px solid #000"><strong>{{ number_format($grandtotal,2) }}</strong></td>
    </tr>
</table>