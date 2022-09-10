@if($color_size_matrix==1)
<table border="1" style="border-style:dotted">
    <tr align="center">
    <td width="100px">Color/Size</td>
    <?php 
    //print_r($datas);
    //die;
    ?>
    @foreach($sizes as $sizeid=>$size_name)
    <td width="70px">{{$size_name}}</td>
    @endforeach
    <td>Total</td>
    </tr>
    <tbody>

        @foreach($colors as $colorid=>$color_name)
            <tr>
                    <td width="100px">
                    {{ $color_name }}
                    </td>

                    @foreach($sizes as $sizeid=>$size_name)
                    <td width="70px" align="right">{{isset($datas[$colorid][$sizeid])?array_sum($datas[$colorid][$sizeid]):0}}</td>
                    @endforeach
                    <td align="right">{{isset($color_total[$colorid])?array_sum($color_total[$colorid]):0}}</td>
                    
                    
            </tr>
        @endforeach
    </tbody>
    <tr>
    <td width="100px">Total</td>
    <?php
    $totqty=0;
    ?>
    @foreach($sizes as $sizeid=>$size_name)
    <?php
    $totqty+=isset($size_total[$sizeid])?array_sum($size_total[$sizeid]):0;
    ?>
    <td width="70px" align="right">{{isset($size_total[$sizeid])?array_sum($size_total[$sizeid]):0}}</td>
    @endforeach
    <td align="right">{{$totqty}}</td>
    </tr>
</table>
<p><strong>Country Break Down</strong></p>


@foreach($country as $countriid=>$countriname)
<table border="1" style="border-style:dotted">
    <tr align="center">
    <td width="100px" colspan="{{3+count($country_sizes[$countriid])}}"><strong>{{$countriname}}</strong></td>
    </tr>
    <tr align="center">
    <td width="100px">Color/Size</td>
    @foreach($country_sizes[$countriid] as $sizeid=>$size_name)
    <td width="70px">{{$size_name}}</td>
    @endforeach
    <td>Total</td>
    </tr>
    <tbody>

        @foreach($country_colors[$countriid] as $colorid=>$color_name)
            <tr>
                    <td width="100px">
                    {{ $color_name }}
                    </td>

                    @foreach($country_sizes[$countriid] as $sizeid=>$size_name)
                    <td width="70px" align="right">{{ isset($country_datas[$countriid][$colorid][$sizeid])?array_sum($country_datas[$countriid][$colorid][$sizeid]):0}}</td>
                    @endforeach
                    <td align="right">{{isset($country_color_total[$countriid][$colorid])?array_sum($country_color_total[$countriid][$colorid]):0}}</td>
                    
                    
            </tr>
        @endforeach
    </tbody>
    <tr>
    <td width="100px">Total</td>
    <?php
    $totqty=0;
    ?>
    @foreach($country_sizes[$countriid] as $sizeid=>$size_name)
    <?php
    $totqty+=isset($country_size_total[$countriid][$sizeid])?array_sum($country_size_total[$countriid][$sizeid]):0;
    ?>
    <td width="70px" align="right">{{isset($country_size_total[$countriid][$sizeid])?array_sum($country_size_total[$countriid][$sizeid]):0}}</td>
    @endforeach
    <td align="right">{{$totqty}}</td>
    </tr>
