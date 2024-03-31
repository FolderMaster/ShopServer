var loginForm = localStorage.getItem("LoginForm");
var registrationForm = localStorage.getItem("RegistrationForm");
var countBlock = localStorage.getItem("CountBlock");

if (loginForm === null) {
  getHtml("/resources/html/login-form.html", function (html) {
    if (html) {
      localStorage.setItem("LoginForm", html);
      loginForm = html;
    }
  });
}

if (registrationForm === null) {
  getHtml("/resources/html/registration-form.html", function (html) {
    if (html) {
      localStorage.setItem("RegistrationForm", html);
      registrationForm = html;
    }
  });
}

if (countBlock === null) {
  getHtml("/resources/html/count-block.html", function (html) {
    if (html) {
      localStorage.setItem("CountBlock", html);
      countBlock = html;
    }
  });
}

function displayMessage(text, type) {
  var message = $('<div class="message ' + type + '">' + text + "</div>");
  $("#message-list").append(message);
  message.fadeIn(2000);
  message.fadeOut(2000, function () {
    message.remove();
  });
}

function displaySuccessMessage(data, status, xhr) {
  displayMessage(xhr.responseText, "success");
}

function displayErrorMessage(xhr, status, error) {
  displayMessage(xhr.responseText, "error");
}

function getHtml(url, callback) {
  $.ajax({
    type: "get",
    url: url,
    success: function (data, status, xhr) {
      callback(xhr.responseText);
    },
    error: function (xhr, status, error) {
      displayErrorMessage(xhr, status, error);
      callback(null);
    },
  });
}

function createModalWindow(html) {
  var form = $(html).submit(submitFormEvent);
  var window = $('<div class="modal block"></div>')
    .append(
      '<button class="right-float" onclick="$(\'.modal\').remove();">x</button>'
    )
    .append(form);
  var background = $(
    '<div class="modal background center-layout"></div>'
  ).append(window);
  $("body").prepend(background);
}

function submitFormEvent(e) {
  var ajax = $(this).attr("ajax");

  if (ajax) {
    e.preventDefault();

    var formMethod = $(this).attr("method");
    var formAction = $(this).attr("action");
    var successCode = $(this).attr("onsuccess");
    var formData = new FormData(this);

    switch (formMethod) {
      case "get":
        var query = new URLSearchParams(formData.entries());
        if (query.size > 0) {
          formAction += "?" + query.toString();
        }
        $.ajax({
          type: formMethod,
          url: formAction,
          processData: false,
          contentType: false,
          success: function (data, status, xhr) {
            displaySuccessMessage(data, status, xhr);
            if (successCode) {
              eval(successCode);
            }
          },
          error: displayErrorMessage,
        });
        break;
      case "post":
        $.ajax({
          type: formMethod,
          url: formAction,
          data: formData,
          processData: false,
          contentType: false,
          success: function (data, status, xhr) {
            displaySuccessMessage(data, status, xhr);
            if (successCode) {
              eval(successCode);
            }
          },
          error: displayErrorMessage,
        });
        break;
    }
  }
}

function changeInputsValueEvent(e) {
  var value = $(this).val();
  var name = $(this).attr("name");
  var form = $(this).closest(".form");
  var inputs = form.find('input[name="' + name + '"]');
  inputs.val(value);
}

function calculateTotalCost() {
  var countsInputs = new Map();
  $('input[name^="count["]').each(function () {
    var index = $(this)
      .attr("name")
      .match(/\[(\d+)\]/)[1];
    countsInputs.set(index, $(this));
  });
  var counts = [...countsInputs].map(function ([key, value]) {
    return value.val();
  });
  var costs = $('input[name^="cost["]')
    .map(function () {
      return $(this).val();
    })
    .get();
  var totalCost = 0;
  for (var i = 0; i < costs.length && i < counts.length; ++i) {
    totalCost += costs[i] * counts[i];
  }
  $('input[name="totalCost"]').val(totalCost);
  var enable = totalCost > 0;
  $('input[name="process-order-button"]').prop("disabled", !enable);
}

function clickButtonEvent(e) {
  e.preventDefault();

  var buttonMethod = $(this).attr("method");
  var buttonAction = $(this).attr("action");
  var successCode = $(this).attr("onsuccess");

  $.ajax({
    type: buttonMethod,
    url: buttonAction,
    processData: false,
    contentType: false,
    success: function (data, status, xhr) {
      displaySuccessMessage(data, status, xhr);
      if (successCode) {
        eval(successCode);
      }
    },
    error: displayErrorMessage,
  });
}

