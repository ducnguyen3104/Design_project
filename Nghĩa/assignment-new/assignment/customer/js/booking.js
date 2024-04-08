function toggleBookingForm(subServiceID) {
    var button = document.getElementById('result_' + subServiceID).querySelector('button');
    var form = document.getElementById('booking_form_' + subServiceID);
    var allForms = document.querySelectorAll('[id^="booking_form_"]');
    var allButtons = document.querySelectorAll('[id^="result_"] button');
    // Hide all booking forms and show all booking buttons
    allForms.forEach(function (item) {
        item.style.display = 'none';
    });
    allButtons.forEach(function (item) {
        item.style.display = 'inline-block';
    });
    // Show the booking form for the clicked sub-service and hide its button
    if (form.style.display === 'none') {
        form.style.display = 'block';
        button.style.display = 'none';
    } else {
        form.style.display = 'none';
        button.style.display = 'inline-block';
    }
}