<?php
        $i=1;
        //$ordQty=0;
        //$reqQty=0;
        //$bomQty=0;
        //$amontQty=0;
?>
@if($colorsizes->isNotEmpty())
<p>New Item Details</p>
<table  border="1" style="border-style:dotted">
    <tr align="center">
        <th colspan="11"  class="text-center"></th>
        <td colspan="7"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td></th>
    </tr>
    <tr align="center">
        <th width="50px"  class="text-center">SL</th>
        <th width="100px"  class="text-center">Style</th>
        <th width="100px"  class="text-center">BercodeID</th>
        <th width="150px"  class="text-center">Sales<br/>Order</th>
        <th width="150px"  class="text-center">Gmt Item</th>
        <th width="80px"  class="text-center">Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="100px"  class="text-center">UOM</th>
        <th width="80px"  class="text-center">Qty</th>
        <th width="80px"  class="text-center">Rate</th>
        <th width="100px"  class="text-center">Value</th>
        <th width="100px"  class="text-center">Selling<br/>Price</th>
        <th width="100px"  class="text-center">Currency</th>
        <th width="60px"  class="text-center">VAT %</th>
        <th width="60px"  class="text-center">S.TAX %</th>
        <th width="100px"  class="text-center">Job No</th>
    </tr>
    <tbody> 
        @foreach($colorsizes as $colorsize)
        <tr align="center">
            <td width="30px">
                {{ $i }}
            </td>
            <td width="120px">
                <input type="text" name="style_ref[{{ $i }}]" id="style_ref{{ $i }}" value="{{ $colorsize->style_ref }}"/>
                <input type="hidden" name="style_id[{{ $i }}]" id="style_id{{ $i }}" value="{{ $colorsize->style_id }}"/>
                <input type="hidden" name="country_id[{{ $i }}]" id="country_id{{ $i }}" value="{{ $colorsize->country_id }}"/>
            </td>
            <td width="150px">{{ $colorsize->srm_product_receive_dtl_id }}</td>
            <td width="150px">
                <input type="text" name="sale_order_no[{{ $i }}]" id="sale_order_no{{ $i }}" value="{{ $colorsize->sale_order_no }}" readonly/>
                <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $colorsize->sales_order_id }}" readonly/>
            </td>
            <td width="150px">
                <input type="text" name="style_gmt_name[{{ $i }}]" id="style_gmt_name{{ $i }}" value="{{ $colorsize->item_description }}" readonly />
                <input type="hidden" name="style_gmt_id[{{ $i }}]" id="style_gmt_id{{ $i }}" value="{{ $colorsize->style_gmt_id }}" readonly/>
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="150px">
                <input type="text" name="color_name[{{ $i }}]" id="color_name{{ $i }}" value="{{ $colorsize->color_name }}" readonly/>
                <input type="hidden" name="color_id[{{ $i }}]" id="color_id{{ $i }}" value="{{ $colorsize->color_id }}"/>
            </td>
            <td width="80px">
                <input type="text" name="size_name[{{ $i }}]" id="size_name{{ $i }}" value="{{ $colorsize->size_name }}" readonly/>
                <input type="hidden" name="size_id[{{ $i }}]" id="size_id{{ $i }}" value="{{ $colorsize->size_id }}"/>
            </td>
            <td width="100px" align="right">
                {{ $colorsize->uom_code }}
                <input type="hidden" name="uom_id[{{ $i }}]" id="uom_id{{ $i }}" value="{{ $colorsize->uom_id }}"/>
            </td>
            <td width="80px" align="right">
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $colorsize->colorsize_qty }}" class="number integer" onchange="MsSrmProductReceiveDtl.orderCalculate({{ $i }},{{ $loop->count}})"/>
                
            </td>
            <td width="80px" align="right">
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" value="{{ $colorsize->colorsize_rate }}" class="number integer"  onchange="MsSrmProductReceiveDtl.orderCalculate({{ $i }},{{ $loop->count}})"/>
            </td>
            <td width="100px" align="right">
                <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" value="{{ $colorsize->colorsize_amount }}" class="number integer"/>
            </td>
            <td width="100px" align="right">
                <input type="text" name="sales_rate[{{ $i }}]" id="sales_rate{{ $i }}" value="" class="number integer" onchange="MsSrmProductReceiveDtl.copy({{ $i }},{{ $loop->count}},'sales_rate')" />
            </td>
            <td width="100px" align="right">
                {{ $colorsize->currency_code }}
                <input type="hidden" name="currency_id[{{ $i }}]" id="currency_id{{ $i }}" value="{{ $colorsize->currency_id }}"/>
            </td>
            <td width="60px" align="right">
                <input type="text" name="vat_per[{{ $i }}]" id="vat_per{{ $i }}" onchange="MsSrmProductReceiveDtl.copy({{ $i }},{{ $loop->count}},'vat_per')" value="" class="number integer"/>
            </td>
            <td width="60px" align="right">
                <input type="text" name="source_tax_per[{{ $i }}]" id="source_tax_per{{ $i }}" value="" class="number integer" onchange="MsSrmProductReceiveDtl.copy({{ $i }},{{ $loop->count}},'source_tax_per')"/>
            </td>
            <td width="150px">
                <input type="text" name="job_no[{{ $i }}]" id="job_no{{ $i }}" value="{{ $colorsize->job_no }}" readonly />
                <input type="hidden" name="job_id[{{ $i }}]" id="job_id{{ $i }}" value="{{ $colorsize->job_id }}"/>
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
<p>Saved Item Details</p>
<table border="1" style="border-style:dotted">
    <tr align="center">
        <th colspan="11"  class="text-center"></th>
        <td colspan="7"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td></th>
    </tr>
    <tr align="center">
        <th width="50px"  class="text-center">SL</th>
        <th width="100px"  class="text-center">Style</th>
        <th width="100px"  class="text-center">BercodeID</th>
        <th width="150px"  class="text-center">Sales<br/>Order</th>
        <th width="150px"  class="text-center">Gmt Item</th>
        <th width="80px"  class="text-center">Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="100px"  class="text-center">UOM</th>
        <th width="80px"  class="text-center">Qty</th>
        <th width="80px"  class="text-center">Rate</th>
        <th width="100px"  class="text-center">Value</th>
        <th width="100px"  class="text-center">Selling<br/>Price</th>
        <th width="100px"  class="text-center">Currency</th>
        <th width="60px"  class="text-center">VAT %</th>
        <th width="60px"  class="text-center">S.TAX %</th>
        <th width="100px"  class="text-center">Job No</th>
    </tr>
    <tbody>
        <?php
        $i=1;
        $tQty=0;
        $tSaleRate=0;
        ?>
        @foreach($saved as $colorsize)
         <tr align="center">
            <td width="30px">
                    {{ $i }}
            </td>
            <td width="120px">
                <input type="text" name="style_ref[{{ $i }}]" id="style_ref{{ $i }}" value="{{ $colorsize->style_ref }}" readonly/>
                <input type="hidden" name="style_id[{{ $i }}]" id="style_id{{ $i }}" value="{{ $colorsize->style_id }}"/>
                <input type="hidden" name="country_id[{{ $i }}]" id="country_id{{ $i }}" value="{{ $colorsize->country_id }}"/>
            </td>
            <td width="150px">
                <a href="javascript:void(0)" onClick="MsSrmProductReceiveDtl.bercodePdf({{ $colorsize->srm_product_receive_dtl_id}})"> {{ $colorsize->srm_product_receive_dtl_id }}</a>
            </td>
            <td width="150px">
                <input type="text" name="sale_order_no[{{ $i }}]" id="sale_order_no{{ $i }}" value="{{ $colorsize->sale_order_no }}" readonly />
                <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $colorsize->sales_order_id }}"/>
            </td>
            <td width="150px">
                <input type="text" name="style_gmt_name[{{ $i }}]" id="style_gmt_name{{ $i }}" value="{{ $colorsize->style_gmt_name }}" readonly />
                <input type="hidden" name="style_gmt_id[{{ $i }}]" id="style_gmt_id{{ $i }}" value="{{ $colorsize->style_gmt_id }}"/>
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="150px">
                <input type="text" name="color_name[{{ $i }}]" id="color_name{{ $i }}" value="{{ $colorsize->color_name }}" readonly/>
                <input type="hidden" name="color_id[{{ $i }}]" id="color_id{{ $i }}" value="{{ $colorsize->color_id }}"/>
            </td>
            <td width="150px">
                <input type="text" name="size_name[{{ $i }}]" id="size_name{{ $i }}" value="{{ $colorsize->size_name }}" readonly/>
                <input type="hidden" name="size_id[{{ $i }}]" id="size_id{{ $i }}" value="{{ $colorsize->size_id }}"/>
            </td>
            <td width="100px" align="right">
                {{ $colorsize->uom_code }}
                <input type="hidden" name="uom_id[{{ $i }}]" id="uom_id{{ $i }}" value="{{ $colorsize->uom_id }}"/>
            </td>
            <td width="80px" align="right">
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $colorsize->qty }}" class="number integer"  onchange="MsSrmProductReceiveDtl.calculate({{ $i }},{{ $loop->count}})" />
            </td>
            <td width="80px" align="right">
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" value="{{ $colorsize->rate }}" class="number integer"  onchange="MsSrmProductReceiveDtl.calculate({{ $i }},{{ $loop->count}})" />
            </td>
            <td width="100px" align="right">
                <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" value="{{ $colorsize->amount }}" class="number integer" />
            </td>
            <td width="100px" align="right">
                <input type="text" name="sales_rate[{{ $i }}]" id="sales_rate{{ $i }}" value="{{ $colorsize->sales_rate }}" class="number integer" onchange="MsSrmProductReceiveDtl.copy({{ $i }},{{ $loop->count}},'sales_rate')" />
            </td>
            <td width="100px" align="right">
                {{ $colorsize->currency_code }}
                <input type="hidden" name="currency_id[{{ $i }}]" id="currency_id{{ $i }}" value="{{ $colorsize->currency_id }}"/>
            </td>
            <td width="60px" align="right">
                <input type="text" name="vat_per[{{ $i }}]" id="vat_per{{ $i }}" value="{{ $colorsize->vat_per }}" class="number integer" onchange="MsSrmProductReceiveDtl.copy({{ $i }},{{ $loop->count}},'vat_per')"/>
            </td>
            <td width="60px" align="right">
                <input type="text" name="source_tax_per[{{ $i }}]" id="source_tax_per{{ $i }}" value="{{ $colorsize->source_tax_per }}" class="number integer" onchange="MsSrmProductReceiveDtl.copy({{ $i }},{{ $loop->count}},'source_tax_per')"/>
            </td>
            <td width="150px">
                <input type="text" name="job_no[{{ $i }}]" id="job_no{{ $i }}" value="{{ $colorsize->job_no }}" readonly />
                <input type="hidden" name="job_id[{{ $i }}]" id="job_id{{ $i }}" value="{{ $colorsize->job_id }}"/>
            </td>
        </tr>
        <?php
            $i++;
            $tQty+=$colorsize->qty;
            $tSaleRate+=$colorsize->sales_rate;
        ?>
        
        @endforeach
    </tbody>
    <tfoot>
        <tr align="center">
            <td width="30px">
                {{ $i }}
        </td>
        <td colspan="9"> Total </td>
        {{-- <td width="150px"></td>
        <td width="150px"></td>
        <td width="150px"></td>
        <td width="150px"></td>
        <td width="150px"></td>
        <td width="100px" align="right"></td>
        <td width="80px" align="right"></td>
        <td width="80px" align="right"></td> --}}
        <td width="100px" align="right">{{ $tQty }}</td>
        <td width="100px" align="right">{{ $tSaleRate }}</td>
        <td width="100px" align="right"></td>
        <td width="60px" align="right"></td>
        <td width="60px" align="right"></td>
        <td width="150px"></td>
        <td width="80px"></td>
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
