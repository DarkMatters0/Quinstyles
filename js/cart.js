$(document).ready(function () {
    // JavaScript to handle checkbox state change and AJAX request
    $(".purchase-cart").on("click", function (e) {
        e.preventDefault(); // Prevent default behavior
        modalPurchaseCart(this.dataset.id); // Call function to edit product
    });

    $(".order-custom-cart").on("click", function (e) {
        e.preventDefault(); // Prevent default behavior
        modalPurchaseCustomCart(this.dataset.id); // Call function to edit product
    });

    $(".delete-cart").on("click", function (e) {
        e.preventDefault(); // Prevent default behavior
        deletingCart(this.dataset.id); // Call function to edit product
    });

    // Event listener for viewing a custom-request
    $(".see-custom-details").on("click", function (e) {
        e.preventDefault(); // Prevent default behavior
        seeDetailsModal(this.dataset.id); // Call function to edit product
      });

    function modalPurchaseCart(cart_item_id) {
        $.ajax({
            type: "GET",
            url: "../cart/cart-purchase.html",
            dataType: "html",
            success: function (view) {
                $(".modal-container").empty().html(view);
                $("#staticBackdropPurchase").modal("show");
                $("#staticBackdropPurchase").attr("data-id", cart_item_id);

                // Call the details fetching function
                $("#form-confirm-purchase").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission
                    PurchaseCart(cart_item_id); // Call function to update product
                });
            },
        });
    }

    function PurchaseCart(cart_item_id) {
        $.ajax({
            type: "POST",
            url: `../cart/cart-purchase.php?id=${cart_item_id}`, // Pass cart_id in the URL only
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    $("#staticBackdropPurchase").modal("hide");
                    $("form")[0].reset();
                    alert("Item purchased successfully!"); // Success alert
                    window.location.href = 'cart.php'; // Refresh the cart page
                } else {
                    alert(response.message); // Alert with error message
                }
            },
            error: function () {
                alert("Failed to buy the item. Please try again."); // General error alert
            }
        });
    }

    function modalPurchaseCustomCart(cart_item_id) {
        $.ajax({
            type: "GET",
            url: "../cart/cart-custom-purchase.html",
            dataType: "html",
            success: function (view) {
                $(".modal-container").empty().html(view);
                $("#staticBackdropPurchaseCart").modal("show");
                $("#staticBackdropPurchaseCart").attr("data-id", cart_item_id);

                // Call the details fetching function
                $("#form-confirm-purchase-custom").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission
                    customPurchaseCart(cart_item_id); // Call function to update product
                });
            },
        });
    }

    function customPurchaseCart(cart_item_id) {
        $.ajax({
            type: "POST",
            url: `../cart/cart-custom-purchase.php?id=${cart_item_id}`, // Pass cart_id in the URL only
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    $("#staticBackdropPurchaseCart").modal("hide");
                    $("form")[0].reset();
                    alert("Item purchased successfully!"); // Success alert
                    window.location.href = 'cart.php'; // Refresh the cart page
                } else {
                    alert(response.message); // Alert with error message
                }
            },
            error: function () {
                alert("Failed to buy the item. Please try again."); // General error alert
            }
        });
    }

    // Function to show the add product modal
