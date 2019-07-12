$(document).on("change", 'input[type="file"]', function () {
  var input = $(this),
      addContainer = input.closest(".photo-input-div"),
      files = input.prop("files"),
      fragment = document.createDocumentFragment();
  fragment.appendChild(input.get()[0]);
  var newPhotosContainer = $(document.createElement("div")),
      files_container = $(document.createElement("div"));
  
  newPhotosContainer.addClass("new-photos");
  newPhotosContainer.addClass("screen-div");
  newPhotosContainer.append(fragment);
  files_container.addClass("new-files-container");
  addContainer.after(newPhotosContainer);
  for(var i = 0; i < files.length; i++){
    files_container.append('<div class="photo-input-div"><div><div class="photo-icon"><span class="icon-picture"></span></div><div><span>' + files[i].name + '</span></div></div><div class="option-div"><label><input type="radio" name="main-photo" value="' + files[i].name + '"><div class="page-button inline"><span class="icon-checkbox"></span>Główne</div></label></div></div>');
  }
  newPhotosContainer.append(files_container);
  newPhotosContainer.append('<div class="files-options"><button type="button" class="page-button danger remove-files"><span class="button-icon icon-trash"></span>Usuń</button></div>');
  addContainer.children(".image-container.min-image").children("label").html('<input type="file" name="photos[]" accept="image/*" class="input-file" multiple><span>+</span>');
});

$(document).on("click", 'button.remove-files', function () {
  var newPhotos = $(this).closest(".new-photos");
  if(newPhotos != null){
    newPhotos.remove();
  }
});