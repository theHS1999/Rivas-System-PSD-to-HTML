$(".dropdown a.dropdown-hover").append(`<img src="img/down-caret.png" alt="caret" class="caret">`);

var navbarList = $(".nav-items").html();
var iconsList = $(".icons-section .nav-icons").html();

//console.log(iconsList)

function setNavbar() {
    let windowWidth = $(window).width();


    if(windowWidth > 960) {

        if($(".nav-items").hasClass("hidden")) {
            $(".nav-items").removeClass("hidden");
            $(".dropdown-nav-items .nav-item").remove();
            $(".nav-items").html(navbarList);
        }
        if($(".icons-section").hasClass("dropdown")) {
            $(".icons-section").removeClass("dropdown");
            console.log("class removed");
            $(".icons-section .dropdown-menu .dropdown-icons .nav-icon").remove();
            $(".icons-section .nav-icons").html(iconsList);
        }
        $(".dropdown").mouseenter(function() {
            $(this).find(".caret").css("transform", "rotate(180deg)");
            $(this).find(".dropdown-menu").fadeIn(300);
        });
        $(".dropdown").mouseleave(function() {
            $(this).find(".caret").css("transform", "rotate(0)");
            $(this).find(".dropdown-menu").fadeOut(300);
        });
    }

    if(windowWidth > 720 && windowWidth < 960) {
        if($(".nav-items").hasClass("hidden")) {
            $(".nav-items").removeClass("hidden");
            $(".dropdown-nav-items .nav-item").remove();
            $(".nav-items").html(navbarList);
        }
        $(".icons-section").addClass("dropdown");
        $(".icons-section .nav-icons .nav-icon").remove();
        $(".icons-section .dropdown-menu .dropdown-icons").html(iconsList);

        $(".dropdown").click(function(e) {
            var dropdownState = $(this).find(".dropdown-menu").css("display");
            if(dropdownState === "none") {
                $(".dropdown").each(function () {
                    if($(this).find(".dropdown-menu").css("display") === "block") {
                        $(this).find(".caret").css("transform", "rotate(0)");
                        $(this).find(".dropdown-menu").fadeOut(300);
                    }
                });
                $(this).find(".caret").css("transform", "rotate(180deg)");
                $(this).find(".dropdown-menu").fadeIn(300);
            } else {
                $(this).find(".caret").css("transform", "rotate(0)");
                $(this).find(".dropdown-menu").fadeOut(300);
            }
            e.stopPropagation();
        });

        $(document).click(function() {
            $(".dropdown .caret").css("transform", "rotate(0)");
            $(".dropdown .dropdown-menu").fadeOut(300);
        });
    }

    if(windowWidth < 720) {
        $(".nav-items").addClass("hidden");
        $(".nav-items .nav-item").remove();
        $(".dropdown-nav-items").html(navbarList);
        if($(".icons-section").hasClass("dropdown")) {
            $(".icons-section").removeClass("dropdown");
            $(".icons-section .dropdown-menu .dropdown-icons .nav-icon").remove();
            $(".icons-section .nav-icons").html(iconsList);
        }

        $(".dropdown-nav-menu").click(function(e) {
            let navDropdownState = $(this).find(".dropdown-nav-items").css("display");
            if(navDropdownState === "none") {
                $(this).find(".dropdown-nav-items").slideDown(300);
                $(this).find("#navbar-drop-caret").css("transform", "rotate(180deg)");
            } else {
                $(this).find(".dropdown-nav-items").slideUp(300);
                $(this).find("#navbar-drop-caret").css("transform", "rotate(0)");
            }
            e.stopPropagation();
        });

        $(".dropdown").click(function(e) {
            let dropdownState = $(this).find(".dropdown-menu").css("display");
            if(dropdownState === "none") {
                $(".dropdown").each(function () {
                    if($(this).find(".dropdown-menu").css("display") === "block") {
                        $(this).find(".caret").css("transform", "rotate(0)");
                        $(this).find(".dropdown-menu").slideUp(300);
                    }
                });
                $(this).find(".caret").css("transform", "rotate(180deg)");
                $(this).find(".dropdown-menu").slideDown(300);
            } else {
                $(this).find(".caret").css("transform", "rotate(0)");
                $(this).find(".dropdown-menu").slideUp(300);
            }
            e.stopPropagation();
        });
        $(document).click(function() {
            $(".dropdown .caret").css("transform", "rotate(0)");
            $(".dropdown .dropdown-menu").fadeOut(300);
        });
    }
}

setNavbar();

$(window).resize(function() {
    setNavbar();
});
