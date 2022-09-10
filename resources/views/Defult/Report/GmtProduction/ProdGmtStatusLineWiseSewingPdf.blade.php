<table border="1">
    <tr>
        <td width="30" align="center"><strong>SL</strong></td>
        <td width="100" align="center"><strong>Sewing Company</strong></td>
        <td width="100" align="center"><strong>Line No</strong></td>
        <td width="100"><strong>Particulars</strong></td>
        @foreach ($sizeArr as $size_id=>$size_name)
        <td width="60" align="center"><strong>{{ $size_name }}</strong></td>
        @endforeach
        <td width="70"  align="center"><strong>Total</strong></td>
    </tr>
    <?php
        $i=1;
        $totalSewLineQty=0;
        $totalSewQty=0;
    ?>
    @foreach ($lineArr as $wstudy_line_setup_id=>$linedata)
    <?php
        $totalSewLineQty=0;
        $totalSewQty=0;
    ?>
    <tr>
        <td width="30" rowspan="3" align="center">{{ $i++ }}</td>
        <td width="100" rowspan="3" align="left">{{ $linedata['company_name'] }}</td>
        <td width="100" rowspan="3"  align="left">{{ $linedata['line_no'] }}</td>
        <td width="100"  align="left">Input Qty</td>
        @foreach ($sizeArr as $key=>$val) 
        <?php
            $sew_line_qty=isset($lineSizeArr[$wstudy_line_setup_id][$key]['sew_line_qty'])?$lineSizeArr[$wstudy_line_setup_id][$key]['sew_line_qty']:0;
            $totalSewLineQty+=$sew_line_qty;
        ?>
        <td width="60" align="right">{{ $sew_line_qty }}</td>
        @endforeach
        <td width="70" align="right">{{ $totalSewLineQty }}</td>
    </tr>
    <tr>
        <td width="100" align="left">Output Qty</td>
        @foreach ($sizeArr as $key=>$val) 
        <?php
            $sew_qty=isset($lineSizeArr[$wstudy_line_setup_id][$key]['sew_qty'])?$lineSizeArr[$wstudy_line_setup_id][$key]['sew_qty']:0;
            $totalSewQty+=$sew_qty;
        ?>
        <td width="60" align="right">{{ $sew_qty }}</td>
        @endforeach
        <td width="70" align="right">{{ $totalSewQty }}</td>
    </tr>
    <tr>
        <td width="100" align="left"><strong>Balance Qty</strong></td>
        @foreach ($sizeArr as $key=>$val) 
        <?php
            $sew_qty=isset($lineSizeArr[$wstudy_line_setup_id][$key]['sew_qty'])?$lineSizeArr[$wstudy_line_setup_id][$key]['sew_qty']:0;
            $sew_line_qty=isset($lineSizeArr[$wstudy_line_setup_id][$key]['sew_line_qty'])?$lineSizeArr[$wstudy_line_setup_id][$key]['sew_line_qty']:0;
           // $totalSewLineQty+=$sew_line_qty;
          //  $totalSewQty+=$sew_qty;
        ?>
        <td width="60" align="right"><strong>{{ $sew_line_qty-$sew_qty }}</strong></td>
        @endforeach
        <td width="70" align="right"><strong>{{ $totalSewLineQty-$totalSewQty }}</strong></td>
    </tr>
    @endforeach
    
</table>