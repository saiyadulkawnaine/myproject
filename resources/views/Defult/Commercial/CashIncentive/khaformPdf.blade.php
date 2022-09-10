<table>
    <tr>
        <td align="left" style="font-family:siyambangla; font-size: 9px">{{ $rows['id'] }}</td>
        <td align="right" style="font-family:siyambangla; font-size: 9px">ফরম-খ</td>
    </tr>
    <tr>
        <td align="left"></td>
        <td align="left"></td>
    </tr>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" colspan="2"><strong>(অনুচ্ছেদ ০৩(ক) এফই সার্কুলার নং-০৯/২০০১ দ্রষ্টব্য)</strong></td>
    </tr>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" colspan="2">স্বীয় উৎপাদিত বস্ত্র দ্বারা প্রস্তুতকৃত তৈরী পোশাক রপ্তানীর বিপরীতে শুল্কবন্ড ও ডিউটি ড্র-ব্যাংক এর পরিবর্তে বিকল্প নগদ সহায়তার জন্য রপ্তানীমুখী কম্পোজিট তৈরী পোশাক ইউনিটের আবেদন পত্র</td>
    </tr>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" colspan="2">(ক) আবেদনকারী কম্পোজিট ইউনিটের নাম ও ঠিকানাঃ&nbsp;&nbsp;&nbsp;{{ $rows['company_name'] }},{{ $rows['company_address'] }}</td>
    </tr>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" colspan="2">রপ্তানী নিবন্ধন সনদপত্র নম্বরঃ (ইআরসি) RA-{{ $rows['erc_no'] }}</td>
    </tr>
