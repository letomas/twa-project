var specifiedElement = document.getElementById('sub-nav-title--manage');

document.addEventListener('click', function(event) {
    var isClickInside = specifiedElement.contains(event.target);
    if (isClickInside) {
        //inside
        document.getElementById('sub-nav--manage').style.height = "13.5rem";
    } else {
        //outside
        document.getElementById('sub-nav--manage').style.height = "0rem";
    }
});