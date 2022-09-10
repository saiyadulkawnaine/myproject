<?php
        $i=1;
        //$ordQty=0;
        //$reqQty=0;
        //$bomQty=0;
        //$amontQty=0;
?>
@if($colorsizes->isNotEmpty())
<p>New Inspection Details</p>
<table border="1">
    <tr align="center">
        <th width="150px"  class="text-center">GMT Item</th>
        <th width="150px"  class="text-center">GMT Color</th>
        <th width="150px"  class="text-center">GMT Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Passed Qty</th>
        <th width="80px"  class="text-center">Re- <br/>Check<br/> Qty</th>
        <th width="80px"  class="text-center">Failed<br/> Qty</th>
        <th width="100px"  class="text-center">Re- <br/>Check<br/>Comments</th>
        <th width="100px"  class="text-center">Failed<br/>Comments</th>
        <th width="80px"  class="text-center">Expected<br/>Exfactory<br/>Date</th>
        <th width="80px"  class="text-center">Exfactory<br/>Qty</th>
    </tr>
    <tbody>
        
        @foreach($colorsizes as $colorsize)
        <tr align="center">
            <td width="150px">
                {{ $colorsize->item_description }}
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="150px">{{ $colorsize->color_name }}</td>
            <td width="150px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->plan_cut_qty }}</td>
            <td width="80px" align="right">
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="" class="number integer"/>
            </td>
            <td width="80px" align="right">
                <input type="text" name="re_check_qty[{{ $i }}]" id="re_check_qty{{ $i }}" value="" class="number integer"/>
            </td>
            <td width="80px">
                <input type="text" name="failed_qty[{{ $i }}]" id="failed_qty{{ $i }}" value="" class="number integer"/>
            </td>
            <td width="100px">
                <input type="text" name="re_check_remarks[{{ $i }}]" id="re_check_remarks{{ $i }}" value="" />
            </td>
            <td width="100px">
                <input type="text" name="failed_remarks[{{ $i }}]" id="failed_remarks{{ $i }}" value="" />
            </td>
            <td width="100px">
                <input type="text" name="expected_exfactory_date[{{ $i }}]" id="expected_exfactory_date{{ $i }}" value="" class="datepicker"/>
            </td>
            <td width="80px">
                <input type="text" name="exfactory_qty[{{ $i }}]" id="exfactory_qty{{ $i }}" value="" class="number integer"/>
            </td>
        </tr>
        <?php
            $i++;
        ?>
        @endforeach
    </tbody>
</table>
@endif
@if($saved->isNotEmpty())
<br/>
<p>Saved Inspection Details</p>
<table border="1">
    <tr align="center">
        <th width="150px"  class="text-center">GMT Item</th>
        <th width="150px"  class="text-center">GMT Color</th>
        <th width="150px"  class="text-center">GMT Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Passed Qty</th>
        <th width="80px"  class="text-center">Re- <br/>Check<br/> Qty</th>
        <th width="80px"  class="text-center">Failed<br/> Qty</th>
        <th width="100px"  class="text-center">Re- <br/>Check<br/>Comments</th>
        <th width="100px"  class="text-center">Failed<br/>Comments</th>
        <th width="80px"  class="text-center">Expected<br/>Exfactory<br/>Date</th>
        <th width="80px"  class="text-center">Exfactory<br/>Qty</th>
        <th width="80px"  class="text-center"></th>
    </tr>
    <tbody>
        <?php
        //$i=1;
        $tQty=0;
        $tReCheckQty=0;
        $tFailedQty=0;
        $tExfactoryQty=0;
        ?>
        @foreach($saved as $colorsize)
         <tr align="center">
            <td width="150px">
                {{ $colorsize->item_description }}
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="150px">{{ $colorsize->color_name }}</td>
            <td width="150px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->plan_cut_qty }}</td>
            <td width="80px" align="right">
            <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $colorsize->qty }}" class="number integer"/>
            </td>
            <td width="80px" align="right">
                <input type="text" name="re_check_qty[{{ $i }}]" id="re_check_qty{{ $i }}" value="{{ $colorsize->re_check_qty }}" class="number integer"/>
            </td>
            <td width="80px">
                <input type="text" name="failed_qty[{{ $i }}]" id="failed_qty{{ $i }}" value="{{ $colorsize->failed_qty }}" class="number integer"/>
            </td>
            <td width="100px">
                <input type="text" name="re_check_remarks[{{ $i }}]" id="re_check_remarks{{ $i }}" value="{{ $colorsize->re_check_remarks }}" />
            </td>
            <td width="100px">
                <input type="text" name="failed_remarks[{{ $i }}]" id="failed_remarks{{ $i }}" value="{{ $colorsize->failed_remarks }}" />
            </td>
            <td width="100px">
                <input type="text" name="expected_exfactory_date[{{ $i }}]" id="expected_exfactory_date{{ $i }}" value="{{ $colorsize->expected_exfactory_date }}" class="datepicker"/>
            </td>
            <td width="80px">
                <input type="text" name="exfactory_qty[{{ $i }}]" id="exfactory_qty{{ $i }}" value="{{ $colorsize->exfactory_qty }}" class="number integer"/>
            </td>
            <td width="80px"><a href="javascript:void(0)" onclick="MsProdGmtInspectionOrder.delete(event,{{ $colorsize->prod_gmt_inspection_order_id }})">Remove</a></td>
        </tr>
        <?php
            $i++;
            $tQty+=$colorsize->qty;
            $tReCheckQty+=$colorsize->re_check_qty;
            $tFailedQty+=$colorsize->failed_qty;
            $tExfactoryQty+=$colorsize->exfactory_qty;
        ?>
        
        @endforeach
    </tbody>
    <tfoot>
        <tr align="center">
            <th width="150px"  class="text-center">Total</th>
            <th width="150px"  class="text-center"></th>
            <th width="150px"  class="text-center"></th>
            <th width="80px"  class="text-center"></th>
            <th width="80px"  class="text-center">{{ $tQty }}</th>
            <th width="80px"  class="text-center">{{ $tReCheckQty }}</th>
            <th width="80px"  class="text-center">{{ $tFailedQty }}</th>
            <th width="100px"  class="text-center"></th>
            <th width="100px"  class="text-center"></th>
            <th width="80px"  class="text-center"></th>
            <th width="80px"  class="text-center">{{ $tExfactoryQty }}</th>
            <th width="80px"  class="text-center"></th>
        </tr>
    </tfoot>
</table>
@endif

<script>
(function(){
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });

   })(jQuery);
</script>
