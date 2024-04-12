let map;
let directionsService;
let directionsRenderer;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 10.762622, lng: 106.660172 }, 
    });
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
}

function calculateAndDisplayRoute() {
    let start = document.getElementById("address1").value;
    let end = document.getElementById("address2").value;

    directionsService.route({
        origin: start,
        destination: end,
        travelMode: google.maps.TravelMode.DRIVING,
    }, function(response, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}
