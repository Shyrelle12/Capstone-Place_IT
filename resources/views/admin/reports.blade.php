<title>Admin Reports</title>
<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(' AdminReports') }}
        </h2>
    </x-slot>
    <div class="container mx-auto mt-2 p-5 max-w-5xl" id="print-container"> <!-- Reduced width with max-w-4xl -->
        <div class="p-5 bg-white shadow-md rounded-lg">
            <!-- Approved Negotiations Report Card -->
                <div class="card shadow-lg bg-gray-300 border-light p-5 rounded h-100  mt-3">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x mb-3 text-primary"></i>
                        <p class="card-text text-muted">Total approved negotiations: <strong>{{ $approvedNegotiations }}</strong></p>        
                    </div>
                </div>

            <!-- Pending Negotiations Report Card -->
                <div class="card shadow-lg bg-gray-300 border-light p-5 rounded h-100 mt-2">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-3x mb-3 text-warning"></i>
                        <p class="card-text text-muted">Total pending negotiations:  <strong>{{ $pendingNegotiations }}</strong></p>
                    </div>
                </div>

            <!-- Total Negotiations Report Card -->
            <a href="{{ route('admin.negotiations') }}" class="btn btn-outline-info">
            <div class="card shadow-lg bg-gray-300 hover:bg-gray-400 border-light p-5 rounded h-100 mt-2">
                <div class="card-body text-center">
                    <i class="fas fa-comments fa-3x mb-3 text-info"></i>
                    <p class="card-text text-muted">Total number of all negotiations: <strong>{{ $totalNegotiations }}</strong></p>
                </div>
            </div>
            </a>
            <!-- System Feedback Report Card -->
            <a href="{{ route('admin.feedbacks') }}" class="btn btn-outline-danger">
                <div class="card shadow-lg bg-gray-300 hover:bg-gray-400 border-light p-5 rounded h-100 mt-2">
                    <div class="card-body text-center">
                        <i class="fas fa-comments-dollar fa-3x mb-3 text-danger"></i>
                        <p class="card-text text-muted">Total system feedbacks received: <strong>{{ $systemFeedbacks }}</strong></p>
                    </div>
                </div>
            </a>
            <!-- Reviews Report Card -->
            <a href="{{ route('admin.reviews') }}" class="btn btn-outline-info">
                <div class="card shadow-lg bg-gray-300 hover:bg-gray-400 border-light p-5 rounded h-100 mt-2">
                    <div class="card-body text-center">
                        <i class="fas fa-star fa-3x mb-3 text-info"></i>
                        <p class="card-text text-muted">Total reviews on rental agreements: <strong>{{ $reviews }}</strong></p>
                    </div>
                </div>
            </a>

            <!-- Payment History Report Card -->
            <a href="{{ route('admin.payments') }}" class="btn btn-outline-secondary">
                <div class="card shadow-lg bg-gray-300 hover:bg-gray-400 border-light p-5 rounded h-100 mt-2">
                    <div class="card-body text-center">
                        <i class="fas fa-credit-card fa-3x mb-3 text-secondary"></i>
                        <p class="card-text text-muted">Total payments made: <strong>{{ $paymentHistory }}</strong></p>
                    </div>
                </div>
            </a>

            <!-- Total Earnings Report Card -->
            <div class="card shadow-lg bg-gray-300 hover:bg-gray-400 border-light p-5 rounded h-100 mt-2">
                <div class="card-body text-center">
                    <i class="fas fa-wallet fa-3x mb-3 text-success"></i>
                    <p class="card-text text-muted">Total earnings 10% of each successful negotiations: <strong>₱{{ number_format($totalEarnings, 2) }}</strong></p>            
                </div>
            </div>
            <button onclick="printReport()" class="btn btn-primary mt-3 hover:text-slate-400">Print Report</button>
          </div>
    </div>

    <script>
    function printReport() {
        // Get the content of the container
        var printContent = document.getElementById('print-container').innerHTML;

        // Remove the Print Report button from the content
        printContent = printContent.replace('<button onclick="printReport()" class="btn btn-primary mt-3">Print Report</button>', '');

        // Get the original content of the body
        var originalContent = document.body.innerHTML;

        // Create a new window for printing
        var printWindow = window.open('', '', 'height=800,width=700');

        // Write the content to the new window
        printWindow.document.write('<html><head><title>Print Report</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: "Courier New", monospace; margin: 0; padding: 0; width: 200px; font-size: 12px; }');
        printWindow.document.write('.receipt { width: 100%; margin: 0 auto; padding: 10px; border: 1px solid #000; text-align: left; }');
        printWindow.document.write('.receipt-header { font-size: 14px; font-weight: bold; margin-bottom: 10px; }');
        printWindow.document.write('.receipt-footer { margin-top: 10px; font-size: 12px; }');
        printWindow.document.write('.receipt .section { margin-bottom: 10px; text-align: left; }');
        printWindow.document.write('.receipt .total { font-weight: bold; }');
        printWindow.document.write('.btn { display: none; }'); // Hide button in print preview
        printWindow.document.write('</style>'); 
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="receipt">');
        printWindow.document.write('<h2>Admin Reports</h2>');
        printWindow.document.write(printContent); // Print the content of the container
        printWindow.document.write('<div class="receipt-footer">');
        printWindow.document.write('<p>Thank you for using our system!</p>');
        printWindow.document.write('</div>');
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');

        // Wait for the window to load, then trigger the print
        printWindow.document.close();
        printWindow.document.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }
</script>


</x-app-layout>
