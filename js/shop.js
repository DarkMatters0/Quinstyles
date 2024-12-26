$(document).ready(function () {
    // Attach event using delegation for dynamically loaded elements
    $(document).on("click", ".buy-uniform", function (e) {
      e.preventDefault(); // Prevent default behavior
      buyUniform(); // Call function to add product
    });

    $(document).on("click", ".buy-pe", function (e) {
        e.preventDefault(); // Prevent default behavior
        buyPe(); // Call function to add product
      });
  
    // Function to show the add product modal
    function buyUniform() {
      $.ajax({
        type: "GET", // Use GET request
        url: "../shop/form-add-cart-uniform.html", // URL for add product view
        dataType: "html", // Expect HTML response
        success: function (view) {
          $(".modal-container").html(view); // Inject modal HTML
          $("#placeOrderModal").modal("show"); // Initialize and show the modal

          $("#form-place-order-uniform").on("submit", function (e) {
            e.preventDefault(); // Prevent default form submission
            saveUniform(); // Call function to save product
          });
        },
        error: function () {
          alert("Failed to load the modal. Please try again."); // Error handling
        },
      });
    }

    // Function to save the uniform product
function saveUniform() {
    $.ajax({
      type: "POST", // Use POST request
      url: "../cart/add-to-cart.php", // URL for handling cart addition
      data: $("#form-place-order-uniform").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors from the server response
          if (response.customer_idErr) {
            $("input[name='customer_id']").addClass("is-invalid");
            $("input[name='customer_id']").next(".invalid-feedback").text(response.customer_idErr).show();
          } else {
            $("input[name='customer_id']").removeClass("is-invalid");
          }
  
          if (response.product_typeErr) {
            $("select[name='uniform-type']").addClass("is-invalid");
            $("select[name='uniform-type']").next(".invalid-feedback").text(response.product_typeErr).show();
          } else {
            $("select[name='uniform-type']").removeClass("is-invalid");
          }
  
          if (response.genderErr) {
            $("select[name='gender']").addClass("is-invalid");
            $("select[name='gender']").next(".invalid-feedback").text(response.genderErr).show();
          } else {
            $("select[name='gender']").removeClass("is-invalid");
          }
  
          if (response.sizeErr) {
            $("select[name='size']").addClass("is-invalid");
            $("select[name='size']").next(".invalid-feedback").text(response.sizeErr).show();
          } else {
            $("select[name='size']").removeClass("is-invalid");
          }
  
          if (response.quantityErr) {
            $("input[name='quantity']").addClass("is-invalid");
            $("input[name='quantity']").next(".invalid-feedback").text(response.quantityErr).show();
          } else {
            $("input[name='quantity']").removeClass("is-invalid");
          }
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#placeOrderModal").modal("hide");
          $("form")[0].reset(); // Reset the form
          window.location.href = "../cart/cart.php"; // Redirect to cart page
        }
      },
      error: function () {
        alert("An error occurred while saving the product. Please try again.");
      },
    });
  }
  

    function buyPe() {
        $.ajax({
          type: "GET", // Use GET request
          url: "../shop/form-add-cart-pe.html", // URL for add product view
          dataType: "html", // Expect HTML response
          success: function (view) {
            $(".modal-container").html(view); // Inject modal HTML
            $("#placeOrderModal").modal("show"); // Initialize and show the modal

            $("#form-place-order-pe").on("submit", function (e) {
                e.preventDefault(); // Prevent default form submission
                savePe(); // Call function to save product
              });
          },
          error: function () {
            alert("Failed to load the modal. Please try again."); // Error handling
          },
        });
      }

          // Function to save the uniform product
function savePe() {
    console.log("savePe function called");
    $.ajax({
      type: "POST", // Use POST request
      url: "../cart/add-to-cart.php", // URL for handling cart addition
      data: $("#form-place-order-pe").serialize(), // Serialize the form data for submission
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "error") {
          // Handle validation errors from the server response
          if (response.customer_idErr) {
            $("input[name='customer_id']").addClass("is-invalid");
            $("input[name='customer_id']").next(".invalid-feedback").text(response.customer_idErr).show();
          } else {
            $("input[name='customer_id']").removeClass("is-invalid");
          }
  
          if (response.product_typeErr) {
            $("select[name='uniform-type']").addClass("is-invalid");
            $("select[name='uniform-type']").next(".invalid-feedback").text(response.product_typeErr).show();
          } else {
            $("select[name='uniform-type']").removeClass("is-invalid");
          }
  
          if (response.genderErr) {
            $("select[name='gender']").addClass("is-invalid");
            $("select[name='gender']").next(".invalid-feedback").text(response.genderErr).show();
          } else {
            $("select[name='gender']").removeClass("is-invalid");
          }
  
          if (response.sizeErr) {
            $("select[name='size']").addClass("is-invalid");
            $("select[name='size']").next(".invalid-feedback").text(response.sizeErr).show();
          } else {
            $("select[name='size']").removeClass("is-invalid");
          }
  
          if (response.quantityErr) {
            $("input[name='quantity']").addClass("is-invalid");
            $("input[name='quantity']").next(".invalid-feedback").text(response.quantityErr).show();
          } else {
            $("input[name='quantity']").removeClass("is-invalid");
          }
        } else if (response.status === "success") {
          // On success, hide modal and reset form
          $("#placeOrderModal").modal("hide");
          $("form")[0].reset(); // Reset the form
          window.location.href = "../cart/cart.php"; // Redirect to cart page
        }
      },
      error: function () {
        alert("An error occurred while saving the product. Please try again.");
      },
    });
  }
  });
  