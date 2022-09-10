<?php
$width=($rows->count()*200)+150+200;
?>

<table width="{{$width}}" border="1" style="border-style:dotted;line-height: 25px; margin: 0 auto">
<thead>
<tr align="right" style="background: #ccc;">
  <td align="center" colspan="{{$rows->count()*2+1+2}}">
    <font style="font-weight: bold;">Lithe Group</font><br/>
    56 AH Tower, Road 2, Sector 3, Uttara, Dhaka<br/>
    Budget Summary from {{$from}}  To {{$to}}
  </td>
</tr>
<tr>
  <th class="text-center" rowspan="2">
    Particulars
  </th>
  @foreach ($rows as $row)
  <th class="text-center" colspan="2">
    {{$row->rcv_month}}-{{$row->rcv_year}}
  </th>
   @endforeach
  <th class="text-center" colspan="2">
    Total
  </th>
</tr>
<tr>
  
  @foreach ($rows as $row)
  <th class="text-center">
    Qty
  </th>
  <th class="text-center">
    Amount
  </th>
   @endforeach 
   <th class="text-center">
    Qty
  </th>
  <th class="text-center">
    Amount
  </th>
</tr>
</thead>
<tbody>
<tr align="right" style="background: #ccc;font-weight: bold;">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    1. Cost Budget of Orders
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Order Value
  </td>
  <?php
  $totQty=0;
  $totAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->qty,0)}}
  </td>
  <td> 
    {{number_format($row->amount,0)}}
  </td>
  <?php
  $totQty+=$row->qty;
  $totAmount+=$row->amount;
  ?>
  @endforeach
  <td>
    {{number_format($totQty,0)}}
  </td>
  <td> 
    {{number_format($totAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Less Sales Commission
  </td>
  <?php
  $totCommiAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->commi_amount,0)}}
  </td>
  <?php
  $totCommiAmount+=$row->commi_amount;
  ?>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totCommiAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left">
    A. Net Order Value
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->amount-$row->commi_amount,0)}}
  </td>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totAmount-$totCommiAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    B. Raw Materials Cost
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Fabric Purchase 
  </td>
  <?php
  $totFabPurQty=0;
  $totFabPurAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->grey_fab_pur_req,0)}}
  </td>
  <td> 
    {{number_format($row->fab_pur_amount,0)}}
  </td>
  <?php
  $totFabPurQty+=$row->grey_fab_pur_req;
  $totFabPurAmount+=$row->fab_pur_amount;
  ?>
  @endforeach
  <td>
    {{number_format($totFabPurQty,0)}}
  </td>
  <td> 
    {{number_format($totFabPurAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Yarn 
  </td>
  <?php
  $totYarnQty=0;
  $totYarnAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->yarn_qty,0)}}
  </td>
  <td> 
    {{number_format($row->yarn_amount,0)}}
  </td>
  <?php
  $totYarnQty+=$row->yarn_qty;
  $totYarnAmount+=$row->yarn_amount;
  ?>
  @endforeach
  <td>
    {{number_format($totYarnQty,0)}}
  </td>
  <td> 
    {{number_format($totYarnAmount,0)}}
  </td>  
</tr>