</table>
<table>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" width="200px">(খ) রপ্তানী ঋণপত্রের/চুক্তিপত্রের নম্বর তারিখ ও মুল্যঃ<br/>(পাঠযোগ্য সত্যায়িত কপি দাখিল করিতে হইবে)</td>
        <td style="font-family:siyambangla; font-size: 9px" width="510px">&nbsp;{{ $rows['sc_lc'] }}<br/>&nbsp;{{ $rows['replaces_lc_sc'] }}</td>
    </tr>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" colspan="2" width="700px">(গ) <strong>রপ্তানীকৃত তৈরী পোশাক উৎপাদনে ব্যবহৃত উপকরণাদির সংগ্রহ সূত্রঃ</strong></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="1" style="border-collapse: collapse">
    <tr style="font-family:siyambangla; font-size: 9px">
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="50">উপকরণের নাম</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="80">উপকরণের<br/>পরিমাণ</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="110">স্থানীয় সংগ্রহের ক্ষেত্রে<br/> সরবরাহকারির নাম</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="110">অভ্যন্তরীণ ব্যাংক-টু-ব্যাংক<br/>  ঋণপত্রের নম্বর</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="80">তারিখ</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="100">মূল্য-{{ $rows['currency_code'] }}</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="100">বিদেশ হইতে আমদানীর (থাকিলে) ব্যাংক-টু-ব্যাংক আমদানী ঋণপত্র এর নম্বর তারিখ ও মূল্য</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="100">বিকল্প নগদ সহায়তার প্রাপক পক্ষের নাম ও ঠিকানা</th>
    </tr>
    <tr style="font-family:siyambangla; font-size: 9px">
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="130" colspan="2">(১)</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="400" colspan="4">(২)</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="100">(৩)</th>
        <th align="center" style="font-family:siyambangla; font-size: 9px" width="100">(৪)</th>
    </tr>
    <?php
        $lcYarnTotalQty=0;
        $lcYarnTotalAmount=0;
    ?>
    @foreach ($incentiveyarnbtblc as $item)
        <tr>
            
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="50">Yarn</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="80">{{ number_format($item->lc_yarn_qty,2) }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="110">{{ $item->supplier_name }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="110">{{ $item->lc_no }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="80">{{ date('d.M.Y',strtotime($item->lc_date)) }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="100"> {{ number_format($item->lc_yarn_amount,2) }} </td>
            @if ($loop->first)
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="100" rowspan="{{ $item->count() }}"></td>
            {{-- @endif
            @if ($loop->last) --}}
            <td align="center" style="font-family:siyambangla; font-size: 9px" width="100" rowspan="{{ $item->count() }}">{{ $rows['company_name'] }},{{ $rows['company_address'] }}</td>
            @endif
               
        </tr>
    <?php
        $lcYarnTotalQty+=$item->lc_yarn_qty;
        $lcYarnTotalAmount+=$item->lc_yarn_amount;
    ?>
    @endforeach
    <tr>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="50"><strong>Total</strong></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="80"><strong>{{ number_format($lcYarnTotalQty,2) }}</strong></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="110"></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="110"></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="80"></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="100"><strong>{{ $rows['currency_symbol'] }}&nbsp;{{ number_format($lcYarnTotalAmount,2) }}</strong></td>
        {{-- <td align="center" style="font-family:siyambangla; font-size: 9px" width="100"></td> --}}
        {{-- <td align="center" style="font-family:siyambangla; font-size: 9px" width="100"></td> --}}
    </tr>
</table>
<p style="font-family:siyambangla; font-size: 9px">(২ ও ৩ নং কলামে উলিখিত ঋণপত্রগুলির পাঠযোগ্য সত্যায়িত কপি দাখিল করিতে হইবে। অভ্যন্তরীণ ব্যাংক-টু-ব্যাংক ঋণপত্রের বিকল্প নগদ সহায়তার প্রাপক পক্ষের নাম ও ঠিকানা সুস্পষ্ট থাকিবে। ২ নং কলামে উলিখিত সুতা বিটিএমএ এর নির্ধারিত ফরমে মিল ও বিটিএমএ কতৃপক্ষের স্বাক্ষর সম্বলিত প্রত্যয়নপত্র দাখিল করিতে হইবে। এই সুতা উৎপাদনে ব্যবহৃত উৎপাদনকারীর জন্য শুল্ক সুবিধা গ্রহণ করা হয় নাই এবং ডিউটি ড্র-ব্যাংক অথবা বিকল্প নগদ সহায়তা সুবিধার আবেদন ও করা হয় নাই ও হইবে না মর্মে সুতা উৎপাদনকারীর প্রত্যয়নপত্র দাখিল করিতে হইবে।)<br/>
(ঘ) বিটিএমএ এর সদস্য মিল হইতে অভ্যন্তরীণ ব্যাংক-টু-ব্যাংক ঋণপত্রের আওতায় সংগ্রহকৃত সুতা দ্বারা অন্য প্রতিষ্ঠানের মাধ্যমে বস্ত্র উৎপাদন সংশ্লিষ্ট তথ্যঃ</p>
<table border="1" cellspacing="0" cellpadding="2" style="border-collapse: collapse">
    <tr style="font-family:siyambangla; font-size: 9px">
        <th style="font-family:siyambangla; font-size: 9px" width="190">বস্ত্র উৎপাদনকারী প্রতিষ্ঠানের নাম ও ঠিকানা</th>
        <th style="font-family:siyambangla; font-size: 9px" width="190">উৎপাদিত বস্ত্রের পরিমান</th>
        <th style="font-family:siyambangla; font-size: 9px" width="190">বস্ত্র উৎপাদন ব্যয়</th>
        <th style="font-family:siyambangla; font-size: 9px" width="190">উৎপাদন ব্যয় পরিশোধ পদ্ধতি</th>
    </tr>
    <tr>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="190">১</td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="190">২</td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="190">৩</td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="190">৪</td>
    </tr>
    <tr>
        <td align="center" width="190" style="font-family:siyambangla; font-size: 9px">NILL</td>
        <td align="center" width="190" style="font-family:siyambangla; font-size: 9px">NILL</td>
        <td align="center" width="190" style="font-family:siyambangla; font-size: 9px">NILL</td>
        <td align="center" width="190" style="font-family:siyambangla; font-size: 9px">NILL</td>
    </tr>
</table>
<p style="font-family:siyambangla; font-size: 9px">(৩ নং কলামে উল্লিখিত ঋণপত্রগুলির পাঠযোগ্য সত্যায়িত কপি দাখিল করিতে হইবে।)<br/>
(ঙ) চূড়ান্ত রপ্তানী চালানের বিবরণঃ </p>
<table  border="1" cellspacing="0" cellpadding="1" style="border-collapse: collapse">
    <thead>
        <tr style="font-family:siyambangla; font-size: 9px">
            <th style="font-family:siyambangla; font-size: 9px">ব্যাংক বিল নং</th>
            <th style="font-family:siyambangla; font-size: 9px">পন্যের বর্ণনা</th>
            <th style="font-family:siyambangla; font-size: 9px">পরিমান পিছ</th>
            <th style="font-family:siyambangla; font-size: 9px">ইনভয়েস মুল্য(বৈদেশিক মুদ্রায়) ({{ $rows['currency_code'] }}) </th>
            <th style="font-family:siyambangla; font-size: 9px">জাহাজীকরণের তারিখ</th>
            <th style="font-family:siyambangla; font-size: 9px">ইএক্সপি নং</th>
            <th colspan="2" style="font-family:siyambangla; font-size: 9px">বৈদেশিক মুদ্রায় প্রত্যাবাসিত রপ্তানী মুল্য ও প্রত্যাবাসনের তারিখ ({{ $rows['currency_code'] }})</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">জাহাজ ভাড়া<br/>({{ $rows['currency_code'] }})</th>
        </tr>
        <tr style="font-family:siyambangla; font-size: 9px">
            <th align="center" style="font-family:siyambangla; font-size: 9px">(১)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(২)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৩)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৪)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৫)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৬)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৭)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৮)</th>
            <th align="center" style="font-family:siyambangla; font-size: 9px">(৯)</th>
        </tr>
    </thead>
    <?php 
        $totalPcsQty=0;
        $totalInvoiceAmount=0;
        $totalRealizedAmount=0;
        $totalClaimAmount=0;
        $totalLocalCurrency=0;
		$totalCostOfExport=0;
        //$exch_rate=0;
        $avgConvRate=0;
        $totalFreight=0;
    ?>
    <tbody>
    @foreach ($cashincentiveclaim as $invoicedtl)
        <tr style="font-family:siyambangla; font-size: 9px">
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->bank_bill_no }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->item_desc }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ number_format($invoicedtl->invoice_qty,0) }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->invoice_amount }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->bl_date }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->exp_form_no }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ number_format($invoicedtl->realized_amount,2) }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->realized_date }}</td>
            <td align="center" style="font-family:siyambangla; font-size: 9px">{{ $invoicedtl->freight }}</td>
        </tr>
    <?php 
        $totalPcsQty+=$invoicedtl->invoice_qty;
        $totalInvoiceAmount+=$invoicedtl->invoice_amount;
        $totalRealizedAmount+=$invoicedtl->realized_amount;
        $totalCostOfExport+=$invoicedtl->cost_of_export;
        $totalClaimAmount+=$invoicedtl->claim_amount;
        $totalLocalCurrency+=$invoicedtl->local_cur_amount;
        $avgConvRate=$invoicedtl->exch_rate;
        $totalFreight+=$invoicedtl->freight;
    ?>
    @endforeach
    </tbody>
    <tfoot>
        <tr style="font-family:siyambangla; font-size: 9px">
            <td align="center" style="font-family:siyambangla; font-size: 9px"><strong>Total</strong></td>
            <td align="center"></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"><strong>{{ number_format($totalPcsQty,0) }}</strong></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"><strong>{{ $rows['currency_symbol'] }}&nbsp;{{ number_format($totalInvoiceAmount,2) }}</strong></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"><strong>{{ $rows['currency_symbol'] }}&nbsp;{{ number_format($totalRealizedAmount,2) }}</strong></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"></td>
            <td align="center" style="font-family:siyambangla; font-size: 9px"><strong>{{ $totalFreight }}</strong></td>
        </tr>
    </tfoot>
