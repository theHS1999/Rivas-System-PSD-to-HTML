function setNavbar() {
    let pageWidth = $(window).width();

    if(pageWidth < 960) {
        $(".navbar-items").css("display", "none");
        $(".side-menu-toggler").css("display", "block");
    } else {
        if($(".navbar-items").css("display") === "none") {
            $(".navbar-items").css("display", "block");
            $(".side-menu-toggler").css("display", "none");
        }
    }
}

$(".side-menu-toggler").click(function(e) {
    $(".cover").addClass("show");
    $(".side-menu").addClass("open");

    e.stopPropagation();
});

$(document).click(function() {
    $(".cover").removeClass("show");
    $(".side-menu").removeClass("open");
});

setNavbar();
$(window).resize(function() {
    setNavbar();
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