function checkAreAllInputsFilled(inputs) {
  var result = true;
  inputs.each(function () {
    if ($(this).val() === "") {
      result = false;
      $(this).addClass("error");
    } else {
      $(this).removeClass("error");
    }
  });
  return result;
}

function changeLoginForm() {
  var enable = checkAreAllInputsFilled($(this).find("input"));
  $(this).find('button[type="submit"]').prop("disabled", !enable);
}

function createLoginForm() {
  if (loginForm === null) {
    displayErrorMessage("Fail create login form!");
  } else {
    createModalWindow(loginForm);
    $(".login-form").change(changeLoginForm);
  }
}

function changeRegistrationForm() {
  var enable = checkAreAllInputsFilled($(this).find("input"));
  if (enable) {
    enable =
      $(this).find('input[name="password"]').val() ==
      $(this).find('input[name="repeatPassword"]').val();
    if (!enable) {
      $(this).find('input[name="repeatPassword"]').addClass("error");
    }
  }
  $(this).find('button[type="submit"]').prop("disabled", !enable);
}

function createRegistrationForm() {
  if (registrationForm === null) {
    displayErrorMessage("Fail create registration form!");
  } else {
    createModalWindow(registrationForm);
    $(".registration-form").change(changeRegistrationForm);
  }
}

function hideAccordionContent(content) {
  content.show();
  content.slideUp(500);
}

function showAccordionContent(content) {
  content.hide();
  content.slideDown(500);
}

function clickAccordionHeaderEvent(e) {
  var parent = $(this).closest(".accordion");
  parent.toggleClass("active");
  var content = parent.find(".accordion-content");
  if (parent.hasClass("active")) {
    showAccordionContent(content);
  } else {
    hideAccordionContent(content);
  }
}

function haveCurrentTabId(element, id) {
  return element.attr("tab-id") === id;
}

function clickTabEvent(e) {
  var parent = $(this).closest(".tab-control");
  var id = $(this).attr("tab-id");

  var tabs = parent.find(".tab");
  tabs
    .not(function () {
      return haveCurrentTabId($(this), id);
    })
    .removeClass("active");
  tabs
    .filter(function () {
      return haveCurrentTabId($(this), id);
    })
    .addClass("active");

  var contents = parent.find(".tab-content");
  contents
    .not(function () {
      return haveCurrentTabId($(this), id);
    })
    .removeClass("active");
  contents
    .filter(function () {
      return haveCurrentTabId($(this), id);
    })
    .addClass("active");
}

function clickTabAccorionEvent(e) {
  var thisAccordion = $(this).closest(".accordion");
  thisAccordion.toggleClass("active");
  var control = $(this).closest(".tab-control");

  var accordions = control.find(".accordion").not(thisAccordion);
  accordions.each(() => {
    $(this).removeClass("active");
    hideAccordionContent($(this).find(".accordion-content"));
  });

  if (thisAccordion.hasClass("active")) {
    showAccordionContent(thisAccordion.find(".accordion-content"));
  } else {
    hideAccordionContent(thisAccordion.find(".accordion-content"));
  }
}

function translateSlider(slider, index) {
  slider.css("transform", "translateX(" + -index * 100 + "%)");
}

function getBackIndex(index, count) {
  return index > 0 ? index - 1 : count - 1;
}

function getForthIndex(index, count) {
  return index < count - 1 ? index + 1 : 0;
}

function sliderButtonClick(button, changeIndexFunction) {
  var container = button.closest(".slider-container");
  var index = Number(container.attr("index"));
  var slider = container.find(".slider");
  var count = slider.find(".slide").length;
  index = changeIndexFunction(index, count);
  container.attr("index", index);
  translateSlider(slider, index);
}

function clickFavoritesButtonEvent(e) {
  var itemId = $(this).closest(".item").attr("item-id");
  var operationType = null;
  if ($(this).hasClass("active")) {
    $(this).text("В избранное");
    $(this).removeClass("active");
    operationType = 0;
  } else {
    $(this).text("Удалить из избранного");
    $(this).addClass("active");
    operationType = 1;
  }
  $.ajax({
    type: "get",
    url: `/ajax/editItemInFavorites.php?itemId=${itemId}&operationType=${operationType}`,
    processData: false,
    contentType: false,
    success: displaySuccessMessage,
    error: displayErrorMessage,
  });
}