</table>
<p style="font-family:siyambangla; font-size: 9px">(চ) বিকল্প নগদ সহায়তার আবেদনকৃত অংকঃ</p>
<table border="1" cellspacing="0" cellpadding="2" style="border-collapse: collapse">
    <tr>
        <td style="font-family:siyambangla; font-size: 9px" align="center" width="450px">রপ্তানী ঋণ পত্রের/চুক্তিপত্রের বিপরীতে ব্যবহৃত স্বীয় প্রতিষ্ঠানে দেশীয় সুতা দ্বারা উৎপাদিত বস্ত্রের মুল্য।</td>
        <td style="font-family:siyambangla; font-size: 9px" align="center" width="300px">১ নং কলামে উল্লিখিত বস্ত্র মুল্যের {{ $rows['claim_per'] }}% হারে নগদ সহায়তার অংক।</td>
    </tr>
    <tr style="font-family:siyambangla; font-size: 9px">
        <td style="font-family:siyambangla; font-size: 9px" align="center" width="450px">(১)</td>
        <td style="font-family:siyambangla; font-size: 9px" align="center" width="300px">(২)</td>
    </tr>
</table>
<table>
    <tr>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="450" align="center"><strong>US ${{ number_format($totalCostOfExport,2) }} </strong></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="300" align="center"><strong>US$ {{ number_format($totalClaimAmount,2) }} @ {{ number_format($avgConvRate,2) }} </strong></td>
    </tr>
    <tr>
        <td width="450"></td>
        <td align="center" style="font-family:siyambangla; font-size: 9px" width="300"><strong>Tk {{ number_format($totalLocalCurrency,0) }}</strong></td>
    </tr>
