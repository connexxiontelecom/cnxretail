@foreach ($bills as $item)
    <tr class="item">
        <td>
            <p>Payment for bill no. {{$item->bill_no ?? ''}}</p>
        </td>
        <td>
            <p>{{date('d F, Y h:ia', strtotime($item->bill_date)) ?? ''}}</p>
        </td>
        <td>
            <p>{{number_format($item->bill_amount/$item->exchange_rate,2) ?? ''}}</p>
        </td>
        <td>
            <p>{{number_format($item->paid_amount/$item->exchange_rate,2)}}</p>

            </td>

        <td>
        <p>{{number_format($item->bill_amount/$item->exchange_rate - $item->paid_amount/$item->exchange_rate,2)}}</p>

        <input type="hidden" name="totalAmount" id="totalAmount" value="{{$total += $item->bill_amount/$item->exchange_rate - $item->paid_amount/$item->exchange_rate}}">
        <input type="hidden" name="exchange_rate[]" value="{{$item->exchange_rate}}">
        <input type="hidden" name="currency[]" value="{{$item->currency_id}}">
        <input type="hidden" name="bill[]" value="{{$item->id}}">
        </td>
        <td>
            <div class="form-group form-primary form-static-label">
                <input type="number" step="0.01" name="payment[]" class="form-control total_amount">
                <span class="form-bar"></span>
                <label class="float-label">Payment</label>
            </div>
        </td>
    </tr>
@endforeach
