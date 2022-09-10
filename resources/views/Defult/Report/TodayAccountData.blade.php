

<table  border="1" style="margin: 0 auto;">
    <thead>
        <tr style="background-color: #EAE9E9">
        <th width="300" class="text-left">Particulars</th>
        <th width="112" class="text-center">A21</th>
        <th width="112" class="text-center">FFL</th>
        <th width="114" class="text-center">LAL</th>
        <th width="114" class="text-center">FDL</th>
        <th width="114" class="text-center">FPL</th>
        <th width="114" class="text-center">LFL</th>
        <th width="114" class="text-center">MIL</th>
        <th width="114" class="text-center">HO</th>
        <th width="114" class="text-center">TOTAL</th>
        </tr>
    </thead>

    <tr><td colspan="10"></td></tr>
    <tr><td colspan="10"><strong>Today Performance</strong></td></tr>
    <tr><td colspan="10" style="padding-left: 15px"><strong>Sales Generated</strong></td></tr>
    <tr>
    <td width="300" style="padding-left: 30px"> Operating Revenue </td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,2)">{{isset($today_revenue_arr[16][2])?number_format($today_revenue_arr[16][2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,4)">{{isset($today_revenue_arr[16][4])?number_format($today_revenue_arr[16][4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,1)">{{isset($today_revenue_arr[16][1])?number_format($today_revenue_arr[16][1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,5)">
    {{isset($today_revenue_arr[16][5])?number_format($today_revenue_arr[16][5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,6)">{{isset($today_revenue_arr[16][6])?number_format($today_revenue_arr[16][6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,41)">
    {{isset($today_revenue_arr[16][41])?number_format($today_revenue_arr[16][41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,3)">{{isset($today_revenue_arr[16][3])?number_format($today_revenue_arr[16][3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,21)">{{isset($today_revenue_arr[16][21])?number_format($today_revenue_arr[16][21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $row_total16=isset($today_revenue_arr[16])?array_sum($today_revenue_arr[16]):0;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(16,0)">
    {{number_format($row_total16,0)}}
    </a>
    </td>
    </tr>
    <tr>
    <td width="300" style="padding-left: 30px">Non-Operating Revenue</td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,2)">
    {{isset($today_revenue_arr[25][2])?number_format($today_revenue_arr[25][2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,4)">
    {{isset($today_revenue_arr[25][4])?number_format($today_revenue_arr[25][4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,1)">{{isset($today_revenue_arr[25][1])?number_format($today_revenue_arr[25][1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,5)">{{isset($today_revenue_arr[25][5])?number_format($today_revenue_arr[25][5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,6)">{{isset($today_revenue_arr[25][6])?number_format($today_revenue_arr[25][6],0):''}}</a></td>
    <td width="114" align="right">
        <a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,41)">
    {{isset($today_revenue_arr[25][41])?number_format($today_revenue_arr[25][41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,3)">{{isset($today_revenue_arr[25][3])?number_format($today_revenue_arr[25][3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,21)">{{isset($today_revenue_arr[25][21])?number_format($today_revenue_arr[25][21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $row_total25=isset($today_revenue_arr[25])?array_sum($today_revenue_arr[25]):0;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(25,0)">
    {{number_format($row_total25,0)}}
    </a>
    </td>
    </tr>
    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Revenue</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,2)">{{isset($today_revenue_com_total[2])?number_format(array_sum($today_revenue_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,4)">{{isset($today_revenue_com_total[4])?number_format(array_sum($today_revenue_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,1)">{{isset($today_revenue_com_total[1])?number_format(array_sum($today_revenue_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,5)">{{isset($today_revenue_com_total[5])?number_format(array_sum($today_revenue_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,6)">{{isset($today_revenue_com_total[6])?number_format(array_sum($today_revenue_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,41)">{{isset($today_revenue_com_total[41])?number_format(array_sum($today_revenue_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,3)">{{isset($today_revenue_com_total[3])?number_format(array_sum($today_revenue_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,21)">{{isset($today_revenue_com_total[21])?number_format(array_sum($today_revenue_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayrevenue(0,0)">{{number_format($row_total16+$row_total25,0)}}</a></strong></td>
    </tr>

    <tr><td colspan="10" style="padding-left: 15px"><strong>Cash Inflow</strong></td></tr>
    <?php
    $todayInflowTotal=0;
    ?>
    @foreach($today_inflow_arr as $todayinflowhead=>$todayinflowvalue)
    <?php
    $head=explode('::',$todayinflowhead);
    ?>
    <tr>
    <td width="300" style="padding-left: 30px"> {{$head[1]}} </td>
    <td width="112" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},2)">{{isset($todayinflowvalue[2])?number_format($todayinflowvalue[2],0):''}}
    </a>
    </td>
    <td width="112" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},4)">
    {{isset($todayinflowvalue[4])?number_format($todayinflowvalue[4],0):''}}
    </a>
    </td>
    <td width="114" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},1)">
    {{isset($todayinflowvalue[1])?number_format($todayinflowvalue[1],0):''}}
    </a>
    </td>
    <td width="112" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},5)">
    {{isset($todayinflowvalue[5])?number_format($todayinflowvalue[5],0):''}}
    </a>
    </td>
    <td width="112" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},6)">
    {{isset($todayinflowvalue[6])?number_format($todayinflowvalue[6],0):''}}
    </a>
    </td>
    <td width="114" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},41)">
    {{isset($todayinflowvalue[41])?number_format($todayinflowvalue[41],0):''}}
    </a>
    </td>
    <td width="112" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},3)">
    {{isset($todayinflowvalue[3])?number_format($todayinflowvalue[3],0):''}}
    </a>
    </td>
    <td width="112" align="right">
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},21)">
    {{isset($todayinflowvalue[21])?number_format($todayinflowvalue[21],0):''}}
    </a>
    </td>
    <td width="114" align="right">
    <?php
    $todayInflowRowTotal=isset($today_inflow_row_total[$todayinflowhead])?array_sum($today_inflow_row_total[$todayinflowhead]):0;
    $todayInflowTotal+=$todayInflowRowTotal;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow({{$head[0]}},0)">
    {{number_format($todayInflowRowTotal,0)}}
    </a>
    </td>
    </tr>
    @endforeach

    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Cash Inflow</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">
    <strong>
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,2)">
    {{isset($today_inflow_com_total[2])?number_format(array_sum($today_inflow_com_total[2]),0):''}}
    </a>
    </strong>
    </td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">
        <strong>
        <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,4)">
        {{isset($today_inflow_com_total[4])?number_format(array_sum($today_inflow_com_total[4]),0):''}}
        </a>
       </strong>
       </td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">
    <strong>
        <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,1)">
        {{isset($today_inflow_com_total[1])?number_format(array_sum($today_inflow_com_total[1]),0):''}}
        </a>
    </strong>
    </td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">
        <strong>
        <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,5)">
        {{isset($today_inflow_com_total[5])?number_format(array_sum($today_inflow_com_total[5]),0):''}}
        </a>
        </strong>
    </td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">
        <strong>
        <a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,6)">
        {{isset($today_inflow_com_total[6])?number_format(array_sum($today_inflow_com_total[6]),0):''}}
        </a>
        </strong>
    </td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,41)">{{isset($today_inflow_com_total[41])?number_format(array_sum($today_inflow_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,3)">{{isset($today_inflow_com_total[3])?number_format(array_sum($today_inflow_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,21)">{{isset($today_inflow_com_total[21])?number_format(array_sum($today_inflow_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayinflow(0,0)">{{number_format($todayInflowTotal,0)}}</a></strong></td>
    </tr>


    
    <tr><td colspan="10" style="padding-left: 15px"><strong>Cash Outflow</strong></td></tr>
    <?php
    $todayOutflowTotal=0;
    ?>
    @foreach($today_outflow_arr as $todayoutflowhead=>$todayoutflowvalue)
    <?php
    $head=explode('::',$todayoutflowhead);
    ?>
    <tr>
    <td width="300" style="padding-left: 30px"> {{$head[1]}} </td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},2)">{{isset($todayoutflowvalue[2])?number_format($todayoutflowvalue[2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},4)">{{isset($todayoutflowvalue[4])?number_format($todayoutflowvalue[4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},1)">
    {{isset($todayoutflowvalue[1])?number_format($todayoutflowvalue[1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},5)">{{isset($todayoutflowvalue[5])?number_format($todayoutflowvalue[5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},6)">{{isset($todayoutflowvalue[6])?number_format($todayoutflowvalue[6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},41)">
    {{isset($todayoutflowvalue[41])?number_format($todayoutflowvalue[41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},3)">{{isset($todayoutflowvalue[3])?number_format($todayoutflowvalue[3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},21)">{{isset($todayoutflowvalue[21])?number_format($todayoutflowvalue[21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $todayOutflowRowTotal=isset($today_outflow_row_total[$todayoutflowhead])?array_sum($today_outflow_row_total[$todayoutflowhead]):0;
    $todayOutflowTotal+=$todayOutflowRowTotal;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow({{$head[0]}},0)">
    {{number_format($todayOutflowRowTotal,0)}}
    </a>
    </td>
    </tr>
    @endforeach

    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Cash Outflow</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,2)">{{isset($today_outflow_com_total[2])?number_format(array_sum($today_outflow_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,4)">{{isset($today_outflow_com_total[4])?number_format(array_sum($today_outflow_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,1)">{{isset($today_outflow_com_total[1])?number_format(array_sum($today_outflow_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,5)">{{isset($today_outflow_com_total[5])?number_format(array_sum($today_outflow_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,6)">{{isset($today_outflow_com_total[6])?number_format(array_sum($today_outflow_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,41)">{{isset($today_outflow_com_total[41])?number_format(array_sum($today_outflow_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,3)">{{isset($today_outflow_com_total[3])?number_format(array_sum($today_outflow_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,21)">{{isset($today_outflow_com_total[21])?number_format(array_sum($today_outflow_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.todayoutflow(0,0)">{{number_format($todayOutflowTotal,0)}}</a></strong></td>
    </tr>

    <tr><td colspan="10" style="padding-left: 15px"><strong>Accounts Receivables</strong></td></tr>
    <?php
    $todayReceivableOpeningTotal=0;
    ?>
    @foreach($today_receivable_opening_arr as $todayreceivableopeninghead=>$todayreceivableopeningvalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$todayreceivableopeninghead}} </td>
    <td width="112" align="right">{{isset($todayreceivableopeningvalue[2])?number_format($todayreceivableopeningvalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivableopeningvalue[4])?number_format($todayreceivableopeningvalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($todayreceivableopeningvalue[1])?number_format($todayreceivableopeningvalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivableopeningvalue[5])?number_format($todayreceivableopeningvalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivableopeningvalue[6])?number_format($todayreceivableopeningvalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($todayreceivableopeningvalue[41])?number_format($todayreceivableopeningvalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivableopeningvalue[3])?number_format($todayreceivableopeningvalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivableopeningvalue[21])?number_format($todayreceivableopeningvalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $todayReceivableOpeningRowTotal=isset($today_receivable_opening_row_total[$todayreceivableopeninghead])?array_sum($today_receivable_opening_row_total[$todayreceivableopeninghead]):0;
    $todayReceivableOpeningTotal+=$todayReceivableOpeningRowTotal;
    ?>
    {{number_format($todayReceivableOpeningRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach


    <?php
    $todayReceivableTotal=0;
    ?>
    @foreach($today_receivable_arr as $todayreceivablehead=>$todayreceivablevalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$todayreceivablehead}} </td>
    <td width="112" align="right">{{isset($todayreceivablevalue[2])?number_format($todayreceivablevalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivablevalue[4])?number_format($todayreceivablevalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($todayreceivablevalue[1])?number_format($todayreceivablevalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivablevalue[5])?number_format($todayreceivablevalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivablevalue[6])?number_format($todayreceivablevalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($todayreceivablevalue[41])?number_format($todayreceivablevalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivablevalue[3])?number_format($todayreceivablevalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($todayreceivablevalue[21])?number_format($todayreceivablevalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $todayReceivableRowTotal=isset($today_receivable_row_total[$todayreceivablehead])?array_sum($today_receivable_row_total[$todayreceivablehead]):0;
    $todayReceivableTotal+=$todayReceivableRowTotal;
    ?>
    {{number_format($todayReceivableRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach



    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Receiveable</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[2])?number_format(array_sum($today_receivable_com_total[2]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[4])?number_format(array_sum($today_receivable_com_total[4]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[1])?number_format(array_sum($today_receivable_com_total[1]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[5])?number_format(array_sum($today_receivable_com_total[5]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[6])?number_format(array_sum($today_receivable_com_total[6]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[41])?number_format(array_sum($today_receivable_com_total[41]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[3])?number_format(array_sum($today_receivable_com_total[3]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_receivable_com_total[21])?number_format(array_sum($today_receivable_com_total[21]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{number_format($todayReceivableOpeningTotal+$todayReceivableTotal,0)}}</strong></td>
    </tr>




    <tr><td colspan="10" style="padding-left: 15px"><strong>Accounts Payable</strong></td></tr>
    <?php
    $todayPayableOpeningTotal=0;
    ?>
    @foreach($today_payable_opening_arr as $todaypayableopeninghead=>$todaypayableopeningvalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$todaypayableopeninghead}} </td>
    <td width="112" align="right">{{isset($todaypayableopeningvalue[2])?number_format($todaypayableopeningvalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayableopeningvalue[4])?number_format($todaypayableopeningvalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($todaypayableopeningvalue[1])?number_format($todaypayableopeningvalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayableopeningvalue[5])?number_format($todaypayableopeningvalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayableopeningvalue[6])?number_format($todaypayableopeningvalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($todaypayableopeningvalue[41])?number_format($todaypayableopeningvalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayableopeningvalue[3])?number_format($todaypayableopeningvalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayableopeningvalue[21])?number_format($todaypayableopeningvalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $todayPayableOpeningRowTotal=isset($today_payable_opening_row_total[$todaypayableopeninghead])?array_sum($today_payable_opening_row_total[$todaypayableopeninghead]):0;
    $todayPayableOpeningTotal+=$todayPayableOpeningRowTotal;
    ?>
    {{number_format($todayPayableOpeningRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach


    <?php
    $todayPayableTotal=0;
    ?>
    @foreach($today_payable_arr as $todaypayablehead=>$todaypayablevalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$todaypayablehead}} </td>
    <td width="112" align="right">{{isset($todaypayablevalue[2])?number_format($todaypayablevalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayablevalue[4])?number_format($todaypayablevalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($todaypayablevalue[1])?number_format($todaypayablevalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayablevalue[5])?number_format($todaypayablevalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayablevalue[6])?number_format($todaypayablevalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($todaypayablevalue[41])?number_format($todaypayablevalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayablevalue[3])?number_format($todaypayablevalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($todaypayablevalue[21])?number_format($todaypayablevalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $todayPayableRowTotal=isset($today_payable_row_total[$todaypayablehead])?array_sum($today_payable_row_total[$todaypayablehead]):0;
    $todayPayableTotal+=$todayPayableRowTotal;
    ?>
    {{number_format($todayPayableRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach



    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Payable</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[2])?number_format(array_sum($today_payable_com_total[2]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[4])?number_format(array_sum($today_payable_com_total[4]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[1])?number_format(array_sum($today_payable_com_total[1]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[5])?number_format(array_sum($today_payable_com_total[5]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[6])?number_format(array_sum($today_payable_com_total[6]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[41])?number_format(array_sum($today_payable_com_total[41]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[3])?number_format(array_sum($today_payable_com_total[3]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($today_payable_com_total[21])?number_format(array_sum($today_payable_com_total[21]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{number_format($todayPayableOpeningTotal+$todayPayableTotal,0)}}</strong></td>
    </tr>
    
</table>



<br/>
<table  border="1" style="margin: 0 auto;">
    <thead>
        <tr style="background-color: #EAE9E9">
        <th width="300" class="text-left">Particulars</th>
        <th width="112" class="text-center">A21</th>
        <th width="112" class="text-center">FFL</th>
        <th width="114" class="text-center">LAL</th>
        <th width="114" class="text-center">FDL</th>
        <th width="114" class="text-center">FPL</th>
        <th width="114" class="text-center">LFL</th>
        <th width="114" class="text-center">MIL</th>
        <th width="114" class="text-center">HO</th>
        <th width="114" class="text-center">TOTAL</th>
        </tr>
    </thead>

    <tr><td colspan="10"></td></tr>
    <tr><td colspan="10"><strong>Month Performance</strong></td></tr>
    <tr><td colspan="10" style="padding-left: 15px"><strong>Sales Generated</strong></td></tr>
    <tr>
    <td width="300" style="padding-left: 30px">Operating Revenue</td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,2)">{{isset($month_revenue_arr[16][2])?number_format($month_revenue_arr[16][2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,4)">{{isset($month_revenue_arr[16][4])?number_format($month_revenue_arr[16][4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,1)">{{isset($month_revenue_arr[16][1])?number_format($month_revenue_arr[16][1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,5)">{{isset($month_revenue_arr[16][5])?number_format($month_revenue_arr[16][5],0):''}}</a></td>

    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,6)">{{isset($month_revenue_arr[16][6])?number_format($month_revenue_arr[16][6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,41)">

    {{isset($month_revenue_arr[16][41])?number_format($month_revenue_arr[16][41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,3)">
    {{isset($month_revenue_arr[16][3])?number_format($month_revenue_arr[16][3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,21)">{{isset($month_revenue_arr[16][21])?number_format($month_revenue_arr[16][21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $row_total16=isset($month_revenue_arr[16])?array_sum($month_revenue_arr[16]):0;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(16,0)">
    {{number_format($row_total16,0)}}
    </a>
        
    </td>
    </tr>
    <tr>
    <td width="300" style="padding-left: 30px">Non-Operating Revenue</td>
    <td width="112" align="right">
        <a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,2)">{{isset($month_revenue_arr[25][2])?number_format($month_revenue_arr[25][2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,4)">{{isset($month_revenue_arr[25][4])?number_format($month_revenue_arr[25][4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,1)">{{isset($month_revenue_arr[25][1])?number_format($month_revenue_arr[25][1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,5)">{{isset($month_revenue_arr[25][5])?number_format($month_revenue_arr[25][5],0):''}}</a></td>

    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,6)">{{isset($month_revenue_arr[25][6])?number_format($month_revenue_arr[25][6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,41)">

    {{isset($month_revenue_arr[25][41])?number_format($month_revenue_arr[25][41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,3)">{{isset($month_revenue_arr[25][3])?number_format($month_revenue_arr[25][3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,21)">{{isset($month_revenue_arr[25][21])?number_format($month_revenue_arr[25][21],0):''}}</a>
    </td>
    <td align="right">
    <?php
    $row_total25=isset($month_revenue_arr[25])?array_sum($month_revenue_arr[25]):0;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(25,0)">
    {{number_format($row_total25,0)}}
    </a>
    </td>
    </tr>

    <tr style="background-color: #EAE9E9">
    <td width="300"style="padding-left: 15px"><strong>Total Revenue</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,2)">{{isset($month_revenue_com_total[2])?number_format(array_sum($month_revenue_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,4)">{{isset($month_revenue_com_total[4])?number_format(array_sum($month_revenue_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,1)">{{isset($month_revenue_com_total[1])?number_format(array_sum($month_revenue_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,5)">{{isset($month_revenue_com_total[5])?number_format(array_sum($month_revenue_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,6)">{{isset($month_revenue_com_total[6])?number_format(array_sum($month_revenue_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,41)">{{isset($month_revenue_com_total[41])?number_format(array_sum($month_revenue_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,3)">{{isset($month_revenue_com_total[3])?number_format(array_sum($month_revenue_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,21)">{{isset($month_revenue_com_total[21])?number_format(array_sum($month_revenue_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthrevenue(0,0)">{{number_format($row_total16+$row_total25,0)}}</a></strong></td>
    </tr>

    <tr><td colspan="10" style="padding-left: 15px"><strong>Cash Inflow</strong></td></tr>
    <?php
    $monthInflowTotal=0;
    ?>
    @foreach($month_inflow_arr as $monthinflowhead=>$monthinflowvalue)
    <?php
    $head=explode('::',$monthinflowhead);
    ?>
    <tr>
    <td width="300" style="padding-left: 30px"> {{$head[1]}} </td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},2)">{{isset($monthinflowvalue[2])?number_format($monthinflowvalue[2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},4)">{{isset($monthinflowvalue[4])?number_format($monthinflowvalue[4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},1)">{{isset($monthinflowvalue[1])?number_format($monthinflowvalue[1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},5)">
    {{isset($monthinflowvalue[5])?number_format($monthinflowvalue[5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},6)">{{isset($monthinflowvalue[6])?number_format($monthinflowvalue[6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},41)">
    {{isset($monthinflowvalue[41])?number_format($monthinflowvalue[41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},3)">{{isset($monthinflowvalue[3])?number_format($monthinflowvalue[3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},21)">{{isset($monthinflowvalue[21])?number_format($monthinflowvalue[21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $monthInflowRowTotal=isset($month_inflow_row_total[$monthinflowhead])?array_sum($month_inflow_row_total[$monthinflowhead]):0;
    $monthInflowTotal+= $monthInflowRowTotal;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow({{$head[0]}},0)">
    {{number_format($monthInflowRowTotal,0)}}
    </a>
    </td>
    </tr>
    @endforeach

    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Cash Inflow</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,2)">{{isset($month_inflow_com_total[2])?number_format(array_sum($month_inflow_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,4)">{{isset($month_inflow_com_total[4])?number_format(array_sum($month_inflow_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,1)">{{isset($month_inflow_com_total[1])?number_format(array_sum($month_inflow_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,5)">{{isset($month_inflow_com_total[5])?number_format(array_sum($month_inflow_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,6)">{{isset($month_inflow_com_total[6])?number_format(array_sum($month_inflow_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,41)">{{isset($month_inflow_com_total[41])?number_format(array_sum($month_inflow_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,3)">{{isset($month_inflow_com_total[3])?number_format(array_sum($month_inflow_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,21)">{{isset($month_inflow_com_total[21])?number_format(array_sum($month_inflow_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthinflow(0,0)">{{number_format($monthInflowTotal,0)}}</a></strong></td>
    </tr>


    

    <tr><td colspan="10" style="padding-left: 15px"><strong>Cash Outflow</strong></td></tr>
    <?php
    $monthOutflowTotal=0;
    ?>
    @foreach($month_outflow_arr as $monthoutflowhead=>$monthoutflowvalue)
    <?php
    $head=explode('::',$monthoutflowhead);
    ?>
    <tr>
    <td width="300" style="padding-left: 30px"> {{$head[1]}} </td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},2)">{{isset($monthoutflowvalue[2])?number_format($monthoutflowvalue[2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},4)">
    {{isset($monthoutflowvalue[4])?number_format($monthoutflowvalue[4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},1)">{{isset($monthoutflowvalue[1])?number_format($monthoutflowvalue[1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},5)">{{isset($monthoutflowvalue[5])?number_format($monthoutflowvalue[5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},6)">{{isset($monthoutflowvalue[6])?number_format($monthoutflowvalue[6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},41)">
    {{isset($monthoutflowvalue[41])?number_format($monthoutflowvalue[41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},3)">{{isset($monthoutflowvalue[3])?number_format($monthoutflowvalue[3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},21)">{{isset($monthoutflowvalue[21])?number_format($monthoutflowvalue[21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $monthOutflowRowTotal=isset($month_outflow_row_total[$monthoutflowhead])?array_sum($month_outflow_row_total[$monthoutflowhead]):0;
    $monthOutflowTotal+=$monthOutflowRowTotal;
    ?>
    <a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow({{$head[0]}},0)">
    {{number_format($monthOutflowRowTotal,0)}}
    </a>
    </td>
    </tr>
    @endforeach

    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Cash Outflow</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,2)">{{isset($month_outflow_com_total[2])?number_format(array_sum($month_outflow_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,4)">{{isset($month_outflow_com_total[4])?number_format(array_sum($month_outflow_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,1)">{{isset($month_outflow_com_total[1])?number_format(array_sum($month_outflow_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,5)">{{isset($month_outflow_com_total[5])?number_format(array_sum($month_outflow_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,6)">{{isset($month_outflow_com_total[6])?number_format(array_sum($month_outflow_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,41)">{{isset($month_outflow_com_total[41])?number_format(array_sum($month_outflow_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,3)">{{isset($month_outflow_com_total[3])?number_format(array_sum($month_outflow_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,21)">{{isset($month_outflow_com_total[21])?number_format(array_sum($month_outflow_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsTodayAccount.monthoutflow(0,0)">{{number_format($monthOutflowTotal,0)}}</a></strong></td>
    </tr>


    <tr><td colspan="10" style="padding-left: 15px"><strong>Accounts Receivables</strong></td></tr>
    <?php
    $monthReceivableOpeningTotal=0;
    ?>
    @foreach($month_receivable_opening_arr as $monthreceivableopeninghead=>$monthreceivableopeningvalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$monthreceivableopeninghead}} </td>
    <td width="112" align="right">{{isset($monthreceivableopeningvalue[2])?number_format($monthreceivableopeningvalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivableopeningvalue[4])?number_format($monthreceivableopeningvalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($monthreceivableopeningvalue[1])?number_format($monthreceivableopeningvalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivableopeningvalue[5])?number_format($monthreceivableopeningvalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivableopeningvalue[6])?number_format($monthreceivableopeningvalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($monthreceivableopeningvalue[41])?number_format($monthreceivableopeningvalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivableopeningvalue[3])?number_format($monthreceivableopeningvalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivableopeningvalue[21])?number_format($monthreceivableopeningvalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $monthReceivableOpeningRowTotal=isset($month_receivable_opening_row_total[$monthreceivableopeninghead])?array_sum($month_receivable_opening_row_total[$monthreceivableopeninghead]):0;
    $monthReceivableOpeningTotal+=$monthReceivableOpeningRowTotal;
    ?>
    {{number_format($monthReceivableOpeningRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach


    <?php
    $monthReceivableTotal=0;
    ?>
    @foreach($month_receivable_arr as $monthreceivablehead=>$monthreceivablevalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$monthreceivablehead}} </td>
    <td width="112" align="right">{{isset($monthreceivablevalue[2])?number_format($monthreceivablevalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivablevalue[4])?number_format($monthreceivablevalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($monthreceivablevalue[1])?number_format($monthreceivablevalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivablevalue[5])?number_format($monthreceivablevalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivablevalue[6])?number_format($monthreceivablevalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($monthreceivablevalue[41])?number_format($monthreceivablevalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivablevalue[3])?number_format($monthreceivablevalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($monthreceivablevalue[21])?number_format($monthreceivablevalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $monthReceivableRowTotal=isset($month_receivable_row_total[$monthreceivablehead])?array_sum($month_receivable_row_total[$monthreceivablehead]):0;
    $monthReceivableTotal+=$monthReceivableRowTotal;
    ?>
    {{number_format($monthReceivableRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach



    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Receiveable</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[2])?number_format(array_sum($month_receivable_com_total[2]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[4])?number_format(array_sum($month_receivable_com_total[4]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[1])?number_format(array_sum($month_receivable_com_total[1]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[5])?number_format(array_sum($month_receivable_com_total[5]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[6])?number_format(array_sum($month_receivable_com_total[6]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[41])?number_format(array_sum($month_receivable_com_total[41]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[3])?number_format(array_sum($month_receivable_com_total[3]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_receivable_com_total[21])?number_format(array_sum($month_receivable_com_total[21]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{number_format($monthReceivableOpeningTotal+$monthReceivableTotal,0)}}</strong></td>
    </tr>

    <tr><td colspan="10" style="padding-left: 15px"><strong>Accounts Payable</strong></td></tr>
    <?php
    $monthPayableOpeningTotal=0;
    ?>
    @foreach($month_payable_opening_arr as $monthpayableopeninghead=>$monthpayableopeningvalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$monthpayableopeninghead}} </td>
    <td width="112" align="right">{{isset($monthpayableopeningvalue[2])?number_format($monthpayableopeningvalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayableopeningvalue[4])?number_format($monthpayableopeningvalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($monthpayableopeningvalue[1])?number_format($monthpayableopeningvalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayableopeningvalue[5])?number_format($monthpayableopeningvalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayableopeningvalue[6])?number_format($monthpayableopeningvalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($monthpayableopeningvalue[41])?number_format($monthpayableopeningvalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayableopeningvalue[3])?number_format($monthpayableopeningvalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayableopeningvalue[21])?number_format($monthpayableopeningvalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $monthPayableOpeningRowTotal=isset($month_payable_opening_row_total[$monthpayableopeninghead])?array_sum($month_payable_opening_row_total[$monthpayableopeninghead]):0;
    $monthPayableOpeningTotal+=$monthPayableOpeningRowTotal;
    ?>
    {{number_format($monthPayableOpeningRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach


    <?php
    $monthPayableTotal=0;
    ?>
    @foreach($month_payable_arr as $monthpayablehead=>$monthpayablevalue)
    <tr>
    <td width="300" style="padding-left: 30px"> {{$monthpayablehead}} </td>
    <td width="112" align="right">{{isset($monthpayablevalue[2])?number_format($monthpayablevalue[2],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayablevalue[4])?number_format($monthpayablevalue[4],0):''}}</td>
    <td width="114" align="right">{{isset($monthpayablevalue[1])?number_format($monthpayablevalue[1],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayablevalue[5])?number_format($monthpayablevalue[5],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayablevalue[6])?number_format($monthpayablevalue[6],0):''}}</td>
    <td width="114" align="right">
    {{isset($monthpayablevalue[41])?number_format($monthpayablevalue[41],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayablevalue[3])?number_format($monthpayablevalue[3],0):''}}</td>
    <td width="112" align="right">{{isset($monthpayablevalue[21])?number_format($monthpayablevalue[21],0):''}}</td>
    <td width="114" align="right">
        <?php
    $monthPayableRowTotal=isset($month_payable_row_total[$monthpayablehead])?array_sum($month_payable_row_total[$monthpayablehead]):0;
    $monthPayableTotal+=$monthPayableRowTotal;
    ?>
    {{number_format($monthPayableRowTotal,0)}}
    
    </td>
    </tr>
    @endforeach



    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Payable</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[2])?number_format(array_sum($month_payable_com_total[2]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[4])?number_format(array_sum($month_payable_com_total[4]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[1])?number_format(array_sum($month_payable_com_total[1]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[5])?number_format(array_sum($month_payable_com_total[5]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[6])?number_format(array_sum($month_payable_com_total[6]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[41])?number_format(array_sum($month_payable_com_total[41]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[3])?number_format(array_sum($month_payable_com_total[3]),0):''}}</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{isset($month_payable_com_total[21])?number_format(array_sum($month_payable_com_total[21]),0):''}}</strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{number_format($monthPayableOpeningTotal+$monthPayableTotal,0)}}</strong></td>
    </tr>
    
</table>
