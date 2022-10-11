<table>
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Transaction type</th>
            <th>Items</th>
            <th>Created By</th>
            <th>Timestamp</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->type }}</td>
                <td>
                    @foreach ($transaction->items as $item)
                    {{ $item->name }}
                    <br>
                    @isset($item->pivot->from_count, $item->pivot->to_count)
                    {{ $item->pivot->from_count }} to {{ $item->pivot->to_count }}
                    @endisset
                    @endforeach
                </td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->created_at->format('m/d/y g:i A') }}</td>
                <td>{{ $transaction->comment }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
