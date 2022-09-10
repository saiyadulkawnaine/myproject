{{-- <p align="left">Date:{{ date('d.m.Y') }}</p>
<p></p> --}}
<h2 align="center"><u>BENEFICIARY'S CONFIRMATION FOR AZO STUFF</u></h2>
<p></p>
<p></p>
<p></p>
<table width="70%"> 
    <tr>
        <td align="left">Invoice No. & Date</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->invoice_no }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:{{ date('d.m.Y',strtotime($rows->invoice_date)) }}</td>
    </tr>
    <tr>
        <td align="left">Order No</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $sale_order_no }}</td>
    </tr>
    <tr>
        <td align="left">Article No.</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $article_no }}</td>
    </tr>
    <tr>
        <td align="left">Quantity</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ number_format($rows->invoice_qty,0) }} PCS</td>
    </tr>
    <tr>
        <td align="left">Number of Carton</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->total_ctn_qty }}</td>
    </tr>
    <tr>
        <td align="left">Export L/C No & Dt</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->lc_sc_no }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dt:{{ date('d.m.Y',strtotime($rows->lc_sc_date)) }}</td>
    </tr>
    <tr>
        <td align="left">PSID NO:</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300"></td>
    </tr>
</table>
<p></p>
<p>We do hereby confirming that the style mentioned in the confirmation letter have been tested according to the GCM-Method and that they are feel of any banned.</p>
<p>CO.KG, RUSSEER WEG, 101-103, 24109 KIEL, GERMANY</p>
<p></p>
<p>We also agree to bear all charges which may arise out claims from the side of the applicant's customers</p>
<p>Name of the test institute with official seal:</p>
<p>Place and Date of issue: Bangladesh</p>
<p></p>
<p>For & Behalf of</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>Authorised Signature</p>