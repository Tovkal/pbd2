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

function showSuccess($alert, msg) {
    setupAlert($alert, "alert-success fade-in", msg);
    $alert.delay(3000).fadeOut("slow", function() {
        $alert.attr("class", "hidden");
        $alert.removeAttr("style");
    });
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