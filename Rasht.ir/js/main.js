$(".dropdown a.dropdown-hover").append(`<img src="img/down-caret.png" alt="caret" class="caret">`);

var navbarList = $(".nav-items").html();
var iconsList = $(".icons-section .nav-icons").html();


function setNavbar() {
    let windowWidth = $(window).width();


    if(windowWidth > 720) {

        if($(".nav-items").hasClass("hidden")) {
            $(".nav-items").removeClass("hidden");
            $(".dropdown-nav-items .nav-item").remove();
            $(".nav-items").html(navbarList);
        }
    }

    if(windowWidth < 720) {


        $(".nav-items").addClass("hidden");
        $(".nav-items .nav-item").remove();
        $(".dropdown-nav-items").html(navbarList);
    }
}

$(".dropdown").off("click");
$(".dropdown-nav-menu").off("click");



$(".dropdown-nav-menu").on("click", function(e) {
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


$(".dropdown-nav-items").on("click", function(e) {
    $(".dropdown-nav-items .dropdown").on("click", function(e) {
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

    e.stopPropagation();
});


$(".dropdown").on("click", function(e) {
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

$(document).on("click", function() {
    $(".dropdown .caret").css("transform", "rotate(0)");
    $(".dropdown .dropdown-menu").fadeOut(300);
});

setNavbar();

$(window).resize(function() {
    setNavbar();

});

$("#search").focus(function () {
    $(".search-icon img").attr("src", "img/search-icon-focus.png");
});

$("#search").focusout(function () {
    $(".search-icon img").attr("src", "img/search-icon.png");
});


var scrollTrigger = 100, // px
backToTop = function () {
    var scrollTop = $(window).scrollTop();
    if (scrollTop > scrollTrigger) {
        $('#back-to-top').addClass('show');
    } else {
        $('#back-to-top').removeClass('show');
    }
};
backToTop();
$(window).on('scroll', function () {
    backToTop();
});
$('#back-to-top').on('click', function (e) {
    e.preventDefault();
    $('html,body').animate({
        scrollTop: 0
    }, 700);
});