</table>
<p style="font-family:siyambangla; font-size: 9px">প্রত্যয়ন করা যাইতেছে যে, উপরে ঘোষিত সূত্র হইতে সংগৃহীত বাংলাদেশে উৎপাদিত সুতা দ্বারা আমাদের স্বীয় প্রতিষ্ঠানে প্রস্তুতকৃত তৈরী পোশাকের রপ্তানীর বিপরীতে বিকল্প নগদ সহায়তার আবেদন করা হইতেছে। এই উৎপাদনে ব্যবহৃত উপকরণাদির জন্য আমরা শুল্ক বন্ড সুবিধা ভোগ করি নাই এবং ডিউটি ড্র-ব্যাংক সুবিধার জন্যও আবেদন করি নাই এবং করিব না। আবেদনপত্রে প্রদত্ত তথ্যাদি/ঘোষনা পরবর্তীতে ভুল/অসত্য পাওয়ার ক্ষেত্রে প্রদত্ত নগদ সহায়তা সমুদয় বা অংশ বিশেষ আমাদের নিকট হইতে / আমাদের হিসাব হইতে আদায় লওয়া যাইবে।</p>
<p></p>

<table>
    <tr><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td></tr>
    <tr>
        <td style="font-family:siyambangla; font-size: 9px;" width="100">তারিখঃ</td>
        <td width="300"></td>
        <td align="right" style="font-family:siyambangla; font-size: 9px;" width="300">আবেদনকারী প্রতিষ্ঠানের স্বত্বাধিকারী/&nbsp;&nbsp;<br/>ক্ষমতাপ্রাপ্ত কর্মকর্তার স্বাক্ষর ও পদবী&nbsp;&nbsp;</td>
    </tr>
</table>