<tr align="right">
  <td align="left" style="padding-left: 15px">
    Accessories  
  </td>
  <?php
  $totTrimAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->trim_amount,0)}}
  </td>
  <?php
  $totTrimAmount+=$row->trim_amount;
  ?>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totTrimAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left">
      
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->fab_pur_amount+$row->yarn_amount+$row->trim_amount,0)}}
  </td>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totFabPurAmount+$totYarnAmount+$totTrimAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    C. Fabric Production Cost
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Yarn Dyeing Cost 
  </td>
  <?php
  $totYarnDyeingQty=0;
  $totYarnDyeingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->yarn_dying_qty,0)}}
  </td>
  <td> 
    {{number_format($row->yarn_dying_amount,0)}}
  </td>
  <?php
  $totYarnDyeingQty+=$row->yarn_dying_qty;
  $totYarnDyeingAmount+=$row->yarn_dying_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totYarnDyeingQty,0)}}
  </td>
  <td> 
    {{number_format($totYarnDyeingAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Knitting Cost 
  </td>
  <?php
  $totKnitingQty=0;
  $totKnitingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->kniting_qty,0)}}
  </td>
  <td> 
    {{number_format($row->kniting_amount,0)}}
  </td>
  <?php
  $totKnitingQty+=$row->kniting_qty;
  $totKnitingAmount+=$row->kniting_amount;
  ?>
  @endforeach  
  <td>
    {{number_format($totKnitingQty,0)}}
  </td>
  <td> 
    {{number_format($totKnitingAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Dyeing   
  </td>
  <?php
  $totDyeingQty=0;
  $totDyeingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->dying_qty,0)}}
  </td>
  <td> 
    {{number_format($row->dying_amount,0)}}
  </td>
  <?php
  $totDyeingQty+=$row->dying_qty;
  $totDyeingAmount+=$row->dying_amount;
  ?>
  @endforeach
  <td>
    {{number_format($totDyeingQty,0)}}
  </td>
  <td> 
    {{number_format($totDyeingAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   AOP Cost   
  </td>
  <?php
  $totAopQty=0;
  $totAopAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->aop_qty,0)}}
  </td>
  <td> 
    {{number_format($row->aop_amount,0)}}
  </td>
  <?php
  $totAopQty+=$row->aop_qty;
  $totAopAmount+=$row->aop_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totAopQty,0)}}
  </td>
  <td> 
    {{number_format($totAopAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Burn Out   
  </td>
  <?php
  $totBurnoutQty=0;
  $totBurnoutAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->burnout_qty,0)}}
  </td>
  <td> 
    {{number_format($row->burnout_amount,0)}}
  </td>
  <?php
  $totBurnoutQty+=$row->burnout_qty;
  $totBurnoutAmount+=$row->burnout_qty;
  ?>
  @endforeach 
  <td>
    {{number_format($totBurnoutQty,0)}}
  </td>
  <td> 
    {{number_format($totBurnoutAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Washing   
  </td>
  <?php
  $totWashingQty=0;
  $totWashingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->washing_qty,0)}}
  </td>
  <td> 
    {{number_format($row->washing_amount,0)}}
  </td>
  <?php
  $totWashingQty+=$row->washing_qty;
  $totWashingAmount+=$row->washing_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totWashingQty,0)}}
  </td>
  <td> 
    {{number_format($totWashingAmount,0)}}
  </td> 
</tr>

<tr align="right">
  <td align="left">
      
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->yarn_dying_amount+$row->kniting_amount+$row->dying_amount+$row->aop_amount+$row->burnout_amount+$row->washing_amount,0)}}
  </td>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totYarnDyeingAmount+$totKnitingAmount+$totDyeingAmount+$totAopAmount+$totBurnoutAmount+$totWashingAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    D. Embellishment Cost
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Screen Printing Cost   
  </td>
  <?php
  $totPrintingQty=0;
  $totPrintingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->printing_qty,0)}}
  </td>
  <td> 
    {{number_format($row->printing_amount,0)}}
  </td>
  <?php
  $totPrintingQty+=$row->printing_qty;
  $totPrintingAmount+=$row->printing_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totPrintingQty,0)}}
  </td>
  <td> 
    {{number_format($totPrintingAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Embroidery Cost 
  </td>
  <?php
  $totEmbQty=0;
  $totEmbAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->emb_qty,0)}}
  </td>
  <td> 
    {{number_format($row->emb_amount,0)}}
  </td>
  <?php
  $totEmbQty+=$row->emb_qty;
  $totEmbAmount+=$row->emb_amount;
  ?>
  @endforeach
  <td>
    {{number_format($totEmbQty,0)}}
  </td>
  <td> 
    {{number_format($totEmbAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Special Embroidery Cost 
  </td>
  <?php
  $totSpEmbQty=0;
  $totSpEmbAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->spemb_qty,0)}}
  </td>
  <td> 
    {{number_format($row->spemb_amount,0)}}
  </td>
  <?php
  $totSpEmbQty+=$row->spemb_qty;
  $totSpEmbAmount+=$row->spemb_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totSpEmbQty,0)}}
  </td>
  <td> 
    {{number_format($totSpEmbAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   GMT Dyeing 
  </td>
  <?php
  $totGmtDyeingQty=0;
  $totGmtDyeingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->gmtdyeing_qty,0)}}
  </td>
  <td> 
    {{number_format($row->gmtdyeing_amount,0)}}
  </td>
  <?php
  $totGmtDyeingQty+=$row->gmtdyeing_qty;
  $totGmtDyeingAmount+=$row->gmtdyeing_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totGmtDyeingQty,0)}}
  </td>
  <td> 
    {{number_format($totGmtDyeingAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   GMT Washing 
  </td>
  <?php
  $totGmtWashingQty=0;
  $totGmtWashingAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->gmtwashing_qty,0)}}
  </td>
  <td> 
    {{number_format($row->gmtwashing_amount,0)}}
  </td>
  <?php
  $totGmtWashingQty+=$row->gmtwashing_qty;
  $totGmtWashingAmount+=$row->gmtwashing_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totGmtWashingQty,0)}}
  </td>
  <td> 
    {{number_format($totGmtWashingAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
      
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmtdyeing_amount+$row->gmtwashing_amount,0)}}
  </td>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totPrintingAmount+$totEmbAmount+$totSpEmbAmount+$totGmtDyeingAmount+$totGmtWashingAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    E. Other Cost
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Courier  
  </td>
  <?php
  $totCourierAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->courier_amount,0)}}
  </td>
  <?php
  $totCourierAmount+=$row->courier_amount;
  ?>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totCourierAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Lab Test   
  </td>
  <?php
  $totLabAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->lab_amount,0)}}
  </td>
  <?php
  $totLabAmount+=$row->lab_amount;
  ?>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totLabAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Inspection   
  </td>
  <?php
  $totInspAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->insp_amount,0)}}
  </td>
  <?php
  $totInspAmount+=$row->insp_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totInspAmount,0)}}
  </td> 
