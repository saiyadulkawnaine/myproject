<table>
 <tr>
  <td width="200" align="left">@if ($data['master']->buyer_name)
   {{ $data['master']->buyer_name }}
   @endif</td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td width="200" align="left">Asset No:-
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->asset_no }}<br />
   Asset Name:-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']-> asset_name}}<br />
   Asset Type:-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']-> type_id}}<br />
   Asset Group:-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']-> asset_group}}<br />
   Parchage Date:-&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->purchase_date }}
  </td>
  <td width="200"></td>
  <td width="200" align="left">Disposal Date:-
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->disposal_date }}<br />
   Disposal Type:-
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->disposal_type_id }}<br />
   Dep.
   Method:-
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->depreciation_method_id }}<br />
   Dep. Rate:-
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->depreciation_rate }}%
  </td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td></td>
 </tr>
</table>
<?php 
$i=1;
?>
<table border="1" align="center" style="padding: 3px;">
 <tr>
  <th width="50" align="center">SL No</th>
  <th width="100" align="center">Original Cost</th>
  <th width="100" align="center">Acummulated Dep</th>
  <th width="100" align="center">Written Down Value</th>
  <th width="100" align="center">Sold Amount</th>
  <th width="100" align="center">Gain/Loss</th>
 </tr>
 <tr>
  <td width="50" align="center">{{ $i++ }}</td>
  <td width="100" align="center">{{ $data['master']->origin_cost }}</td>
  <td width="100" align="center">{{ $data['master']->accumulated_dep }}</td>
  <td width="100" align="center">{{ number_format($data['master']->written_down_value,2) }}</td>
  <td width="100" align="center">{{ $data['master']->sold_amount }}</td>
  <td width="100" align="center">{{ number_format($data['master']->gain_loss,2) }}</td>
 </tr>
</table>
<table>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td width="200" align="left">
   <h4>Entend by</h4>
  </td>
 </tr>
 <tr>
  <td></td>
 </tr>
 <tr>
  <td width="200" align="left">
   <strong>{{$data['master']->createdby_user_name}}<br />&nbsp;
    {{$data['master']->createdby_designation}}</strong>
  </td>
 </tr>
</table>