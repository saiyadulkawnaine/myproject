<table border="1" style="border-style:dotted; margin: 0 auto">
    
    <tr align="center">
    <td width="100px">Date</td>
    <td width="100px">Day Name</td>
    <td width="100px">Day Status</td>
    <td width="80px">Resource Qty</td>
    <td width="100px">Mkt. Capacity Mint</td>
    <td width="100px">Mkt. Capacity Pcs</td>
    <td width="110px">Prod. Capacity Mint</td>
    <td width="110px">Prod. Capacity Mint</td>
    <td width=""><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
    </tr>
    <tbody>
     <?php 
     $i=1;
     $mkt_cap_mint=0;
     $mkt_cap_pcs=0;
     $prod_cap_mint=0;
     $prod_cap_pcs=0;
     ?>
        @foreach($data as $row)
            <tr>
                    <td width="100px">
                    {{ $row->capacity_date }}
                     <input type="hidden" name="id[{{ $i }}]" id="id{{ $i }}"  value="{{ $row->id }}"/>
                    </td>
                    <td width="100px">
                    {{ $row->day_name }}
                    </td>
                    
                    <td width="100px" align="center">{!! Form::select("day_status[$i]",$daystatus,$row->day_status,array('id'=>'day_status',"onchange"=>"MsSewingCapacityDate.dayStatusChange(this.value,$i,$loop->count)")) !!}
                    </td>
                    <td width="80px" align="center">
                        <input type="text" name="resource_qty[{{ $i }}]" id="resource_qty{{ $i }}"  value="{{ $row->resource_qty }}" style="background-color:#FFFFFF;border:none" class="number integer" onchange="MsSewingCapacityDate.copyResourceQty(this.value,{{ $i }},{{ $loop->count}})"/>
                    </td>
                    <td width="100px" align="right">
                        {{number_format($row->mkt_cap_mint,0)}}
                    
                    </td>
                    <td width="100px" align="right">
                     {{  number_format($row->mkt_cap_pcs,0)}}
                    
                    </td>
                    <td width="110px" align="right">
                        {{number_format($row->prod_cap_mint,0)}}
                    
                    </td>
                    <td width="110px" align="right" >
                      {{ number_format($row->prod_cap_pcs,0)}}
                    
                    </td>
                    <td width="" >
                    
                    </td>
                    
            </tr>
            <?php
            $mkt_cap_mint+=$row->mkt_cap_mint;
            $mkt_cap_pcs+=$row->mkt_cap_pcs;
            $prod_cap_mint+=$row->prod_cap_mint;
            $prod_cap_pcs+=$row->prod_cap_pcs;
            $i++;
            ?>
        @endforeach
    </tbody>
    <tr align="center" style="background-color: #449d44; color: #FFFFFF; font-weight: bold;">
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="80px"></td>
    <td width="100px">{{  number_format($mkt_cap_mint,0)}}</td>
    <td width="100px">{{  number_format($mkt_cap_pcs,0)}}</td>
    <td width="110px">{{  number_format($prod_cap_mint,0)}}</td>
    <td width="110px">{{  number_format($prod_cap_pcs,0)}}</td>
    <td width=""></td>
    
    </tr>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>