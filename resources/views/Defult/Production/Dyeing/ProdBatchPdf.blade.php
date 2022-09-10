<h2 align="center">Batch Card {{$batch['master']->re_dyeing}}</h2>
<h3 align="center">
<u>Buyer 
@if (isset($batch['ordDtl']['buyer_name']))  
{{implode(',',$batch['ordDtl']['buyer_name'])}}
@endif
</u></h3>
<p align="center">Batch Color:  <strong>{{ $batch['master']->batch_color_name }}</strong> Batch Wgt.: <strong>{{ $batch['master']->batch_wgt }}</strong> KG</p>

<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="120">
             <br/>
            Loading Date & Time:
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
            Unloading Date & Time:
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
        Color Range
        </td>
        <td width="106" align="left">
            <br/>
            {{ $batch['master']->color_range_name }}
        </td>
        <td width="106">
            <br/>
           Style Ref
        </td>
        <td width="">
            <br/>
            @if (isset($batch['ordDtl']['style_ref'])) 
            {{implode(',',$batch['ordDtl']['style_ref'])}}
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
            Color Depth.
        </td>
        <td width="108">
            <br/>
            
        </td>
       <td width="106">
            <br/>
            GMT Sales Order
        </td>
        <td width="">
            <br/>
            @if (isset($batch['ordDtl']['sale_order_no']))
            {{implode(',',$batch['ordDtl']['sale_order_no'])}}
            @endif
        </td>
    </tr>
    <tr>
        <td width="106">
        <br/>
        Customer
        </td>
        <td width="106">
        <br/>
        @if (isset($batch['ordDtl']['customer_name']))
        {{implode(',',$batch['ordDtl']['customer_name'])}}
        @endif
        </td>
        <td width="106">
        <br/>
        Lab Dip No
        </td>
        <td width="106">
        <br/>
        {{ $batch['master']->lap_dip_no }}
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
        Batch  Date.
        </td>
        <td width="108">
        <br/>
        {{ $batch['master']->batch_date }}

        </td>

        <td width="106">
        <br/>
        Dyeing Machine 
        </td>
        <td width="106">
        <br/>
        {{ $batch['master']->machine_no }}
        </td>

        <td width="106">
        <br/>
        Batch Serial
        </td>
        <td width="">
        <br/>
        {{ $batch['master']->batch_ext_no }}
        </td>
    </tr>
    <tr>
        <td width="106">
            <br/>
           Shift
        </td>
        <td width="318">
            <br/>
            
        </td>
        <?php
        if($batch['master']->is_redyeing)
        {
        ?>
        <td width="106">
            <br/>
           Org. Batch No
        </td>
        <td width="106">
            <br/>
           {{ $batch['master']->org_batch_no }}
        </td>
        <td width="106">
            <br/>
           Org. Batch ID
        </td>
        <td width="">
            <br/>
           {{ $batch['master']->org_batch_id }}
        </td>
        <?php
        }
        ?>
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
            <td width="60" align="center">Body Part</td>
            <td width="100" align="center">Const. & Compo.</td>
            <td width="50" align="center">Knitted GSM</td>
            <td width="50" align="center">M/C Dia<br/>X Gauge</td>
            <td width="40" align="center">Knitted Dia</td>
            <td width="50" align="center">Looks</td>
            <td width="60" align="center">Shape</td>
            <td width="60" align="center">Dye Type</td>
            <td width="60" align="center">S. Length</td>
            <td width="60" align="center">Grey Qty</td>
            <td width="40" align="center">No Of  Roll</td>
            <td width="40" align="center">R.<br/>Marking</td>
            <td width="" align="center">Yarn Details</td>
            
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        $batch_qty_tot=0;
        $no_of_roll_tot=0;
        ?>
        @foreach ($batch['fabDtl'] as $key=>$value)
        <tr nobr="true">
            <td width="28" align="center">{{$i}}</td>
            <td width="60" align="center">{{$value['body_part']}}</td>
            <td width="100" align="center">{{$value['fabrication']}}</td>
            <td width="50" align="center">{{$value['gsm_weight']}}</td>
            <td width="50" align="center">{{ implode(",",$value['machine_dia']) }}</td>
            <td width="40" align="center">{{$value['dia_width']}}</td>
            <td width="50" align="center"> {{$value['fabric_look']}}</td>
            <td width="60" align="center">{{$value['fabric_shape']}}</td>
            <td width="60" align="center">{{$value['dyetype']}}</td>
            <td width="60" align="center">{{$value['stitch_length']}}</td>
            <td width="60" align="center">{{number_format(array_sum($value['batch_qty']),2)}}</td>
            <td width="40" align="center">{{number_format(array_sum($value['no_of_roll']),2)}}</td>
            <td width="40" align="center"></td>
            <td width="" align="center">{{ implode(",",$value['yarnDtl']) }}</td>
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
            <td width="60" align="center"></td>
            <td width="100" align="center"></td>
            <td width="50" align="center"></td>
            <td width="50" align="center"></td>
            <td width="40" align="center"></td>
            <td width="50" align="center"> </td>
            <td width="60" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="center">Total</td>
            <td width="60" align="center">{{number_format($batch_qty_tot,2)}}</td>
            <td width="40" align="center">{{number_format($no_of_roll_tot,2)}}</td>
            <td width="40" align="center"></td>
            <td width="" align="center"></td>
        </tr>
    </tfoot>
</table>
<p></p>
@endif
@if (count($batch['trimDtl'])>=1)
<p><strong>Trims Detail</strong></p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="130" align="center">Trims Name</td>
            <td width="70" align="center">Qty</td>
            <td width="50" align="center">UOM</td>
            <td width="60" align="center">Wgt./Unit</td>
            <td width="60" align="center">Weight </td>
            <td width="40" align="center">R.<br/>Marking</td>
            <td width="" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        $wgt_qty_tot=0;
        ?>
        @foreach ($batch['trimDtl'] as $key=>$tvalue)
        <tr>
            <td width="28" align="center">{{$i}}</td>
            <td width="130" align="center">{{$tvalue['itemclass_name']}}</td>
            <td width="70" align="center">{{$tvalue['qty']}}</td>
            <td width="50" align="center">{{$tvalue['uom_code']}}</td>
            <td width="60" align="center">{{$tvalue['wgt_per_unit']}}</td>
            <td width="60" align="center"> {{number_format($tvalue['wgt_qty'],2)}}</td>
            <td width="40" align="center"></td>
            <td width="" align="center">{{$tvalue['remarks']}}</td>
            
        </tr>
        <?php 
        $wgt_qty_tot+=$tvalue['wgt_qty'];
        $i++;
        ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td width="28" align="center">#</td>
            <td width="130" align="center"></td>
            <td width="70" align="center"></td>
            <td width="50" align="center"></td>
            <td width="60" align="center">Total</td>
            <td width="60" align="center"> {{number_format($wgt_qty_tot,2)}}</td>
            <td width="40" align="center"></td>
            <td width="" align="center"></td>
            
        </tr>
    </tfoot>
</table>
<p></p>
@endif

<p><strong>Process Required:</strong></p>
{{ implode(" ",$batch['proarr'])}}

<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td>
            Prepared By
        </td>
        <td align="center">Supervisor</td>
        <td align="center">Incharge</td>
        <td align="center">Manager</td>
    </tr>
    <tr>
        <td>
            {{ $batch['master']->created_by }}
        </td>
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
    </tr>
</table>