</tr>

<tr align="right">
  <td align="left" style="padding-left: 15px">
   Freight    
  </td>
  <?php
  $totFreiAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->frei_amount,0)}}
  </td>
  <?php
  $totFreiAmount+=$row->frei_amount;
  ?>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totFreiAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Operating Cost   
  </td>
  <?php
  $totOpaAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->opa_amount,0)}}
  </td>
  <?php
  $totOpaAmount+=$row->opa_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totOpaAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Depreciation   
  </td>
  <?php
  $totDepAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->dep_amount,0)}}
  </td>
  <?php
  $totDepAmount+=$row->dep_amount;
  ?>
  @endforeach
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totDepAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Cost of Capital   
  </td>
  <?php
  $totCocAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->coc_amount,0)}}
  </td>
  <?php
  $totCocAmount+=$row->coc_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totCocAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Income tax   
  </td>
  <?php
  $totIctAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->ict_amount,0)}}
  </td>
  <?php
  $totIctAmount+=$row->ict_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totIctAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Commercial     
  </td>
  <?php
  $totCommerAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->commer_amount,0)}}
  </td>
  <?php
  $totCommerAmount+=$row->commer_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totCommerAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
      
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->courier_amount+$row->lab_amount+$row->insp_amount+$row->frei_amount+$row->opa_amount+$row->dep_amount+$row->coc_amount+$row->ict_amount+$row->commer_amount,0)}}
  </td>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totCourierAmount+$totLabAmount+$totInspAmount+$totFreiAmount+$totOpaAmount+$totDepAmount+$totCocAmount+$totIctAmount+$totCommerAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
    F. Total Variable Cost (B+C+D+E) 
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->fab_pur_amount+$row->yarn_amount+$row->trim_amount+$row->yarn_dying_amount+$row->kniting_amount+$row->dying_amount+$row->aop_amount+$row->burnout_amount+$row->washing_amount+$row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmtdyeing_amount+$row->gmtwashing_amount+$row->courier_amount+$row->lab_amount+$row->insp_amount+$row->frei_amount+$row->opa_amount+$row->dep_amount+$row->coc_amount+$row->ict_amount+$row->commer_amount,0)}}
  </td>
  @endforeach
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totFabPurAmount+$totYarnAmount+$totTrimAmount+
      $totYarnDyeingAmount+
      $totKnitingAmount+
      $totDyeingAmount+
      $totAopAmount+
      $totBurnoutAmount+
      $totWashingAmount+

      $totPrintingAmount+$totEmbAmount+$totSpEmbAmount+$totGmtDyeingAmount+$totGmtWashingAmount+$totCourierAmount+$totLabAmount+$totInspAmount+$totFreiAmount+$totOpaAmount+$totDepAmount+$totCocAmount+$totIctAmount+$totCommerAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left">
    G. Contribution Margin (A-F) 
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->amount-($row->commi_amount+$row->fab_pur_amount+$row->yarn_amount+$row->trim_amount+$row->yarn_dying_amount+$row->kniting_amount+$row->dying_amount+$row->aop_amount+$row->burnout_amount+$row->washing_amount+$row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmtdyeing_amount+$row->gmtwashing_amount+$row->courier_amount+$row->lab_amount+$row->insp_amount+$row->frei_amount+$row->opa_amount+$row->dep_amount+$row->coc_amount+$row->ict_amount+$row->commer_amount),0)}}
  </td>
  @endforeach
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totAmount-($totCommiAmount+$totFabPurAmount+$totYarnAmount+$totTrimAmount+

      

      $totYarnDyeingAmount+
      $totKnitingAmount+
      $totDyeingAmount+
      $totAopAmount+
      $totBurnoutAmount+
      $totWashingAmount+

      $totPrintingAmount+$totEmbAmount+$totSpEmbAmount+$totGmtDyeingAmount+$totGmtWashingAmount+$totCourierAmount+$totLabAmount+$totInspAmount+$totFreiAmount+$totOpaAmount+$totDepAmount+$totCocAmount+$totIctAmount+$totCommerAmount),0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   CM Cost     
  </td>
  <?php
  $totCmAmount=0;
  ?>
  @foreach ($rows as $row)
  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->cm_amount,0)}}
  </td>
  <?php
  $totCmAmount+=$row->cm_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totCmAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
    Net Profit/Loss 
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->amount-($row->cm_amount+$row->commi_amount+$row->fab_pur_amount+$row->yarn_amount+$row->trim_amount+$row->yarn_dying_amount+$row->kniting_amount+$row->dying_amount+$row->aop_amount+$row->burnout_amount+$row->washing_amount+$row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmtdyeing_amount+$row->gmtwashing_amount+$row->courier_amount+$row->lab_amount+$row->insp_amount+$row->frei_amount+$row->opa_amount+$row->dep_amount+$row->coc_amount+$row->ict_amount+$row->commer_amount),0)}}
  </td>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totAmount-($totCmAmount+$totCommiAmount+$totFabPurAmount+$totYarnAmount+$totTrimAmount+
      $totYarnDyeingAmount+
      $totKnitingAmount+
      $totDyeingAmount+
      $totAopAmount+
      $totBurnoutAmount+
      $totWashingAmount+
      $totPrintingAmount+$totEmbAmount+$totSpEmbAmount+$totGmtDyeingAmount+$totGmtWashingAmount+$totCourierAmount+$totLabAmount+$totInspAmount+$totFreiAmount+$totOpaAmount+$totDepAmount+$totCocAmount+$totIctAmount+$totCommerAmount),0)}}
  </td>
