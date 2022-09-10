<table cellspacing="0" cellpadding="1">
    <tr>
        <th colspan="4" align="center" width="640"><h4></h4></th>
    </tr>
    <tr>
        <td width="200">To</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200">Delivery Challan:&nbsp; GDO- {{ str_pad($prodgmtexfactory['id'],10,0,STR_PAD_LEFT ) }}</td>
    </tr>
    <tr>
        <td width="200">{{ $prodgmtexfactory['forwarding_agent_id'] }}</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200">Date: {{ $prodgmtexfactory['exfactory_date'] }}</td>
    </tr>
    <tr>
        <td width="200">{{ $prodgmtexfactory['forwarding_agent_address'] }}</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200">Department&nbsp;:&nbsp;GARMENTS</td>
    </tr>
    <tr>
        <td width="200">{{ $prodgmtexfactory['driver'] }}</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200">Transport Company:&nbsp;{{ $prodgmtexfactory['transport_agent_id'] }}</td>
    </tr>
    <tr>
        <td width="200">License No:&nbsp;{{ $prodgmtexfactory['driver_license_no'] }}</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200">Place of loading:&nbsp;{{ $prodgmtexfactory['port_of_loading'] }}</td>
    </tr>
    <tr>
        <td width="200">Truck No:&nbsp;{{ $prodgmtexfactory['truck_no'] }}</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200">DEPO:&nbsp;{{ $prodgmtexfactory['depo_name'] }}</td>
    </tr>
    <tr>
        <td width="200">Lock No:&nbsp;{{$prodgmtexfactory['lock_no']}}</td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200"></td>
    </tr>
    <tr>
        <td width="300">Recipient:&nbsp;{{ $prodgmtexfactory['recipient'] }}</td>
        <td width="20"></td>
        <td width="120"></td>
        <td width="200"></td>
    </tr>
    <tr>
        <td  width="60" colspan="4">Remarks:&nbsp;</td>
        <td width="580">{!! nl2br(e($prodgmtexfactory['remarks'])) !!}</td>
    </tr>
    <tr>
        <td width="200"></td>
        <td width="120"></td>
        <td width="120"></td>
        <td width="200"></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="1">
    <thead>
        <tr>
            <th width="40" class="text-center" align="center">SL</th>
            <th width="120" class="text-center" align="center">Buyer</th>
            <th width="150" class="text-center" align="center">Style & Order No</th>
            <th width="80" class="text-center" align="center">Item Description</th>
            <th width="100" class="text-center" align="center">Garment Qty-Pcs</th>
            <th width="80" class="text-center" align="center">No of<br/> Carton</th>
            <th width="70" class="text-center" align="center">Producing <br/>Company</th>
        </tr>
    </thead>
    <?php
        $i=1;
        $totalqty=0;
        $tgmtcarton=0;
        

    ?>
    @foreach($saved as $buyer_id=>$values)
    @foreach($values as $style_id=>$row)
    <?php
    $qty=array_sum($row['qty']);
    $no_of_carton=array_sum($row['no_of_carton']);
    ?>
    <tbody>
        <tr>
            <td width="40" align="center">{{ $i }}</td>
            <td width="120" align="center">{{ $row['buyer_name'] }}</td>
            <td width="150" align="center">{{ $row['style_ref'] }} & {{ implode(',',$row['sale_order_no']) }}<br/> {{ $row['company_name'] }}</td>
             <td width="80" align="center">{{ implode(',',$row['item_description']) }}</td>
            <td width="100" align="right">{{number_format($qty,0)}}</td>
            <td width="80" align="right">{{ number_format($no_of_carton,0) }}</td>
            <td width="70" align="center">{{ implode(',',$row['produced_company_id']) }}</td> 
        </tr>
    </tbody>
    <?php
        $i++;
        $totalqty+=$qty;
        $tgmtcarton+=$no_of_carton;
    ?>
    @endforeach
    @endforeach
</table>
<table cellspacing="0" cellpadding="1">
    <tr>
        <th width="40" class="text-center" align="center"></th>
        <th width="120" class="text-center" align="center"></th>
        <th width="80" class="text-center" align="center"></th>
        <th width="150" class="text-center" align="right"><strong>Grand Total</strong></th>
        <th width="100" class="text-center" align="right"><strong>{{ number_format($totalqty,0) }}</strong></th>
        <th width="80" class="text-center" align="right"><strong>{{ number_format($tgmtcarton,0) }}</strong></th>
        <th width="70" class="text-center" align="center"></th>
    </tr>
</table>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <tr>
        <td width="128" align="center" ></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
        <td width="128" align="center"></td>
    </tr>
    <?php
         $underline="color:black";
    ?>
    <tr>
        <td width="100"><span style="color:{{ $underline }};width:80"><hr></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Received By</strong></td>
        <td width="28"></td>
        <td width="100"><span style="color:{{ $underline }}"><hr></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Prepared By</strong></td>
        <td width="28"></td>
        <td width="120"><span style="color:{{ $underline }}"><hr></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Department Head</strong></td>
        <td width="8"></td>
        <td width="100" align="center"><span style="color:{{ $underline }}"><hr></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Checked By</strong></td>
        <td width="28"></td>
        <td width="100" align="center"><span style="color:{{ $underline }}"><hr></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Authorised By</strong></td>
        <td width="28"></td>
    </tr>
    {{-- <tr>
        <td width="128" align="center"><div><hr></div></td>
        <td width="128" align="center"><div style="text-align:center;"><hr></div></td>
        <td width="128" align="center"><div style="text-align:center;"><hr></div></td>
        <td width="128" align="center"><div style="text-align:center;"><hr></div></td>
        <td width="128" align="center"><div style="text-align:center;"><hr></div></td>
    </tr> --}}
</table>