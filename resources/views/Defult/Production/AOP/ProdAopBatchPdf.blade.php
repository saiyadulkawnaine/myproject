<h2 align="center">AOP Batch Card</h2>
<h3 align="center">
<u>Buyer 
@if (isset($batch['ordDtl']['buyer_name']))  
{{implode(',',$batch['ordDtl']['buyer_name'])}}
@endif
</u></h3>
<p align="center">Batch/Fabric Color:  <strong>{{ $batch['master']->batch_color_name }}</strong> Paste Wgt.: <strong>{{ $batch['master']->paste_wgt }}</strong> KG</p>

<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="120">
             <br/>
            Starting Date & Time:
        </td>
        <td width="">
             <br/>
             <table cellspacing="0" cellpadding="2" border="1">
                <tr>
                    <td width="30"></td>
                    <td width="30"></td>
                    <td width="60"></td>
                    <td width="30"></td>
                    <td width="30"></td>
                    <td width="30"></td>
                </tr>
             </table>
            
        </td>
    </tr>
    <tr>
        <td width="120">
             <br/>
            Ending Date & Time:
        </td>
        <td width="">
             <br/>
             <table cellspacing="0" cellpadding="2" border="1">
                <tr>
                    <td width="30"></td>
                    <td width="30"></td>
                    <td width="60"></td>
                    <td width="30"></td>
                    <td width="30"></td>
                    <td width="30"></td>
                </tr>
             </table>
            
        </td>
    </tr>
</table>
<br/>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="106">
        <br/>
        Batch No
        </td>
        <td width="106" align="left">
            <br/>
            {{ $batch['master']->batch_no }}
        </td>

        <td width="106">
        <br/>
        Batch  Date.
        </td>
        <td width="106">
        <br/>
        {{ $batch['master']->batch_date }}

        </td>
       
        <td width="106">
            <br/>
           Style Ref
        </td>
        <td width="106">
            <br/>
            @if (isset($batch['ordDtl']['style_ref'])) 
            {{implode(',',$batch['ordDtl']['style_ref'])}}
            @endif
        </td>
         <td width="106">
        <br/>
        Ship Date
        </td>
        <td width="">
        <br/>
        @if (isset($batch['ordDtl']['ship_date']))
        {{implode(',',$batch['ordDtl']['ship_date'])}}
        @endif
        </td>
    </tr>
    <tr>
        <td width="106">
            <br/>
            Batch For
        </td>
        <td width="106">
            <br/>
            {{ $batch['master']->batchfor }}
        </td>
        <td width="106">
            <br/>
            Design No
        </td>
        <td width="106">
            <br/>
            {{ $batch['master']->design_no }}
        </td>
       
       <td width="106">
            <br/>
            GMT Sales Order
        </td>
        <td width="106">
            <br/>
            @if (isset($batch['ordDtl']['sale_order_no']))
            {{implode(',',$batch['ordDtl']['sale_order_no'])}}
            @endif
        </td>
        <td width="106">
        <br/>
        Customer
        </td>
        <td width="">
        <br/>
        @if (isset($batch['ordDtl']['customer_name']))
        {{implode(',',$batch['ordDtl']['customer_name'])}}
        @endif
        </td>
    </tr>
    
    

    
</table>
<br/>
<p></p>
@if (count($batch['fabDtl'])>=1)
<p><strong>Fabric Detail</strong></p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="150" align="center">Body Part</td>
            <td width="200" align="center">Const. & Compo.</td>
            <td width="50" align="center"> GSM</td>
            <td width="100" align="center">M/C Dia<br/>X Gauge</td>
            <td width="40" align="center"> Dia</td>
            <td width="60" align="center">Shape</td>
            <td width="60" align="center">S. Length</td>
            <td width="80" align="center">Grey Qty</td>
            <td width="60" align="center">No Of  Roll</td>
            <td width="" align="center">R.<br/>Marking</td>
            
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        $batch_qty_tot=0;
        $no_of_roll_tot=0;
        ?>
        @foreach ($batch['fabDtl'] as $key=>$value)
        <tr>
            <td width="28" align="center">{{$i}}</td>
            <td width="150" align="center">{{$value['body_part']}}</td>
            <td width="200" align="center">{{$value['fabrication']}}</td>
            <td width="50" align="center">{{$value['dyeing_gsm_weight']}}</td>
            <td width="100" align="center">{{ implode(",",$value['machine_dia']) }}</td>
            <td width="40" align="center">{{$value['dyeing_dia_width']}}</td>
            <td width="60" align="center">{{$value['fabric_shape']}}</td>
            <td width="60" align="center">{{$value['stitch_length']}}</td>
            <td width="80" align="center">{{number_format(array_sum($value['batch_qty']),2)}}</td>
            <td width="60" align="center">{{number_format(array_sum($value['no_of_roll']),2)}}</td>
            <td width="" align="center"></td>
        </tr>
        <?php 
        $i++;
        $batch_qty_tot+=array_sum($value['batch_qty']);
        $no_of_roll_tot+=array_sum($value['no_of_roll']);
        ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td width="28" align="center">#</td>
            <td width="150" align="center"></td>
            <td width="200" align="center"></td>
            <td width="50" align="center"></td>
            <td width="100" align="center"></td>
            <td width="40" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="center">Total</td>
            <td width="80" align="center">{{number_format($batch_qty_tot,2)}}</td>
            <td width="60" align="center">{{number_format($no_of_roll_tot,2)}}</td>
            <td width="" align="center"></td>
        </tr>
    </tfoot>
</table>
<p></p>
@endif

@if (count($batch['proarr'])>=1)
<p><strong>Process Instruction</strong></p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="130" align="center">Process Name</td>
            <td width="100" align="center">Machine</td>
            <td width="150" align="center">Supervisor</td>
            <td width="60" align="center">Shift</td>
            <td width="100" align="center">Date </td>
            <td width="" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        ?>
        @foreach ($batch['proarr'] as $key=>$tvalue)
        <tr>
            <td width="28" align="center">{{$i}}</td>
            <td width="130" align="center">{{$tvalue['process_name']}}</td>
            <td width="100" align="center">{{$tvalue['machine_no']}}</td>
            <td width="150" align="center">{{$tvalue['supervisor_name']}}</td>
            <td width="60" align="center">{{$tvalue['shift_name']}}</td>
            <td width="100" align="center"> {{$tvalue['prod_date']}}</td>
            <td width="" align="center">{{$tvalue['remarks']}}</td>
            
        </tr>
        <?php 
        $i++;
        ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>

            <td  align="center" ></td>
            
        </tr>
        <tr>
            <td  align="center" ></td>
            
        </tr>
        <tr>
            <td  align="center" ></td>
            
        </tr>
    </tfoot>
</table>
<p></p>
@endif




<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td>Prepared By</td>
        <td align="center">Supervisor</td>
        <td align="center">Incharge</td>
        <td align="center">Planing Head</td>
        <td align="center">AOP Head</td>
    </tr>
    <tr>
        <td>
            {{ $batch['master']->created_by }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>
            {{ $batch['master']->created_date }}  {{ $batch['master']->created_time }} 
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
