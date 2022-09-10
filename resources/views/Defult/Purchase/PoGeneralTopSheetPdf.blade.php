<h2 align="center">Lithe Group</h2>
<h2 align="center">General Item Top Sheet</h2>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="60" align="center">Po No</td>
            <td width="60" align="center">Company</td>
            <td width="70" align="center">Po Date</td>
            <td width="150" align="center">Supplier</td>
            <td width="180" align="center">Description</td>
            <td width="70" align="center">Delv. Date</td>

            <td width="65" align="center">Amount</td>
            <td width="45" align="center">Currency</td>
            <td width="45" align="center">Exch Rate</td>
            <td width="100" align="center">Amount (BDT)</td>
            <td width="60" align="center">Rq. No</td>
        </tr>
    </thead>
    <tbody>
        
        @foreach ($purOrder as $row)
        <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="60" align="center">{{$row->po_no}}</td>
            <td width="60" align="center">{{$row->company_name}}</td>
            <td width="70" align="center">{{ $row->po_date }}</td>
            <td width="150" align="left">{{$row->supplier_name}}</td>
            <td width="180" align="left">{{$row->remarks}}</td>

            <td width="70" align="left">{{$row->delv_end_date}}</td>
            <td width="65" align="right">{{number_format($row->amount,2)}}</td>
            <td width="45" align="center">{{$row->currency_name}}</td>
            <td width="45" align="center">{{$row->exch_rate_c}}</td>
            <td width="100" align="right">{{number_format($row->amount_taka,2)}}</td>
            <td width="60" align="left">{{$row->rq_no}}</td>
        </tr>
       
        @endforeach
        <tr>
            <td width="618" align="right"><strong>Total</strong></td>
            <td width="65" align="right"></td>
            <td width="45" align="right"></td>
            <td width="45" align="right"></td>
            <td width="100" align="right">{{number_format($purOrder->sum('amount_taka'),2)}}</td>
            <td width="60" align="right"></td>
        </tr>
    </tbody>
</table>
    