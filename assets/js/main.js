var currentPage = 1;

function loadMore() {
  currentPage++;
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "load_more.php?page=" + currentPage, true);
  xhr.onload = function () {
    if (xhr.status == 200) {
      document.getElementById("posts-container").innerHTML += xhr.responseText;
    }
  };
  xhr.send();
}

//form validation
function validateForm(formId) {
  var form = document.getElementById(formId);
  var inputs = form.querySelectorAll("input[required], textarea[required]");

  for (var i = 0; i < inputs.length; i++) {
    if (inputs[i].value.trim() === "") {
      alert("Please fill in all required fields.");
      inputs[i].focus();
      return false;
    }
  }
  return true;
}
