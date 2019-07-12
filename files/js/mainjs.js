var classSticky = "sticky",
    visibleNavClass = "show",
    nav,
    navPosition,
    menu,
    hamburgerBtn,
    scrollableContainer;

function MenuSticky() {
  if (scrollableContainer.scrollTop() >= navPosition) {
    menu.addClass(classSticky);
  } else {
    menu.removeClass(classSticky);
  }
  
  menu.css("right", $("body").width() - menu.parent().width() + "px");
}

function MenuHide() {
  nav.removeClass(visibleNavClass);
}

function MenuToggle() {
  nav.toggleClass(visibleNavClass);
}

function RemoveCookieInfo() {
  var cookieInfo = $("#cookies-info-div");
  cookieInfo.addClass("hidden");
  window.setTimeout(function () {
    cookieInfo.remove();
  }, 500);
}

$(document).ready(function () {
  var cookieBtn = $("#cookie-div-close-button");
  nav = $("#nav-links-container");
  menu = $("#menu");
  hamburgerBtn = $(".menu-toggle-button");
  scrollableContainer = $("#body");
  navPosition = $("#menu-container").offset().top + scrollableContainer.scrollTop();

  MenuSticky();

  scrollableContainer.on("scroll", function () {
    MenuSticky();
  });
  
  $(this).on("click", function (ev) {
    var target = $(ev.target);
    if(target.is(hamburgerBtn)){
      MenuToggle();
    }else{
      MenuHide();
    }
  });
  
  if(cookieBtn.length > 0) {
    cookieBtn.on("click", RemoveCookieInfo);
  }
});