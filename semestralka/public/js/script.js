var linkManage = document.getElementById('sub-nav-title--manage');

document.addEventListener('click', function(event) {
    var isClickInside = linkManage.contains(event.target);
    if (isClickInside) {
        //inside
        document.getElementById('sub-nav--manage').style.height = "16rem";
    } else {
        //outside
        document.getElementById('sub-nav--manage').style.height = "0rem";
    }
});

var linkAccount = document.getElementById('sub-nav-title--account');

document.addEventListener('click', function(event) {
    var isClickInside = linkAccount.contains(event.target);
    if (isClickInside) {
        //inside
        document.getElementById('sub-nav--account').style.height = "5rem";
    } else {
        //outside
        document.getElementById('sub-nav--account').style.height = "0rem";
    }
});