<h5 align="center">Inactive Summery({{date('d-M-Y',strtotime($date_from))}} - {{date('d-M-Y',strtotime($date_to))}})</h5>
<table border="1" cellpadding="3" cellspacing="2" style="margin: 0 auto">
  <tr>
    <th width="30">SL</th>
    <th width="200">Designation</th>
    @foreach ($monthArr as $month=>$month_name)
    <th width="70" class="text-center">{{ $month_name }}</th>
    @endforeach
    <th width="70" class="text-center">Total</th>
  </tr>
  <?php
    $i=1;
    $monthwiseTotalArr=[];
  ?>
  @foreach ($designationArr as $designation_id=>$designation_name)
  <?php
    $designationwiseTotal=0;
  ?>
  <tr>
    <td width="30" class="text-center">{{ $i++ }}</td>
    <td width="200">{{ $designation_name }}</td>
    @foreach ($monthArr as $key=>$value)
    <?php
    $no_emp=isset($monthwiseArr[$designation_id][$key])?$monthwiseArr[$designation_id][$key]:0;
    
    ?>
    <td width="70" class="text-center">{{ $no_emp }}</td>
    <?php
      $designationwiseTotal+=$no_emp;
      $monthwiseTotalArr[$key]=isset($monthwiseTotalArr[$key])?$monthwiseTotalArr[$key]+=$no_emp:$no_emp;
    ?>
    @endforeach
    <td width="70" class="text-center"><a href="javascript:void(0)" onClick="MsEmployeeInactiveSummery.designationEmployeeDetailWindow({{$designation_id}})"><strong>{{ $designationwiseTotal }}</strong></a></td>
  </tr>
  
  @endforeach
  <tr>
    <td width="30"></td>
    <td width="200"><strong>TOTAL</strong></td>
    @foreach ($monthArr as $month=>$month_name)
    <td width="70" class="text-center"><strong>{{ $monthwiseTotalArr[$month] }}</strong></td>
    @endforeach
    <td width="70" class="text-center"><strong>{{ array_sum( $monthwiseTotalArr) }}</strong></td>
  </tr>
</table>