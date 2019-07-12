var hiddenClass = "hidden",
    html,
    images,
    status = false,
    img_fs_container_name = "image-fs-container",
    img_fs_header_name = "image-fs-header",
    img_fs_img_container_name = "image-fs-img-container",
    nextClass = "next",
    prevClass = "prev",
    img_index;

function CreateImgFsButton(span_class, button_id) {
  var button = document.createElement("button"),
      span = document.createElement("span");
  
  span.classList.add(span_class);
  button.appendChild(span);
  button.setAttribute("id", button_id);
  button.classList.add("images-nav-buttons", "nav-color");
  
  return button;
}

function f(ev) {
  switch(ev.keyCode){
    case 27:
      RemoveFsImageContainer();
      break;
    case 37:
      PrevImage();
      break;
    case 39:
      NextImage()
      break;
  }
}

function RemoveFsImageContainer() {
  window.removeEventListener("keyup", f);
  var fsContainer = $("#" + img_fs_container_name);
  fsContainer.addClass(hiddenClass);
  window.setTimeout(function () {
    fsContainer.remove();
  }, 400);
}

function AddFsImageContainer() {
  var image_fs_container = document.createElement("div"),
      image_fs_header = document.createElement("div"),
      image_fs_img = document.createElement("div"),
      image_title = document.createElement("div"),
      image_buttons_div_container = document.createElement("div"),
      image_close_button = CreateImgFsButton("icon-cancel", "close-img");
  
  window.addEventListener("keyup", f, false);
  
//  image_fs_container.addEventListener("keyup", function (ev) {
//    if(ev.keyCode === 27){
//      RemoveFsImageContainer();
//    }
//  }, true);
  image_close_button.addEventListener("click", RemoveFsImageContainer, false);

  image_fs_container.setAttribute("id", img_fs_container_name);
  image_fs_container.classList.add(hiddenClass);
  image_fs_header.setAttribute("id", img_fs_header_name);
  image_fs_header.classList.add(hiddenClass);
  image_fs_img.setAttribute("id", img_fs_img_container_name);
  image_fs_img.addEventListener("click", RemoveFsImageContainer, false);

  image_title.setAttribute("id", "image-title");
  image_buttons_div_container.setAttribute("id", "image-buttons-container");

  if(images.length > 1){
    var image_prev_button = CreateImgFsButton("icon-left-open", "prev-img"),
        image_next_button = CreateImgFsButton("icon-right-open", "next-img");

    image_prev_button.addEventListener("click", PrevImage, false);
    image_next_button.addEventListener("click", NextImage, false);
//    image_fs_container.addEventListener("keyup", function (ev) {
//      if(ev.keyCode === 37){
//        PrevImage();
//      }else if(ev.keyCode === 39){
//        NextImage();
//      }
//    }, true);

    image_buttons_div_container.appendChild(image_prev_button);
    image_buttons_div_container.appendChild(image_next_button);
  }

  image_buttons_div_container.appendChild(image_close_button);

  image_fs_header.appendChild(image_title);
  image_fs_header.appendChild(image_buttons_div_container);

  image_fs_container.appendChild(image_fs_header);
  image_fs_container.appendChild(image_fs_img);

  $("main").append(image_fs_container);
  window.setTimeout(function () {
    image_fs_container.classList.remove(hiddenClass);
  }, 10);
}

function AddImage() {
  var last = images.length - 1;
  
  if(img_index > last){
    img_index = 0;
  }else if(img_index < 0){
    img_index = last;
  }
  
  var img = images.eq(img_index),
      image = document.createElement("img"),
      newImgContainer = document.createElement("div");
  
  image.setAttribute("src", img.attr("src"));
  image.setAttribute("alt", img.attr("alt"));
  newImgContainer.setAttribute("id", "active-fs-img");
  newImgContainer.classList.add("image-fs-img");
  newImgContainer.appendChild(image);
  
  $("#image-title").html(img.attr("alt"));
  $("#image-fs-img-container").append(newImgContainer);
  return newImgContainer;
}

function ChangeImage(from, to) {
  var oldImg = $("#active-fs-img");
  oldImg.removeAttr("id");
  var newImg = AddImage();
  oldImg.addClass(from);
  newImg.classList.add(to);
  
  window.setTimeout(function () {
    newImg.classList.remove(to);
  }, 10);
  window.setTimeout(function () {
    oldImg.remove();
  }, 600);
}

function PrevImage() {
  img_index--;
  ChangeImage(nextClass, prevClass);
}

function NextImage() {
  img_index++;
  ChangeImage(prevClass, nextClass);
}

$(document).ready(function () {
  images = $(".image-container > img");
  html = $("html");
  
  var doc = $(this);
  
  doc.on("click", ".image-container > img", function () {
    img_index = images.index($(this));
    AddFsImageContainer();
    AddImage();
  });

  doc.on("mousemove", function (ev) {
    var target = $(ev.target);
    clearTimeout(status);
    if(target.closest("#" + img_fs_container_name) != null){
      var div = document.querySelector("#" + img_fs_header_name);
      if(div != null){
        div.classList.remove(hiddenClass);
        status = window.setTimeout(function () {
          if(target.closest("#" + img_fs_header_name) == null){
            div.classList.add(hiddenClass);
          }
        }, 500);
      }
    }
  });
});