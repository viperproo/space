var optionsContainerCSS = '.select-options-container',
    selectTagCSS = '.select-tag',
    selectTags = $(selectTagCSS),
    select = $('.select-selected-option'),
    radio = $(optionsContainerCSS + ' input[type="radio"]'),
    optionsContainer = $(optionsContainerCSS),
    className = 'hidden';

$(document).on('click', function (ev) {
  var clicked = $(ev.target),
      selectTag = clicked.closest(selectTagCSS);
  if(selectTag.length == 1){
    if(clicked.is("input")){
      selectTags.addClass(className);
    }else{
      selectTag.toggleClass(className);
    }
  }else{
    selectTags.addClass(className);
  }
});

radio.on('click', function () {
  var t = $(this),
      val = t.next().text(),
      span = t.parent().parent().prev().children('.select-value');
  span.text(val);
});