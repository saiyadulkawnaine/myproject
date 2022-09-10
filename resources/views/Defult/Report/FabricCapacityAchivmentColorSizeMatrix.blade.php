<table border="1">

<tr align="center">
    <td> </td>
    <td>1</td>
    <td>2</td>
    <td>3</td>
    <td>4</td>
    {{-- <td>5</td> --}}
    <td>6</td>
    {{-- <td>7</td> --}}
    <td>8</td>
    {{-- <td>9</td> --}}
    <td>10</td>
    <td>11</td>
</tr>

<tr>
    <td>SL</td>
    <td>Particulars</td>
    <td>Yarn Receive</td>
    <td>Yarn Issue to Knitting</td>
    <td>Knitting</td>
    {{-- <td>Knitting WIP</td> --}}
    <td>Dyeing </td>
    {{-- <td>Dyeing WIP</td> --}}
    <td>Finishing  </td>
    {{-- <td>Finishing WIP </td> --}}
    <td>AOP </td>
    <td>Delv. To Cutting</td>
</tr>

<tr>
    <td> </td>
    <td>Head of Department</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>Man Power & Attendance</td>
</tr>

<tr>
    <td>1 </td>
    <td>No of Machine</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>2</td>
    <td>Total Employee </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>3</td>
    <td>Today Employee Present</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>4</td>
    <td>Today Absent </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>Today Performance</td>
</tr>

<tr align="right">
    <td align="left">5</td>
    <td align="left">Daily Prod. Capacity</td>
    <td> </td>
    <td> </td>
    <td>{{$dailyCapacity}} </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr align="right">
    <td align="left">6</td>
    <td align="left">Today Target</td>
    <td> </td>
    <td> </td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricKnitTodayTergetWindow()">{{$todayTerget}}</a></td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>7</td>
    <td>Today Idle Capacity</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>8</td>
    <td>Today Achievement</td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricTodayAchieveRcvYarnWindow()">Click</a></td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricTodayAchieveKnitYarnIssueWindow()">Click</a></td>
    <td align="right"> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricKnitTodayAchievementWindow()">{{number_format($todayknit,0)}}</a> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>9</td>
    <td>Today Target Variance</td>
    <td> </td>
    <td> </td>
    <td align="right">{{$todayknit-$todayTerget}} </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>10</td>
    <td>Today Capacity Variance</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>11</td>
    <td>Today Earnings</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>12</td>
    <td>Today Expenses</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>13</td>
    <td>Today Profit/(Loss)</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>
<tr>
    <td>Monthly Performance</td>
</tr>

<tr align="right">
    <td align="left">14</td>
    <td align="left">Monthly Prod. Capacity</td>
    <td> </td>
    <td> </td>
    <td>{{$monthCapacity}}</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr align="right">
    <td align="left">15</td>
    <td align="left">Month Target as per Order</td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthTargetWindow()">{{number_format($monthTarget['yarn'],0,'.',',')}}</a></td>
    <td> </td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthTargetWindow()">{{number_format($monthTarget['knit'],0,'.',',')}}</a></td>
   {{--  <td> </td> --}}
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthTargetWindow()">{{number_format($monthTarget['dyeing'],0,'.',',')}}</a></td>
   {{--  <td> </td> --}}
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthTargetWindow()">{{number_format($monthTarget['finish'],0,'.',',')}}</a></td>
   {{--  <td> </td> --}}
    <td> {{number_format($monthTarget['aop'],0,'.',',')}}</td>
    <td> </td>
</tr>

<tr>
    <td>16</td>
    <td>Month Idle Capacity</td>
    <td> </td>
    <td> </td>
    <td> {{ number_format($monthTarget['knit']-$monthCapacity,0,'.',',') }}</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>17</td>
    <td>Month Achievement</td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthAchieveRcvYarnWindow()">Click</a></td>
    <td> <a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthAchieveKnitYarnIssueWindow()">Click</a></td>
    <td align="right"><a href="javascript:void(0)" onclick="MsProdFabricCapacityAchievement.prodFabricMonthAchieveKnittingWindow()">{{number_format($monthAchivement['knit'],0,'.',',')}}</a> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>18</td>
    <td>Month Target Variance</td>
    <td> </td>
    <td> </td>
    <td align="right">{{number_format($monthAchivement['knit']-$monthTarget['knit'],0,'.',',')}} </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>19</td>
    <td>Month Capacity Variance</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>20</td>
    <td>Month Earnings</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>21</td>
    <td>Month Expenses</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>

<tr>
    <td>22</td>
    <td>Month Profit/(Loss)</td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    <td> </td>
    {{-- <td> </td>
    <td> </td>
    <td> </td> --}}
</tr>
</table>