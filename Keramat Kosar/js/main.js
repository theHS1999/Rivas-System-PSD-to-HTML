$(".dropdown a.dropdown-hover").append(`<img src="img/down-caret.png" alt="caret" class="caret">`);

var navbarList = $(".nav-items").html();


function setNavbar() {
    let windowWidth = $(window).width();


    if(windowWidth > 960) {
        $(".dropdown-nav-menu .dropdown-nav-items .nav-item").remove();
        $(".nav-items").html(navbarList);
    }

    if(windowWidth < 960) {
        $(".nav-items .nav-item").remove();
        $(".dropdown-nav-menu .dropdown-nav-items").html(navbarList);
    }
}

$(".dropdown").off("click");
$(".dropdown-nav-menu").off("click");



$(".nav-dropdown-link").on("click", function(e) {
    console.log("here");
    let navDropdownState = $(".dropdown-nav-menu").css("display");
    if(navDropdownState === "none") {
        $(".dropdown-nav-menu").slideDown(300);
        $(".nav-dropdown-link i").css("transform", "rotate(180deg)");
    } else {
        $(".dropdown-nav-menu").slideUp(300);
        $(".nav-dropdown-link i").css("transform", "rotate(0)");
    }



    e.stopPropagation();
});



$(document).on("click", function() {
    $(".nav-dropdown-link i").css("transform", "rotate(0)");
    $(".dropdown-nav-menu").slideUp(300);
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

$(".search-toggler").on("click", function() {
    if($(this).hasClass("off")) {
        $(this).find(".fa-search").removeClass("icon-active").addClass("icon-disabled");
        $(this).find(".fa-times").removeClass("icon-disabled").addClass("icon-active");
        $(".search-section").slideDown(300);
        $(this).removeClass("off");
    } else {
        $(this).find(".fa-times").removeClass("icon-active").addClass("icon-disabled");
        $(this).find(".fa-search").removeClass("icon-disabled").addClass("icon-active");
        $(".search-section").slideUp(300);
        $(this).addClass("off");
    }
});
