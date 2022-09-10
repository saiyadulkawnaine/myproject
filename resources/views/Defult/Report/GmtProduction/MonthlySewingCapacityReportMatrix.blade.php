<h2 align="center">Lithe Group</h2>
<h4 align="center">Month: {{$months[$month_from]}} To {{$months[$month_to]}}</h4>
<table border="1" cellpadding="3" cellspacing="2">
 <thead>
  <tr>
   <th class="text-center" width="30" align="center" rowspan="2">Sl</th>
   <th class="text-center" width="100" align="center" rowspan="2">Company</th>
   <th class="text-center" width="100" align="center" rowspan="2">Location</th>
   <th class="text-center" width="100" align="center" rowspan="2">Prod Source</th>
   <th class="text-center" width="100" align="center" rowspan="2">Year</th>
   <th class="text-center" width="100" align="center" colspan="3">Marketing Capacity</th>
   <th class="text-center" width="100" align="center" colspan="3">Production Capacity</th>
  </tr>
  <tr>
   <th class="text-center" width="100" align="center">In Minute</th>
   <th class="text-center" width="100" align="center">In Hour</th>
   <th class="text-center" width="100" align="center">Basic Qty</th>
   <th class="text-center" width="100" align="center">In Minute</th>
   <th class="text-center" width="100" align="center">In Hour</th>
   <th class="text-center" width="100" align="center">Basic Qty</th>
  </tr>
 </thead>
 <?php
        $i=1;
        $tMktMnt=0;
        $tMktHr=0;
        $tMktQty=0;
        $tProdMnt=0;
        $tProdHr=0;
        $tProdQty=0;
    ?>
 <tbody>
  @foreach ($results as $row)
  <tr>
   <td width="30" align="center">{{ $i++ }}</td>
   <td width="100" align="left">{{ $row->company_name }}</td>
   <td width="100" align="left">{{ $row->location_name }}</td>
   <td width="100" align="left">{{ $row->prod_source_id }}</td>
   <td width="100" align="center">{{ $row->year }}</td>
   <td width="100" align="right">{{ number_format($row->marketing_minute,0) }}</td>
   <td width="100" align="right">{{ number_format($row->marketing_hour,0) }}</td>
   <td width="100" align="right">{{ number_format($row->marketing_basic_qty,0) }}</td>
   <td width="100" align="right">{{ number_format($row->prod_minute,0) }}</td>
   <td width="100" align="right">{{ number_format($row->prod_hour,0) }}</td>
   <td width="100" align="right">{{ number_format($row->prod_basic_qty,0) }}</td>
  </tr>
  <?php
            $tMktMnt+=$row->marketing_minute;
            $tMktHr+=$row->marketing_hour;
            $tMktQty+=$row->marketing_basic_qty;
            $tProdMnt+=$row->prod_minute;
            $tProdHr+=$row->prod_hour;
            $tProdQty+=$row->prod_basic_qty;
        ?>
  @endforeach
 </tbody>
 <tfoot>
  <tr>
   <td width="430" align="left" colspan="5"><strong>Total</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tMktMnt,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tMktHr,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tMktQty,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tProdMnt,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tProdHr,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tProdQty,0) }}</strong></td>
  </tr>
 </tfoot>
</table>
@foreach ($datas as $company_id=>$data)
<table border="1" cellpadding="3" cellspacing="2">
 <caption style="font-weight: bold;font-size: 24px">{{ $company[$company_id] }}</caption>
 <thead>
  <tr>
   <th class="text-center" width="30" align="center" rowspan="2">Sl</th>
   <th class="text-center" width="100" align="center" rowspan="2">Location</th>
   <th class="text-center" width="100" align="center" rowspan="2">Prod Source</th>
   <th class="text-center" width="100" align="center" rowspan="2">Year</th>
   <th class="text-center" width="100" align="center" rowspan="2">Month</th>
   <th class="text-center" width="100" align="center" colspan="3">Marketing Capacity</th>
   <th class="text-center" width="100" align="center" colspan="3">Production Capacity</th>
  </tr>
  <tr>
   <th class="text-center" width="100" align="center">In Minute</th>
   <th class="text-center" width="100" align="center">In Hour</th>
   <th class="text-center" width="100" align="center">Basic Qty</th>
   <th class="text-center" width="100" align="center">In Minute</th>
   <th class="text-center" width="100" align="center">In Hour</th>
   <th class="text-center" width="100" align="center">Basic Qty</th>
  </tr>
 </thead>
 <?php
        $i=1;
        $tMktMnt=0;
        $tMktHr=0;
        $tMktQty=0;
        $tProdMnt=0;
        $tProdHr=0;
        $tProdQty=0;
    ?>
 <tbody>
  @foreach ($data as $row)
  <tr>
   <td width="30" align="center">{{ $i++ }}</td>
   <td width="100" align="left">{{ $row->location_name }}</td>
   <td width="100" align="left">{{ $row->prod_source_id }}</td>
   <td width="100" align="center">{{ $row->year }}</td>
   <td width="100" align="center">{{ $row->sew_month }}</td>
   <td width="100" align="right">{{ number_format($row->marketing_minute,0) }}</td>
   <td width="100" align="right">{{ number_format($row->marketing_hour,0) }}</td>
   <td width="100" align="right">{{ number_format($row->marketing_basic_qty,0) }}</td>
   <td width="100" align="right">{{ number_format($row->prod_minute,0) }}</td>
   <td width="100" align="right">{{ number_format($row->prod_hour,0) }}</td>
   <td width="100" align="right">{{ number_format($row->prod_basic_qty,0) }}</td>
  </tr>
  <?php
            $tMktMnt+=$row->marketing_minute;
            $tMktHr+=$row->marketing_hour;
            $tMktQty+=$row->marketing_basic_qty;
            $tProdMnt+=$row->prod_minute;
            $tProdHr+=$row->prod_hour;
            $tProdQty+=$row->prod_basic_qty;
        ?>
  @endforeach
 </tbody>
 <tfoot>
  <tr>
   <td width="430" align="left" colspan="5"><strong>Total</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tMktMnt,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tMktHr,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tMktQty,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tProdMnt,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tProdHr,0) }}</strong></td>
   <td width="100" align="right"><strong>{{ number_format($tProdQty,0) }}</strong></td>
  </tr>
 </tfoot>
</table>
@endforeach