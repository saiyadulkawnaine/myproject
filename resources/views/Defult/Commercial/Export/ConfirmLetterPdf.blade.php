<p align="left">Date:{{ date('d.m.Y') }}</p>
<p></p>
<h2 align="center"><u>CONFIRMATION LETTER</u></h2>
<p></p>
<p></p>
<p></p>
<table width="70%" cellpadidng="3">
    <tr>
        <td align="left">Buyer</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->buyer_name }}<br/>{{ $rows->buyer_address }}</td>
    </tr>
    <tr>
        <td align="left">L/C No & Dt</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->lc_sc_no }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dt:{{ date('d.m.Y',strtotime($rows->lc_sc_date)) }}</td>
    </tr>
    <tr>
        <td align="left">PO Ref.No</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $style_ref }}</td>
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
        <td align="left">Invoice No.</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->invoice_no }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dt:{{ date('d.m.Y',strtotime($rows->invoice_date)) }}</td>
    </tr>
    <tr>
        <td align="left">Item</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $item_description }}</td>
    </tr>
    <tr>
        <td align="left">Quantity</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ number_format($rows->invoice_qty,0) }} PCS</td>
    </tr>
    <tr>
        <td align="left">Amount</td>
        <td align="left" width="30">:</td>
        <td align="left" width="300">{{ $rows->currency_code }}{{ $rows->currency_symbol }}{{ number_format($rows->invoice_value,2) }}</td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p>We hereby confirm that our product with all requirements in regards of product safety and usability for the import and disttribution in/within the EU. Furthermore we have had the set inspection standards as per order checked by a well known testing institute( SGE, STR, INTERTECH, TUEV, LGA, BUREAU VERITAS, DEKKA OR DR WESTLING)The passend and in full contract amount carried out tests are demonstrated by us as per attached test reports.</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>Thanks & best regards.</p>