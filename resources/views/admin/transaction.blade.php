<x-app-layout>
<h2>Transaction Report</h2>

<!-- Transaction Table -->
<table class="table">
    <thead>
        <tr>
            <th>Payment ID</th>
            <th>Business Owner</th>
            <th>Space Owner</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction->paymentID }}</td>
            <td>{{ $transaction->renter->firstName }}</td>
            <td>{{ $transaction->spaceOwner->firstName }}</td>
            <td>{{ $transaction->amount }}</td>
            <td>{{ $transaction->status }}</td>
            <td>{{ $transaction->date }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Print Button -->
<button onclick="window.print();" class="btn btn-success">Print Report</button>

</x-app-layout>
