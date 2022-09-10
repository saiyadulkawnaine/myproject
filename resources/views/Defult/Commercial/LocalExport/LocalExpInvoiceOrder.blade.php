<?php
    $i=1;
?>
@if($impdocaccepts->isNotEmpty())

<table border="1" class="table_form">
    <caption>New</caption>
    <tr align="center">
        <td width="150px">PI No</td>
        <td width="70px">PI Date</td>
        <td width="80px">Sales Order No</td>
        <td width="150px">Item <br/>Description</td>
        <td width="70px">PI Qty.</td>
        <td width="60px">PI Rate</td>
        <td width="70px">PI Value</td>
        <td width="70px">Current Inv. Qty.</td>
        <td width="60px">Rate</td>
        <td width="70px">Current Inv. Value</td>
        <td width="70px">Total Inv. Qty.</td>
        <td width="70px">PI Bal. Qty.</td>
        <td width="70px">Total Inv. Value</td>
        <td width="70px">PI Bal. Value.</td>
    </tr>
    <tbody>
        @foreach($impdocaccepts as $impdocaccept)
        <tr align="center">
            <td width="150px">
            {{ $impdocaccept->pi_no }}
                <input type="hidden" name="local_exp_invoice_id[{{ $i }}]" id="local_exp_invoice_id{{ $i }}" value="{{ $impdocaccept->local_exp_invoice_id }}"/>
                <input type="hidden" name="local_exp_pi_order_id[{{ $i }}]" id="local_exp_pi_order_id{{ $i }}" value="{{ $impdocaccept->local_exp_pi_order_id }}"/>
            </td>
            <td width="70px">{{ $impdocaccept->pi_date }}</td>
            <td width="80px">{{ $impdocaccept->sale_order_no }}</td>
            <td width="150px">{{ $impdocaccept->item_description }}</td>
            <td width="70px" align="right">{{ $impdocaccept->pi_qty }}</td>
            <td width="60px" align="right">{{ number_format($impdocaccept->pi_rate,4) }}</td>
            <td width="70px" align="right">{{ $impdocaccept->pi_amount }}</td>
            <td>
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value=" {{ $impdocaccept->pi_qty}}" onchange="MsLocalExpInvoiceOrder.calculate({{ $i }})"/>
            </td>
            <td>
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" value=" {{ $impdocaccept->pi_rate}}" onchange="MsLocalExpInvoiceOrder.calculate({{ $i }})" />
            </td>
            <td>
                <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" class="number integer" value=" {{ $impdocaccept->pi_amount }}" readonly />
            </td>
            <td align="right">{{ $impdocaccept->cumulative_qty }}</td>
            <td align="right">
            {{ $impdocaccept->pi_qty-( $impdocaccept->cumulative_qty-$impdocaccept->invoice_qty )}}
            </td>
            <td>{{ $impdocaccept->cumulative_amount }}</td>
            <td>{{ $impdocaccept->pi_amount-( $impdocaccept->cumulative_amount-$impdocaccept->invoice_amount ) }}</td>
        </tr>
        <?php
            $i++;
        ?>        
        @endforeach
    </tbody>
    <tfoot>
        <td width="150px"></td>
        <td width="70px"></td>
        <td width="80px"></td>
        <td width="150px"></td>
        <td width="70px"></td>
        <td width="60px"></td>
        <td width="70px"></td>
        <td width="60px" align="right"></td>
        <td width="70px"></td>
        <td width="70px" align="right"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tfoot>
</table>
@endif
@if($saved->isNotEmpty())

<table border="1" class="table_form">
  <caption>Saved</caption> 
    <tr align="center">
        <td width="150px">PI No</td>
        <td width="70px">PI Date</td>
        <td width="80px">Sales Order No</td>
        <td width="150px">Item <br/>Description</td>
        <td width="70px">PI Qty.</td>
        <td width="60px">PI Rate</td>
        <td width="70px">PI Value</td>
        <td width="70px">Current Inv. Qty.</td>
        <td width="60px">Rate</td>
        <td width="70px">Current Inv. Value</td>
        <td width="70px">Total Inv. Qty.</td>
        <td width="70px">PI Bal. Qty.</td>
        <td width="70px">Total Inv. Value</td>
        <td width="70px">PI Bal. Value.</td>
        <td width="70px"></td>
    </tr>
    <tbody>
    <?php
        $tot_invoice_qty=0;
        $tot_invoice_amount=0;
    ?>
    @foreach($saved as $row)
        <?php
            $tot_invoice_qty+=$row->invoice_qty;
            $tot_invoice_amount+=$row->invoice_amount;
        ?>
        <tr align="center">
            <td width="150px">
            {{ $row->pi_no }}
                <input type="hidden" name="local_exp_invoice_id[{{ $i }}]" id="local_exp_invoice_id{{ $i }}" value="{{ $row->local_exp_invoice_id }}"/>
                <input type="hidden" name="local_exp_pi_order_id[{{ $i }}]" id="local_exp_pi_order_id{{ $i }}" value="{{ $row->local_exp_pi_order_id }}"/>
            </td>
            <td width="70px">{{ $row->pi_date }}</td>
            <td width="80px">{{ $row->sale_order_no }}</td>
            <td width="150px">{{ $row->item_description }}</td>
            <td width="70px" align="right">{{ $row->pi_qty }}</td>
            <td width="60px" align="right">{{ number_format($row->pi_rate,4) }}</td>
            <td width="70px" align="right">{{ $row->pi_amount }}</td>
            <td>
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value=" {{ $row->invoice_qty }}" onchange="MsLocalExpInvoiceOrder.calculate({{$i}})"/>
            </td>
            <td>
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" value="{{ $row->invoice_rate }}" onchange="MsLocalExpInvoiceOrder.calculate({{$i}})" />
            </td>
            <td>
                <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" class="number integer" value=" {{ $row->invoice_amount}}" readonly />
            </td>
            <td align="right">
                {{ $row->cumulative_qty-$row->invoice_qty }}
            </td>
            <td align="right">
                {{ $row->pi_qty-($row->cumulative_qty-$row->invoice_qty) }}
            </td>
            <td>
                {{ $row->cumulative_amount-$row->invoice_amount}}
            </td>
            <td>{{ $row->pi_amount-( $row->cumulative_amount-$row->invoice_amount ) }}</td>
            <td>
                <a href="javascript:void(0)" onclick="MsLocalExpInvoiceOrder.delete(event,{{ $row->local_exp_invoice_order_id }})">Remove</a>
            </td>
        </tr>
    <?php
      $i++;
    ?>           
    @endforeach
    </tbody>
    <tfoot>
        <td width="150px"></td>
        <td width="70px"></td>
        <td width="80px"></td>
        <td width="150px"></td>
        <td width="70px"></td>
        <td width="60px"></td>
        <td width="70px"></td>
        <td width="70px" align="right">{{ $tot_invoice_qty }}</td>
        <td width="60px"></td>
        <td width="70px" align="right">{{ $tot_invoice_amount }}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="60px"></td>
    </tfoot>
</table>
@endif
<script>
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
</script>
