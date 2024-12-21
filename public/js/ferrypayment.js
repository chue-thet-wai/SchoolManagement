$(document).ready(function() {
    // Event delegation for dynamically generated date inputs
    $(document).on('change', '#paid_paiddate_from, #paid_paiddate_to', function() {
        var modal = $(this).closest('.modal');
        var fromDateInput = modal.find('#paid_paiddate_from');
        var toDateInput = modal.find('#paid_paiddate_to');
        var waySelect = modal.find('#paid_ferryway_id');
        var netTotalInput = modal.find('#paid_nettotal');
        var onewayPickupAmountInput = modal.find('#oneway_pickup_amount');
        var onewayBackAmountInput = modal.find('#oneway_back_amount');
        var twoWayAmountInput = modal.find('#two_way_amount');

        if (fromDateInput.val() && toDateInput.val()) {
            // Validate date range
            if (new Date(fromDateInput.val()) > new Date(toDateInput.val())) {
                fromDateInput.val('');
                return;
            }
            calculateTotalAmount(fromDateInput, toDateInput, waySelect, netTotalInput, onewayPickupAmountInput, onewayBackAmountInput, twoWayAmountInput);
        }
    });

    // Function to calculate total amount
    function calculateTotalAmount(fromDateInput, toDateInput, waySelect, netTotalInput, onewayPickupAmountInput, onewayBackAmountInput, twoWayAmountInput) {
        var fromDate = new Date(fromDateInput.val());
        var toDate = new Date(toDateInput.val());
        var wayMonthly = 0;

        // Get monthly way amount based on selected option
        switch (waySelect.val()) {
            case '1': // one way pickup
                wayMonthly = parseFloat(onewayPickupAmountInput.val());
                break;
            case '2': // one way back
                wayMonthly = parseFloat(onewayBackAmountInput.val());
                break;
            default: // two way
                wayMonthly = parseFloat(twoWayAmountInput.val());
                break;
        }

        // Check if the dates represent the start and end of the same month
        if (fromDate.getMonth() === toDate.getMonth() && fromDate.getFullYear() === toDate.getFullYear() && fromDate.getDate() === 1 && toDate.getDate() === new Date(toDate.getFullYear(), toDate.getMonth() + 1, 0).getDate()) {
            netTotalInput.val(Math.round(wayMonthly));
        } else {
            var timeDifference = toDate.getTime() - fromDate.getTime();
            var dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)); // Convert milliseconds to days
            var wayDaily = wayMonthly / 30;
            var totalAmount = wayDaily * dayDifference;
            var roundedTotalAmount = Math.round(totalAmount);
            netTotalInput.val(roundedTotalAmount); 
        }
    }
});