</table>
<p></p>
@endforeach
@endif
@if($color_size_matrix==0)
<?php $k=0; ?>
@foreach($comRangeArr as $key=>$data)
<br/>
<strong>{{$company_name[$key]}}</strong>
<table border="1" style="border-style:dotted">
    <tr align="center">
        <td width="100px" rowspan="2" align="left">Particulars</td>
        <td width="70px" rowspan="2">TTL</td>
        <td width="70px" rowspan="2">Line to be Set</td>
        <td width="70px" rowspan="2">In-active Line</td>
        <td width="70px" rowspan="2">Active Line</td>
        <?php
        $i=1;
        ?>
        @foreach($data as $value)
        <td width="100px" colspan="2">{{$header[$i]['month_from']}}- {{$header[$i]['month_to']}}</td>
        <?php
        $i++;
        ?>
        @endforeach
         @if($i>2)
        <td width="100px" colspan="2">Total</td>
         @endif
    </tr>
    <tr align="center">
        @foreach($data as $value)
        <td width="100px">Qty</td>
        <td width="100px">Value</td>
        @endforeach
        @if($i>2)
        <td width="100px">Qty</td>
        <td width="100px">Value</td>
        @endif
    </tr>
    <tr align="center">
        <td width="100px" align="left">Capacity</td>
        
        <td width="70px">{{$comlineinfo[$key]['ttl']}}</td>
        <td width="70px">{{$comlineinfo[$key]['projected']}}</td>
        <td width="70px">{{$comlineinfo[$key]['inactive']}}</td>
        <td width="70px">{{$comlineinfo[$key]['active']}}</td>
        <?php
        $totCapQty=0;
        $totCapAmt=0;
        $totOrdQty=0;
        $totOrdAmt=0;
        $totNeedQty=0;
        $totNeedAmt=0;
        $totMktQty=0;
        $totMktAmt=0;
        ?>
        @foreach($data as $row)
        <?php
        $totCapQty+=$row->cap_qty;
        $totCapAmt+=$row->cap_amount;
        ?>
        <td width="100px" align="right">{{number_format($row->cap_qty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($row->cap_amount,2,'.',',')}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{number_format($totCapQty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($totCapAmt,2,'.',',')}}</td>
        @endif
    </tr>
    <tr align="center">
        <td width="100px" align="left">Order in Hand</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        @foreach($data as $row)
        <?php
        $totOrdQty+=$row->qty;
        $totOrdAmt+=$row->amount;
        ?>
        <td width="100px" align="right">{{number_format($row->qty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($row->amount,2,'.',',')}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{number_format($totOrdQty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($totOrdAmt,2,'.',',')}}</td>
        @endif
    </tr>
     <tr align="center">
        <td width="100px" align="left">Need</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        @foreach($data as $row)
        <?php
        $totNeedQty+=($row->ned_qty);
        $totNeedAmt+=($row->ned_amount);
        ?>
        <td width="100px" align="right">{{print_with_bracket($row->ned_qty,0)}}</td>
        <td width="100px" align="right">{{print_with_bracket($row->ned_amount,2)}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{print_with_bracket($totNeedQty,0)}}</td>
        <td width="100px" align="right">{{print_with_bracket($totNeedAmt,2)}}</td>
        @endif
    </tr>
    <tr align="center">
        <td width="100px" align="left">Projection</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        @foreach($data as $row)
        <?php
        $totMktQty+=$row->mkt_qty;
        $totMktAmt+=$row->mkt_amount;
        ?>
        <td width="100px" align="right">{{number_format($row->mkt_qty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($row->mkt_amount,2,'.',',')}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{number_format($totMktQty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($totMktAmt,2,'.',',')}}</td>
        @endif
    </tr>
</table>
<?php $k++; ?>
@endforeach
 @if($k>1)
<br/>
<strong>Lithe Group</strong>
<table border="1" style="border-style:dotted">
    <tr align="center">
        <td width="100px" rowspan="2" align="left">Particulars</td>
        <td width="70px" rowspan="2">TTL</td>
        <td width="70px" rowspan="2">Line to be Set</td>
        <td width="70px" rowspan="2">In-active Line</td>
        <td width="70px" rowspan="2">Active Line</td>
        <?php
        $i=1;
        ?>
        @foreach($rangeArr as $value)
        <td width="100px" colspan="2">{{$header[$i]['month_from']}}- {{$header[$i]['month_to']}}</td>
        <?php
        $i++;
        ?>
        @endforeach
         @if($i>2)
        <td width="100px" colspan="2">Total</td>
         @endif
    </tr>
    <tr align="center">
        @foreach($rangeArr as $value)
        <td width="100px">Qty</td>
        <td width="100px">Value</td>
        @endforeach
        @if($i>2)
        <td width="100px">Qty</td>
        <td width="100px">Value</td>
        @endif
    </tr>
    <tr align="center">
        <td width="100px" align="left">Capacity</td>
        <td width="70px">{{$lineinfo['ttl']}}</td>
        <td width="70px">{{$lineinfo['projected']}}</td>
        <td width="70px">{{$lineinfo['inactive']}}</td>
        <td width="70px">{{$lineinfo['active']}}</td>
        <?php
        $totCapQty=0;
        $totCapAmt=0;
        $totOrdQty=0;
        $totOrdAmt=0;
        $totNeedQty=0;
        $totNeedAmt=0;
        $totMktQty=0;
        $totMktAmt=0;
        ?>
        @foreach($rangeArr as $row)
        <?php
        $totCapQty+=$row->cap_qty;
        $totCapAmt+=$row->cap_amount;
        ?>
        <td width="100px" align="right">{{number_format($row->cap_qty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($row->cap_amount,2,'.',',')}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{number_format($totCapQty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($totCapAmt,2,'.',',')}}</td>
        @endif
    </tr>
    <tr align="center">
        <td width="100px" align="left">Order in Hand</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        @foreach($rangeArr as $row)
        <?php
        $totOrdQty+=$row->qty;
        $totOrdAmt+=$row->amount;
        ?>
        <td width="100px" align="right">{{number_format($row->qty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($row->amount,2,'.',',')}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{number_format($totOrdQty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($totOrdAmt,2,'.',',')}}</td>
        @endif
    </tr>
     <tr align="center">
        <td width="100px" align="left">Need</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        @foreach($rangeArr as $row)
        <?php
        $totNeedQty+=($row->ned_qty);
        $totNeedAmt+=($row->ned_amount);
        ?>
        <td width="100px" align="right">{{print_with_bracket($row->ned_qty,0)}}</td>
        <td width="100px" align="right">{{print_with_bracket($row->ned_amount,2)}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{print_with_bracket($totNeedQty,0)}}</td>
        <td width="100px" align="right">{{print_with_bracket($totNeedAmt,2)}}</td>
        @endif
    </tr>
    <tr align="center">
        <td width="100px" align="left">Projection</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        @foreach($rangeArr as $row)
        <?php
        $totMktQty+=$row->mkt_qty;
        $totMktAmt+=$row->mkt_amount;
        ?>
        <td width="100px" align="right">{{number_format($row->mkt_qty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($row->mkt_amount,2,'.',',')}}</td>
        @endforeach
        @if($i>2)
        <td width="100px" align="right">{{number_format($totMktQty,0,'.',',')}}</td>
        <td width="100px" align="right">{{number_format($totMktAmt,2,'.',',')}}</td>
        @endif
    </tr>
</table>
@endif
@endif