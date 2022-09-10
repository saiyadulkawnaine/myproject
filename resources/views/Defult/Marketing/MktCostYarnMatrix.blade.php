<table border="1" class="table_form">
<thead>
        <tr align="center">
               <td colspan="6"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
        </tr>
        <tr align="center">
               <td width="250px">Yarn</td>
               <td width="70px">Cons</td>
               <td width="70px">Rate</td>
               <td width="80px">Amount</td>
        </tr>
        </thead>
    <tbody>
      <?php $i=1;?>
        @foreach($fabricyarn as $colorsize)
                    <tr>
                    <td width="250px">
                    {{ $colorsize->count }}/{{ $colorsize->symbol }}, {{ $colorsize->construction_name }},{{ $colorsize->name }}, {{ $colorsize->ratio }}%
                    <input type="hidden" name="mkt_cost_id[{{ $i }}]" id="mkt_cost_id{{ $i }}" value="{{ $colorsize->mkt_cost_id }}"/>
                     <input type="hidden" name="mkt_cost_fabric_id[{{ $i }}]" id="mkt_cost_fabric_id{{ $i }}" value="{{ $colorsize->mkt_cost_fabric_id }}"/>
                    <input type="hidden" name="autoyarnratio_id[{{ $i }}]" id="autoyarnratio_id{{ $i }}" value="{{ $colorsize->autoyarnratio_id }}"/>
                    </td>
                    
                    <td width="70px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsMktCostEmb.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $colorsize->cons}}"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsMktCostEmb.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount}}" />
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