function setItemCountInCart(itemId, count) {
  $.ajax({
    type: "get",
    url: `/ajax/editItemInCart.php?itemId=${itemId}&count=${count}`,
    processData: false,
    contentType: false,
    success: displaySuccessMessage,
    error: displayErrorMessage,
  });
}

function changeItemCountInCart(e) {
  var itemId = $(this).closest(".item").attr("item-id");
  var count = $(this).val();
  setItemCountInCart(itemId, count);
}

function clickCartButtonEvent(e) {
  var button = $(this);
  var itemId = $(this).closest(".item").attr("item-id");
  var parent = button.parent();
  if (parent.find(".count-block").length == 0) {
    if (countBlock === null) {
      displayErrorMessage("Fail create registration form!");
    } else {
      var block = $(countBlock);

      setItemCountInCart(itemId, 1);

      block.find(".add-button").click(clickAddButtonEvent);

      var removeButton = block.find(".remove-button");
      removeButton.click(clickRemoveButtonEvent);
      removeButton.click(setBlockZeroCountEvent);

      var count = parent.closest(".item").find(".count").text();
      var input = block.find("input[name=count]");
      input.attr("max", count);
      input.change(changeInputsValueEvent);
      input.change(changeItemCountInCart);
      input.change(setBlockZeroCountEvent);

      parent.prepend(block);
      button.remove();
    }
  }
}

function clickAddButtonEvent(e) {
  var input = $(this).closest(".count-block").find("input[name^=count]");
  var oldValue = Number(input.val());
  var newValue = oldValue + 1;
  var maxValue = Number(input.attr("max"));
  if (newValue <= maxValue) {
    input.val(newValue);
    var itemId = $(this).closest(".item").attr("item-id");
    setItemCountInCart(itemId, newValue);
  }
}

function clickRemoveButtonEvent(e) {
  var input = $(this).closest(".count-block").find("input[name^=count]");
  var oldValue = Number(input.val());
  var newValue = oldValue - 1;
  var minValue = Number(input.attr("min"));
  if (newValue >= minValue) {
    input.val(newValue);
    var itemId = $(this).closest(".item").attr("item-id");
    setItemCountInCart(itemId, newValue);
  }
}

function setCartItemZeroCountEvent() {
  var parent = $(this).closest(".item");
  if (parent.find("input[name^=count]").val() == 0) {
    parent.remove();
  }
}

function setBlockZeroCountEvent() {
  var parent = $(this).closest(".count-block");
  if (parent.find("input[name^=count]").val() == 0) {
    var button = $(
      '<button class="interactive item-header cart-button">В корзину</button>'
    );
    button.click(clickCartButtonEvent);
    parent.parent().prepend(button);
    parent.remove();
  }
}

function clickRemoveFromFavoritesEvent() {
  $(this).closest(".item").remove();
}

$(document).ready(function () {
  $("form").submit(submitFormEvent);
  $("button[method][action]").click(clickButtonEvent);
  $("input[name]").change(changeInputsValueEvent);
  $(".accordion-header").click(clickAccordionHeaderEvent);
  $(".tab-accordion-header").click(clickTabAccorionEvent);
  $(".tab").click(clickTabEvent);
  $(".slider-back-button").click(function (e) {
    sliderButtonClick($(this), getBackIndex);
  });
  $(".slider-forth-button").click(function (e) {
    sliderButtonClick($(this), getForthIndex);
  });

  $(".favorites-button").click(clickFavoritesButtonEvent);
  $(".cart-button").click(clickCartButtonEvent);
  $(".add-button").click(clickAddButtonEvent);
  $(".remove-button").click(clickRemoveButtonEvent);

  $(".count-block input[name^=count]").change(changeItemCountInCart);
  switch (window.location.pathname) {
    case "/account/cart.php":
      $("input:not([readonly])").change(calculateTotalCost);
      $("input:not([readonly])").change(setCartItemZeroCountEvent);
      $(".add-button").click(calculateTotalCost);
      $(".remove-button").click(calculateTotalCost);
      $(".remove-button").click(setCartItemZeroCountEvent);
      break;
    case "/shop/item.php":
      break;
    case "/account/favorites.php":
      $(".favorites-button").click(clickRemoveFromFavoritesEvent);
    default:
      $("input[name=count]").change(setBlockZeroCountEvent);
      $(".remove-button").click(setBlockZeroCountEvent);
  }
});
