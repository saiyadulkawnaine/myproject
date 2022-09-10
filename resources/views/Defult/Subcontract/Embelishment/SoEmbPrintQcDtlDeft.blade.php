<?php
$i=1;
?>
@if ($soembprintqcdtls->isNotEmpty())
<table border="1" class="table_form">
 <caption>New</caption>
 <tr align="center">
  <td width="300px">Defect Name</td>
  <td width="200px">Defect Code</td>
  <td width="200px">No Of Defect</td>
 </tr>
 <tbody>
  @foreach($soembprintqcdtls as $productdefect)
  <tr align="center">
   <td width="300px">{{ $productdefect->defect_name }}
    <input type="hidden" name="product_defect_id[{{ $i }}]" id="product_defect_id{{ $i }}"
     value="{{ $productdefect->product_defect_id }}" />
   </td>
   <td width="200px">{{ $productdefect->defect_code }}</td>
   <td width="200px">
    <input type="text" name="no_of_defect[{{ $i }}]" id="no_of_defect{{ $i }}" value="" />
   </td>
  </tr>
  <?php
   $i++;
  ?>
  @endforeach
 </tbody>
</table>
@endif

@if ($saved->isNotEmpty())
<table border="1" class="table_form">
 <caption>Saved</caption>
 <tr align="center">
  <td width="300px">Defect Name</td>
  <td width="200px">Defect Code</td>
  <td width="200px">No Of Defect</td>
 </tr>
 <tbody>
  @foreach($saved as $row)
  <tr align="center">
   <td width="300px">{{ $row->defect_name }}
    <input type="hidden" name="product_defect_id[{{ $i }}]" id="product_defect_id{{ $i }}"
     value="{{ $row->product_defect_id }}" />
   </td>
   <td width="200px">{{ $row->defect_code }}</td>
   <td width="200px">
    <input type="text" name="no_of_defect[{{ $i }}]" class="number integer" id="no_of_defect{{ $i }}"
     value="{{ $row->no_of_defect}}" />
   </td>
  </tr>
  <?php 
    $i++;
    ?>
  @endforeach
 </tbody>
</table>
@endif
<script>
 $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>