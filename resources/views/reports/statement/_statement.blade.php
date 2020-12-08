<table  class="table table-striped table-bordered nowrap simpletable">
    <thead>
    <tr>
        <th>S/No.</th>
        <th>Date</th>
        <th>Narration</th>
        <th>DR</th>
        <th>CR</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
        @php
            $n = 1;
        @endphp
    @foreach ($payments as $pay)
            <tr>
                <td>{{$n++}}</td>
                <td>{{date('d M, Y', strtotime($pay->transaction_date)) ?? ''}}</td>
                <td>{{$pay->narration ?? ''}}</td>
                <td>{{number_format($pay->type == 2 ? $pay->amount : 0,2)}}</td>
                <td>{{number_format($pay->type == 1 ? $pay->amount : 0,2)}}</td>
                <input type="hidden" value="{{$balance += ($balance + $pay->type == 2 ? $pay->amount : 0) - ($pay->type == 1 ? $pay->amount : 0)}}">
                <td>{{number_format( $balance,2)}}</td>
            </tr>
    @endforeach
    <tr>
        <td colspan="5" class="text-right"><strong style="font-weight: 700;">BALANCE: </strong></td>
        <td>{{number_format($balance,2)}}</td>
    </tr>
    </tfoot>
</table>