</tr>
<tr align="right" style="background: #ccc;font-weight: bold;">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    2. Procurement Status
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
    Fabric Required (Purchase)
  </td>
  <?php
  $totFabPurQty=0;
  $totFabPurAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->grey_fab_pur_req,0)}}
  </td>
  <td> 
    {{number_format($row->fab_pur_amount,0)}}
  </td>
  <?php
  $totFabPurQty+=$row->grey_fab_pur_req;
  $totFabPurAmount+=$row->fab_pur_amount;
  ?>
  @endforeach
  <td>
    {{number_format($totFabPurQty,0)}}
  </td>
  <td> 
    {{number_format($totFabPurAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   LC Opened for Fabric     
  </td>
  <?php
  $totLcFabricQty=0;
  $totLcFabricAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->lc_fabric_qty,0)}}
  </td>
  <td> 
    {{number_format($row->lc_fabric_amount,0)}}
  </td>
  <?php
  $totLcFabricQty+=$row->lc_fabric_qty;
  $totLcFabricAmount+=$row->lc_fabric_amount;
  ?>
  @endforeach  
  <td>
    {{number_format($totLcFabricQty,0)}}
  </td>
  <td> 
    {{number_format($totLcFabricAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
   Yet to LC Opened for Fabric     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->grey_fab_pur_req-$row->lc_fabric_qty,0)}}
  </td>
  <td> 
    {{number_format($row->fab_pur_amount-$row->lc_fabric_amount,0)}}
  </td>
  @endforeach 
  <td>
    {{number_format($totFabPurQty-$totLcFabricQty,0)}}
  </td>
  <td> 
    {{number_format($totFabPurAmount-$totLcFabricAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Yarn Required     
  </td>
  <?php
  $totYarnQty=0;
  $totYarnAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->yarn_qty,0)}}
  </td>
  <td> 
    {{number_format($row->yarn_amount,0)}}
  </td>
  <?php
  $totYarnQty+=$row->yarn_qty;
  $totYarnAmount+=$row->yarn_amount;
  ?>
  @endforeach 
  <td>
    {{number_format($totYarnQty,0)}}
  </td>
  <td> 
    {{number_format($totYarnAmount,0)}}
  </td> 
</tr>

