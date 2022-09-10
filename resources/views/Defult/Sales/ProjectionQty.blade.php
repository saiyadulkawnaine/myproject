<table border="1" class="table_form">
<tr align="center">
               <td width="100px"></td>
               <td width="100px"></td>
               
               <td width="70px"></td>
               <td width="70px"></td>
               <td width="80px"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
           
        </tr>
       
        <tr align="center">
               <td width="100px">GMT</td>
               <td width="100px">SAM</td>
               
               <td width="70px">Qty</td>
               <td width="70px">Rate</td>
               <td width="80px">Amount</td>
           
        </tr>
    <tbody>
      <?php $i=1;?>
        @foreach($stylegmts as $stylegmt)
                    <tr align="center">
                    <td width="100px">
                    {{ $stylegmt->item_description }}
                     <input type="hidden" name="style_gmt_id[{{ $i }}]" id="style_gmt_id_{{ $i }}" value="{{ $stylegmt->style_gmt_id }}"/>
                    </td>
                    <td width="100px">
                     <input type="text" name="sam[{{ $i }}]" id="sam_{{ $i }}" class="number integer"  value="{{ $stylegmt->sam }}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onchange="MsProjectionQty.calculate({{ $i }},{{ $loop->count}},'qty')" value="{{ $stylegmt->qty }}"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsProjectionQty.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $stylegmt->rate }}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $stylegmt->amount }}" readonly/>
                    </td>
                    </tr>
                      <?php $i++;?>
        @endforeach
    </tbody>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>