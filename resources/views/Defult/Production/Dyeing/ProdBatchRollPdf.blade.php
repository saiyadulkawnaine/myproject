<p></p>
<table cellspacing="5">
<tr>
<td width="319"><table cellspacing="0" cellpadding="1" border="1">
         <tr>
            <td width="100">
                Grey Qty
            </td>
            <td width="100">
                
            </td>
            
         </tr>
          <tr>
            <td width="100">
                Fin. Qty
            </td>
            <td width="100">
                
            </td>
            
         </tr>
          <tr>
            <td width="100">
                Process Loss
            </td>
            <td width="100">
                
            </td>
            
         </tr>
          <tr>
            <td width="100">
                Yarn Type
            </td>
            <td width="100">
                
            </td>
            
         </tr>
    </table>
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <table cellspacing="0" cellpadding="2" border="1">
         <tr>
            <td width="100">
                Date
            </td>
            <td width="100">
                
            </td>
         </tr>
          <tr>
            <td width="100">
               Shift
            </td>
            <td width="100"> 
            </td>
         </tr>   
    </table>
</td>
</tr>
</table>
<table cellspacing="5">
<tr>
<td>
<table cellspacing="0" cellpadding="1" border="1">
    <thead>
        <tr>
            <td width="64" align="center">Barcode</td>
            <td width="34" align="center">Roll<br/>No</td>
            <td width="40" align="center">Req.<br/>Dia</td>
            <!-- <td width="40" align="center">Fin. Dia</td>
            <td width="40" align="center">Actual GSM </td> -->
            <td width="90" align="center">Knitted By</td>
            <td width="45" align="center">Roll<br/>Wgt</td>
            <td width="42" align="center">M/C No</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        ?>
        @foreach ($groups[0] as $key=>$value)
        <tr>
            <td width="64" align="center">{{ $value['barcode_no'] }}</td>
            <td width="34" align="center">{{$value['custom_no']}}</td>
            <td width="40" align="center">{{$value['dia_width']}}</td>
            <td width="90" align="center">{{ $value['knit_company_name'] }}</td>
            <td width="45" align="right">{{$value['batch_qty']}}</td>
            <td width="42" align="center">{{$value['machine_no']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</td>
<td>@if (count($groups)>1)<table cellspacing="0" cellpadding="1" border="1">
    <thead>
        <tr>
            <td width="64" align="center">Barcode</td>
            <td width="34" align="center">Roll<br/>No</td>
            <td width="40" align="center">Req.<br/>Dia</td>
            <!-- <td width="40" align="center">Fin. Dia</td>
            <td width="40" align="center">Actual GSM </td> -->
            <td width="90" align="center">Knitted By</td>
            <td width="45" align="center">Roll<br/>Wgt</td>
            <td width="42" align="center">M/C No</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        ?>
        @foreach ($groups[1] as $key=>$value)
        <tr>
            <td width="64" align="center">{{ $value['barcode_no'] }}</td>
            <td width="34" align="center">{{$value['custom_no']}}</td>
            <td width="40" align="center">{{$value['dia_width']}}</td>
            <td width="90" align="center">{{ $value['knit_company_name'] }}</td>
            <td width="45" align="right">{{$value['batch_qty']}}</td>
            <td width="42" align="center">{{$value['machine_no']}}</td>
            
        </tr>
        
        @endforeach
    </tbody>
</table>
@endif
</td>
</tr>
</table>

<strong>Total Finish Weight:</strong>
<br/>
Comments:.......................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................

<br/>
<br/>
<br/>
<br/>
<table>
    
    <tr align="center">
        <td width="159">
            Prepared By
        </td>
        <td width="159">
            Quality In-charge
        </td>
        <td width="159">
            Finishing In-charge
        </td>
        <td width="">
            Dyeing Manager
        </td>
        
    </tr>
    <tr align="center">
        <td width="159">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $batch['master']->user_name }},&nbsp;&nbsp;{{ $batch['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $batch['master']->created_at }}
        </td>
        <td width="159">
        </td>
        <td width="159">
        </td>
        <td width="">
        </td>
        
        
    </tr>
</table>