<tr align="right">
  <td align="left" style="padding-left: 15px">
   LC Opened for Yarn     
  </td>
  <?php
  $totLcYarnQty=0;
  $totLcYarnAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->lc_yarn_qty,0)}}
  </td>
  <td> 
    {{number_format($row->lc_yarn_amount,0)}}
  </td>
  <?php
  $totLcYarnQty+=$row->lc_yarn_qty;
  $totLcYarnAmount+=$row->lc_yarn_amount;
  ?>
  @endforeach  
  <td>
    {{number_format($totLcYarnQty,0)}}
  </td>
  <td> 
    {{number_format($totLcYarnAmount,0)}}
  </td> 
</tr>

<tr align="right">
  <td align="left">
   Yet to LC Opened for Yarn     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->yarn_qty-$row->lc_yarn_qty,0)}}
  </td>
  <td> 
    {{number_format($row->yarn_amount-$row->lc_yarn_amount,0)}}
  </td>
  @endforeach 
  <td>
    {{number_format($totYarnQty-$totLcYarnQty,0)}}
  </td>
  <td> 
    {{number_format($totYarnAmount-$totLcYarnAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Accessories Required     
  </td>
  <?php
  $totTrimAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->trim_amount,0)}}
  </td>
  <?php
  $totTrimAmount+=$row->trim_amount;
  ?>
  @endforeach 
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totTrimAmount,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   LC Opened for Accessories     
  </td>
  <?php
  $totLcTrimAmount=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->lc_trim_amount,0)}}
  </td>
  <?php
  $totLcTrimAmount+=$row->lc_trim_amount;
  ?>
  @endforeach
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totLcTrimAmount,0)}}
  </td>  
</tr>
<tr align="right">
  <td align="left">
   Yet to LC Opened for Accessories     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($row->trim_amount-$row->lc_trim_amount,0)}}
  </td>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format($totTrimAmount-$totLcTrimAmount,0)}}
  </td>
</tr>
<tr align="right">
  <td align="left">
   LC need to open for Fabric, Yarn and Accessories     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format(($row->fab_pur_amount-$row->lc_fabric_amount)+($row->yarn_amount-$row->lc_yarn_amount)+($row->trim_amount-$row->lc_trim_amount),0)}}
  </td>
  @endforeach  
  <td>
    {{number_format(0,0)}}
  </td>
  <td> 
    {{number_format(($totFabPurAmount-$totLcFabricAmount)+($totYarnAmount-$totLcYarnAmount)+($totTrimAmount-$totLcTrimAmount),0)}}
  </td>
</tr>
<tr align="right" style="background: #ccc;font-weight: bold;">
  <td align="left" colspan="{{$rows->count()*2+1+2}}">
    3. Fabric Process Loss
  </td>
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Grey Fabric Required (Kg)     
  </td>
  <?php
  $totGreyFab=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->grey_fab,0)}}
  </td>
  <td> 
    {{number_format(0,0)}}
  </td>
  <?php
  $totGreyFab+=$row->grey_fab;
  ?>
  @endforeach 
  <td>
    {{number_format($totGreyFab,0)}}
  </td>
  <td> 
    {{number_format(0,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left" style="padding-left: 15px">
   Finished Fabric Required (Kg)     
  </td>
  <?php
  $totFinFab=0;
  ?>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->fin_fab,0)}}
  </td>
  <td> 
    {{number_format(0,0)}}
  </td>
  <?php
  $totFinFab+=$row->fin_fab;
  ?>
  @endforeach 
  <td>
    {{number_format($totFinFab,0)}}
  </td>
  <td> 
    {{number_format(0,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
   Process Loss (Kg)     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format($row->grey_fab-$row->fin_fab,0)}}
  </td>
  <td> 
    {{number_format(0,0)}}
  </td>
  @endforeach 
   <td>
    {{number_format($totGreyFab-$totFinFab,0)}}
  </td>
  <td> 
    {{number_format(0,0)}}
  </td> 
</tr>

<tr align="right">
  <td align="left">
   Process Loss %     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format((($row->grey_fab-$row->fin_fab)/$row->grey_fab)*100,0)}} %
  </td>
  <td> 
    {{number_format(0,0)}}
  </td>
  @endforeach 
   <td>
    {{number_format((($totGreyFab-$totFinFab)/$totGreyFab)*100,0)}} %
  </td>
  <td> 
    {{number_format(0,0)}}
  </td> 
</tr>
<tr align="right">
  <td align="left">
   Standard Process Loss %     
  </td>
  @foreach ($rows as $row)
  <td>
    {{number_format($stndardprocesslossper,0)}} %
  </td>
  <td> 
    {{number_format(0,0)}}
  </td>
  @endforeach 
   <td>
    {{number_format($stndardprocesslossper,0)}} %
  </td>
  <td> 
    {{number_format(0,0)}}
  </td> 
</tr>
</tbody>
</table>