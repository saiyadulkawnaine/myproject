<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="319">
        <br/>
            Location:&nbsp;{{ $capacity->location_name }}<br/>
            Production Source:&nbsp;{{ $capacity->prod_source }}<br/>
            Year :&nbsp;{{ $capacity->year }}<br/>
            
        
        </td>
      
        <td width="119">
            <br/>
            MKT Efficiency (%):<br/>
            Product Efficiency (%):<br/>
            Basic SMV:<br/>
            Working Hour:<br/>
        </td>
        <td width="200">
            <br/>
            {{ $capacity->mkt_eff_percent }}<br/>
            {{ $capacity->prod_eff_percent }}<br/>
            {{ $capacity->basic_smv }}<br/>
            {{ $capacity->working_hour }}<br/>
        </td>
    </tr>
    
</table>
<br/>
<?php
    $i=1;
?>

<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="40" align="center">#</td>
            <td width="80" align="center">Month</td>
            <td width="80" align="center">Working Day</td>
            
            <td width="109" align="center">Mkt Capacity Mint</td>
            <td width="109" align="center">Mkt Capacity Pcs</td>
            <td width="109" align="center">Prod Capacity Mint</td>
            <td width="" align="center">Prod Capacity Pcs</td>
            
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        $mkt_cap_mint=0;
        $mkt_cap_pcs=0;
        $prod_cap_mint=0;
        $prod_cap_pcs=0;
       
        ?>
        @foreach($dates as $row)
        <tr>
            <td width="40" align="center">{{$i}}</td>
            <td width="80" align="center">{{$row->month}}</td>
            <td width="80" align="center">{{$row->no_of_day}}</td>
            
            <td width="109"  align="right">{{ number_format($row->mkt_cap_mint,0)}}</td>
            <td width="109"  align="right">{{number_format($row->mkt_cap_pcs,0)}}</td>
            <td width="109"  align="right">{{number_format($row->prod_cap_mint,0)}}</td>
            <td width=""  align="right">{{number_format($row->prod_cap_pcs,0)}}</td>
        </tr>
        <?php
        $mkt_cap_mint+=$row->mkt_cap_mint;
        $mkt_cap_pcs+=$row->mkt_cap_pcs;
        $prod_cap_mint+=$row->prod_cap_mint;
        $prod_cap_pcs+=$row->prod_cap_pcs;
        $i++;
        ?>
        @endforeach
        <tr>
            <td width="40" align="center"></td>
            <td width="80" align="center"></td>
            <td width="80" align="center">Total</td>
            
            <td width="109" align="right">{{number_format($mkt_cap_mint,0)}}</td>
            <td width="109" align="right">{{number_format($mkt_cap_pcs,0)}}</td>
            <td width="109" align="right">{{number_format($prod_cap_mint,0)}}</td>
            <td width="" align="right">{{number_format($prod_cap_pcs,0)}}</td>
        </tr>
    </tbody>
</table>