function setupAlert($alert, alertClass, msg) {
    $alert.attr("class", "alert " + alertClass);
    $alert.text(msg);
}

function showError($alert, msg) {
    setupAlert($alert, "alert-danger fade-in", msg);
}

function showInfo($alert, msg) {
    setupAlert($alert, "alert-info fade-in", msg);
}

function fadeAlert($alert, delay) {
    $alert.delay(delay).fadeOut("slow", function() {
        $alert.attr("class", "hidden");
        $alert.removeAttr("style");
    });
}

function fadeAlertWithDelay($alert, delay) {
    fadeAlert($alert, delay);
}

function fadeAlertWithoutDelay($alert) {
    fadeAlert($alert, 0);
}

function showSuccess($alert, msg, delay) {
    setupAlert($alert, "alert-success fade-in", msg);
    fadeAlertWithDelay($alert, delay);
}

// Custom jQuery functions
(function($) {
    $.fn.hideBootstrap = function() {
        if (!this.hasClass("hidden")) {
            this.addClass("hidden");
        }
    };
    $.fn.showBootstrap = function() {
        if (this.hasClass("hidden")) {
            this.removeClass("hidden");
        }
    };
    $.fn.isEmpty = function() {
        return this.val().length == 0;
    };
    $.fn.isVisible = function() {
        return !this.hasClass('hidden');
    };
})(jQuery);

function scrollToTop() {
    $('html, body').animate({
        scrollTop: $("#content").offset().top
    }, 750);
}

function getTodaysDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    }

    if(mm<10) {
        mm='0'+mm
    }

    today = dd+'/'+mm+'/'+yyyy;
    return today;

}