function seeDetailsModal(customUniformId) {
    $.ajax({
      type: "GET",
      url: "../cart/view-custom-details.html",
      dataType: "html",
      success: function (view) {
        $(".modal-container").empty().html(view);
        $("#staticBackdropDetails").modal("show");
        $("#staticBackdropDetails").attr("data-id", customUniformId);
  
        // Call the details fetching function
        seeDetails(customUniformId);
      },
    });
  }
  
  
    // Function to show the size details
    function seeDetails(customUniformId) {
      $.ajax({
        type: "GET",
        url: "../cart/fetch-custom-details.php", // PHP script to fetch details
        data: { custom_uniform_id: customUniformId }, // Pass the ID
        dataType: "json", // Expect JSON response
        success: function (data) {
          if (data.error) {
            alert(data.error); // Handle errors
            return;
          }
    
          // Populate modal fields with fetched data
          $("#staticBackdropDetails [name='name']").val(data.name);
          $("#staticBackdropDetails [name='gender']").val(data.gender);
          $("#staticBackdropDetails [name='chest_measurement']").val(data.chest_measurement);
          $("#staticBackdropDetails [name='waist_measurement']").val(data.waist_measurement);
          $("#staticBackdropDetails [name='hip_measurement']").val(data.hip_measurement);
          $("#staticBackdropDetails [name='shoulder_width']").val(data.shoulder_width);
          $("#staticBackdropDetails [name='sleeve_length']").val(data.sleeve_length);
          $("#staticBackdropDetails [name='pant_length']").val(data.pant_length);
          $("#staticBackdropDetails [name='custom_features']").val(data.custom_features);
    
          // Show the modal
          $("#staticBackdropDetails").modal("show");
        },
        error: function () {
          alert("Failed to fetch details. Please try again.");
        },
      });
    }

    // To show modal
    function deletingCart(cart_item_id) {
        $.ajax({
            type: "GET", // Use GET request
            url: "../cart/delete-cart.html", // URL to get product data
            dataType: "html", // Expect HTML response
            success: function (view) {
                $(".modal-container").empty().html(view); // Load the modal view
                $("#staticBackdropDelete").modal("show"); // Show the modal
                $("#staticBackdropDelete").attr("data-id", cart_item_id);

                // Set the cart_id in the hidden input
                $("#cart-id").val(cart_item_id);

                // Event listener for the form submission
                $("#form-delete-cart").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission
                    deleteCart(cart_item_id); // Call the function to delete the cart item
                });
            },
        });
    }

    // Function to delete account
    function deleteCart(cart_item_id) {
        $.ajax({
            type: "POST", // Use POST request
            url: "../cart/delete-cart-item.php", // URL of the PHP script
            data: { cart_item_id: cart_item_id }, // Send cart_id to the PHP script
            dataType: "json", // Expect a JSON response
            success: function (response) {
                if (response.status === "success") {
                    $("#staticBackdropDelete").modal("hide"); // Close the modal
                    $("form")[0].reset(); // Reset the form
                    // Optionally, refresh the page or update the UI
                    window.location.href = 'cart.php'; // Redirect to cart page
                } else {
                    alert(response.message); // Show failure message
                }
            },
            error: function () {
                alert("An error occurred while deleting the item.");
            }
        });
    }

            // To show modal
            function deletingOrder(order_id) {
                $.ajax({
                    type: "GET", // Use GET request
                    url: "../orders/delete-order.html", // URL to get product data
                    dataType: "html", // Expect HTML response
                    success: function (view) {
                        $(".modal-container").empty().html(view); // Load the modal view
                        $("#staticBackdropDeleteOrder").modal("show"); // Show the modal
                        $("#staticBackdropDeleteOrder").attr("data-id", order_id);
        
                        // Set the cart_id in the hidden input
                        $("#cart-id").val(order_id);
        
                        // Event listener for the form submission
                        $("#form-delete-order").on("submit", function (e) {
                            e.preventDefault(); // Prevent default form submission
                            deleteOrder(order_id); // Call the function to delete the cart item
                        });
                    },
                });
            }
        
            // Function to delete account
            function deleteOrder(order_id) {
                $.ajax({
                    type: "POST", // Use POST request
                    url: "../orders/delete-order-item.php", // URL of the PHP script
                    data: { order_id: order_id }, // Send cart_id to the PHP script
                    dataType: "json", // Expect a JSON response
                    success: function (response) {
                        if (response.status === "success") {
                            $("#staticBackdropDeleteOrder").modal("hide"); // Close the modal
                            $("form")[0].reset(); // Reset the form
                            // Optionally, refresh the page or update the UI
                            window.location.href = 'orders.php'; // Redirect to cart page
                        } else {
                            alert(response.message); // Show failure message
                        }
                    },
                    error: function () {
                        alert("An error occurred while deleting the item.");
                    }
                });
            }
});
