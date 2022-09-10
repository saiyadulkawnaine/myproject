<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Summary</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-center">Particulars</th>
@foreach($companies as $company )
<th width="100" class="text-center">{{$company->code}}</th>
@endforeach

<th width="150" class="text-center">Total</th>
</tr>
</thead>

<body>
<tr>
    <td width="20" align="center">1</td>
    <td width="150" align="center">Garment Export</td>
    @foreach($companies as $company )
    <td width="100" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getGmtDelails('{{$date_from}}','{{$date_to}}',{{$company->id}})">{{number_format($comGmt[$company->id],0)}}</a></td>
    @endforeach
    <td width="150" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getGmtDelails('{{$date_from}}','{{$date_to}}','')">{{number_format(array_sum($comGmt),0)}}</a></td>
</tr>
<tr>
    <td width="20" align="center">2</td>
    <td width="150" align="center">Knitting</td>
    @foreach($companies as $company )
    <td width="100" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getKnitingDelails('{{$date_from}}','{{$date_to}}',{{$company->id}})">{{number_format($comKniting[$company->id],0)}}</a></td>
    @endforeach
    <td width="150" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getKnitingDelails('{{$date_from}}','{{$date_to}}','')">{{number_format(array_sum($comKniting),0)}}</a></td>
</tr>
<tr>
    <td width="20" align="center">3</td>
    <td width="150" align="center">Dyeing</td>
    @foreach($companies as $company )
    <td width="100" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getDyeingDelails('{{$date_from}}','{{$date_to}}',{{$company->id}})">{{number_format($comDyeing[$company->id],0)}}</a></td>
    @endforeach
    <td width="150" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getDyeingDelails('{{$date_from}}','{{$date_to}}','')">{{number_format(array_sum($comDyeing),0)}}</a></td>
</tr>
<tr>
    <td width="20" align="center">4</td>
    <td width="150" align="center">AOP</td>
    @foreach($companies as $company )
    <td width="100" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getAopDelails('{{$date_from}}','{{$date_to}}',{{$company->id}})">{{number_format($comAop[$company->id],0)}}</a></td>
    @endforeach
    <td width="150" align="right"><a href="javascript:void(0)" onClick="MsGroupSale.getAopDelails('{{$date_from}}','{{$date_to}}','')">{{number_format(array_sum($comAop),0)}}</a></td>
</tr>
<tr>
    <td width="20" align="center">5</td>
    <td width="150" align="center">Screen Print</td>
    @foreach($companies as $company )
    <td width="100" align="right">0</td>
    @endforeach
    <td width="150" align="left"></td>
</tr>
<tr>
    <td width="20" align="center">6</td>
    <td width="150" align="center">Embroidery</td>
    @foreach($companies as $company )
    <td width="100" align="right">0</td>
    @endforeach
    <td width="150" align="left"></td>
</tr>
<tr style="background-color: #ccc; font-weight: bold;">
    <td width="20" align="center"></td>
    <td width="150" align="center">Total</td>
    @foreach($companies as $company )
    <td width="100" align="right">{{number_format($comTot[$company->id],0)}}</td>
    @endforeach
    <?php
    $summary_grand_total=array_sum($comGmt)+array_sum($comKniting)+array_sum($comDyeing)+array_sum($comAop)+array_sum($comSrp)+array_sum($comEmb);
    ?>
    <td width="150" align="right">{{number_format($summary_grand_total,0)}}</td>
</tr>
</body>
</table>
<br/>
@foreach($companies as $company )
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">{{$company->code}}</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-center">Particulars</th>
@foreach($comMonth[$company->id] as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-center">Total</th>
</tr>
</thead>
<tbody>
    <tr>
        <td width="20" align="center">1</td>
        <td width="150" align="center">Garment Export</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthGmt[$company->id][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthGmt[$company->id]),0)}}</td>
    </tr>
    <tr>
        <td width="20" align="center">2</td>
        <td width="150" align="center">Kinting</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthKniting[$company->id][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthKniting[$company->id]),0)}}</td>
    </tr>
    <tr>
        <td width="20" align="center">3</td>
        <td width="150" align="center">Dyeing</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthDyeing[$company->id][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthDyeing[$company->id]),0)}}</td>
    </tr>
    <tr>
        <td width="20" align="center">4</td>
        <td width="150" align="center">AOP</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthAop[$company->id][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthAop[$company->id]),0)}}</td>
    </tr>
    <tr>
        <td width="20" align="center">5</td>
        <td width="150" align="center">Screen Print</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthSrp[$company->id][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthSrp[$company->id]),0)}}</td>
    </tr>
    <tr>
        <td width="20" align="center">6</td>
        <td width="150" align="center">Embroidery</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthEmb[$company->id][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthEmb[$company->id]),0)}}</td>
    </tr>
     <tr style="background-color: #ccc; font-weight: bold;">
        <td width="20" align="center">6</td>
        <td width="150" align="center">Total</td>
        @foreach($comMonth[$company->id] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthTot[$company->id][$month],0)}}</td>
        @endforeach
        <?php
        $company_grand_total=array_sum($comMonthTot[$company->id]);
        ?>
        <td width="150" align="right">{{number_format($company_grand_total,0)}}</td>
    </tr>
</tbody>
</table>
@endforeach
