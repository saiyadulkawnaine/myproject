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

