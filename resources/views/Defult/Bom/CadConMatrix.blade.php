<div style="text-align:right; background:#f9f9f9; padding-right:100px; font-size: 10px">
    <input type="radio" name="is_copy_dia" id="is_copy_dia" value="0" /><strong>No Copy Dia</strong>
    <input type="radio" name="is_copy_dia" id="is_copy_dia" value="1"/><strong> Dia Color Wise</strong>
    <input type="radio" name="is_copy_dia" id="is_copy_dia" value="2"/><strong> Dia Size Wise</strong>
    <input type="radio" name="is_copy_dia" id="is_copy_dia" value="3" /><strong> Dia  GMT Part  </strong>

    <input type="radio" name="is_copy_cons" id="is_copy_cons" value="0" /><strong>No Copy Cons </strong>
    <input type="radio" name="is_copy_cons" id="is_copy_cons" value="1" /><strong> Cons Fabric & Color Wise </strong>
    <input type="radio" name="is_copy_cons" id="is_copy_cons" value="2" /><strong> Cons  Color Wise </strong>
    <input type="radio" name="is_copy_cons" id="is_copy_cons" value="3" /><strong> Cons  GMT Part   </strong>
</div>
<br/>
<table border="1" class="table_form">
<thead>
    
    <tr align="center">
    <td width="30px">#</td>
    <td width="200px">GMT Item</td>
    <td width="150px">GMT Part</td>
    
    <td width="80px">Fabric Nature</td>
    <td width="80px">Fabric Looks</td>
    <td width="80px">Fabric Shape</td>
    <td width="250px">Fabric Description</td>
    <td width="70px">Dia</td>
    <td width="80px">Color</td>
    <td width="80px">Size</td>
    <td width="60px">UOM</td>
    <td width="70px">Cons</td>
    </tr>
    </thead>
    <tbody>
     <?php 
	 $i=1;
	 ?>
        @foreach($stylefabrications as $row=>$value)
        
        
           
            <tr>
                <td width="30px" align="left">
                    {{$i}}
                </td>
                    <td width="200px" align="left">
                    {{ $value['stylegmts'] }}
                    <input type="hidden" name="id[{{ $i }}]" id="id{{ $i }}"  value="{{ $value['id'] }}"/>
                    <input type="hidden" name="cad_id[{{ $i }}]" id="cad_id{{ $i }}"  value="{{ $value['cad_id'] }}"/>
                    <input type="hidden" name="style_fabrication_id[{{ $i }}]" id="style_fabrication_id{{ $i }}" value="{{ $value['style_fabrication_id'] }}"/>
                    <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id{{ $i }}" value="{{ $value['style_size_id'] }}"/>
                    <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id{{ $i }}" value="{{ $value['style_color_id'] }}"/>
                    <input type="hidden" name="style_gmt_color_size_id[{{ $i }}]" id="style_gmt_color_size_id{{ $i }}" value="{{ $value['style_gmt_color_size_id'] }}"/>
                    </td>
                    <td width="150px" align="left">
                    {{ $value['gmtspart'] }}
                     <input type="hidden" name="gmtspart_id[{{ $i }}]" id="gmtspart_id{{ $i }}" value="{{ $value['gmtspart_id'] }}"/>
                    
                    </td>
                    
                    <td width="80px" align="left">{{ $value['fabricnature'] }}</td>
                    <td width="80px" align="left">{{ $value['fabriclooks'] }}</td>
                    <td width="80px" align="left">{{ $value['fabricshape'] }}</td>
                    <td width="250px" align="left">{{ $value['fabrication'] }}</td>
                     <td width="70px" align="right">
                    <input type="text" name="dia[{{ $i }}]" id="dia{{ $i }}" value="{{ $value['dia'] }}" onchange="MsCadCon.copyDia({{ $i }},{{ $loop->count}})"/>
                    </td>
                    <td width="80px" align="center">{{ $value['color_name'] }}</td>
                    <td width="80px" align="center">{{ $value['name'] }}</td>
                    <td width="80px" align="center">{{ $value['uom_name'] }}</td>
                    <td width="70px" align="right">
                    <input type="text" name="cons[{{ $i }}]" id="cons{{ $i }}" value="{{ $value['cons'] }}" class="number integer" onchange="MsCadCon.copy({{ $i }},{{ $loop->count}})" />
                    </td>
                    
            </tr>
			<?php 
            $i++;
            ?>
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