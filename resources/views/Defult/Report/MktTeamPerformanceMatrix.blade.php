{{-- <table border="1" cellpadding="2" >
    @foreach ($datas as $team_leader_id=>$row)
    <tbody>
        <tr>
            <td align="center" width="200">{{$teamArr[$team_leader_id]['team_name']}}</td>
            <td width="200">
                <table  border="1" cellpadding="3">
                    <tr>
                        @foreach ($row as $m)
                        <td align="center" colspan="2" width="200">{{$m->month}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($row as $d)
                            <td width="100" align="right">{{$d->qty}}</td>
                            <td width="100" align="right">{{$d->amount}}</td>
                       @endforeach
                    </tr>
                </table>
            </td>      
        </tr>
    </tbody>
    @endforeach
</table> --}}
<table border="1" cellpadding="2" >
    <thead>
        <tr>
            <td width="200" align="center">Team Leader</td>
            <td width="200" colspan="2" align="center">January</td>
            <td width="200" colspan="2" align="center">February</td>
            <td width="200" colspan="2" align="center">March</td>
            <td width="200" colspan="2" align="center">April</td>
            <td width="200" colspan="2" align="center">May</td>
            <td width="200" colspan="2" align="center">June</td>
            <td width="200" colspan="2" align="center">July</td>
            <td width="200" colspan="2" align="center">August</td>
            <td width="200" colspan="2" align="center">September</td>
            <td width="200" colspan="2" align="center">October</td>
            <td width="200" colspan="2" align="center">November</td>
            <td width="200" colspan="2" align="center">December</td>
        </tr>
        <tr>
            <td width="200" align="center"></td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
            <td width="100" align="center">Qty</td>
            <td width="100" align="center">Amount</td>
        </tr>
    </thead>
    @foreach ($datas as $team_leader_id=>$row)
    <tbody>
        <tr>
            <td align="center" width="200">{{$teamArr[$team_leader_id]['team_name']}}</td>
            <td width="200">
                <table  border="1" cellpadding="3">
                    <tr>
                    @foreach($row as $d)
                        <td width="100" align="right">{{$d->qty}}</td>
                        <td width="100" align="right">{{$d->amount}}</td>
                    @endforeach
                    </tr>
                </table>
            </td>      
        </tr>
    </tbody>
    @endforeach
</table>