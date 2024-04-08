document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('registerPack').addEventListener('submit', function (event) {
        event.preventDefault(); 

        var subscriptionTierID = document.getElementById('subscription_tier').value;

        var currentDate = new Date();
        var startDate = currentDate.toISOString().split('T')[0]; 

        var endDate = new Date(currentDate);
        endDate.setMonth(endDate.getMonth() + 1);
        endDate = endDate.toISOString().split('T')[0]; 

        var confirmationMessage = 'Are you sure you want to register this subscription pack?';
        confirmationMessage += '\nStart Date: ' + startDate + '\nEnd Date: ' + endDate;

        if (confirm(confirmationMessage)) {
            this.submit();
        }
    });
});
