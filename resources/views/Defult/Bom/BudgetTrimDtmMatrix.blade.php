<table border="1" class="table_form">
    <tr align="center">
    <td width="40px">#</td>
    <td width="800px">Fabric Descriptions</td>
    <td width="70px">Fabric Color</td>
    <td width="70px">Accessories Qty</td>
    </tr>
    <tbody>
    <?php
    $i=1;
    $reqTot=0;
    $bomTot=0;
    $amountTot=0;
    ?>
    @foreach($colorsizes as $value)
    <tr align="left">
    <td width="40px">
    {{ $i }}
    <input type="hidden" name="budget_fabric_id[{{ $i }}]" value="{{ $value->budget_fabric_id }}"/>
    <input type="hidden" name="fabric_color[{{ $i }}]"  value="{{ $value->fabric_color }}"/>
    <input type="hidden" name="budget_trim_id[{{ $i }}]" value="{{ $budget_trim_id }}"/>
    
    </td>
    
    <td width="800px">
    {{ $value->fabric_description }}
    </td>
    <td width="70px">
    {{ $value->color_name }}
    </td>
    <td width="70px">
    <input type="text" name="qty[{{ $i }}]"  class="number integer" value="{{ $value->qty }}"/>
    </td>
    
    </tr>
    <?php
    
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr align="center">
    <td width="40px"></td>
    <td width="800px"> </td>
    <td width="70px" align="right"></td>
    <td width="70px" align="right"></td>
    </tr>
    </tfoot>
</table>
<script>
$('.integer').keyup(function () {
if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
this.value = this.value.replace(/[^0-9\.]/g, '');
}
});
</script>
