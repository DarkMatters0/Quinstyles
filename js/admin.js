$(document).ready(function () {
  // Event listener for navigation links
  $(".nav-link").on("click", function (e) {
    e.preventDefault(); // Prevent default anchor click behavior
    $(".nav-link").removeClass("link-active"); // Remove active class from all links
    $(this).addClass("link-active"); // Add active class to the clicked link

    let url = $(this).attr("href"); // Get the URL from the href attribute
    window.history.pushState({ path: url }, "", url); // Update the browser's URL without reloading
  });

  // Event listener for the dashboard link
  $("#dashboard-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewAnalytics(); // Call the function to load analytics
  });

    // Event listener for the products link
    $("#products-link").on("click", function (e) {
      e.preventDefault(); // Prevent default behavior
      viewProducts(); // Call the function to load products
    });
    
        // Event listener for the products link
    $("#orders-link").on("click", function (e) {
      e.preventDefault(); // Prevent default behavior
      viewOrders(); // Call the function to load products
    });

  // Event listener for the custom requests link
  $("#custom-request-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewCustomRequests(); // Call the function to load custom requests
  });

  $("#refund-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewRefund(); // Call the function to load custom requests
  });

  $("#account-link").on("click", function (e) {
    e.preventDefault(); // Prevent default behavior
    viewAccounts(); // Call the function to load custom requests
  });

  // Determine which page to load based on the current URL
  let url = window.location.href;
  if (url.endsWith("dashboard")) {
    $("#dashboard-link").trigger("click"); // Trigger the dashboard click event
  } else if (url.endsWith("custom-request")) {
    $("#custom-request-link").trigger("click"); // Trigger the custom requests click event
  } 

  else if (url.endsWith("products")) {
    $("#products-link").trigger("click"); // Trigger the products click event
  } 
  
  else if (url.endsWith("orders")) {
    $("#orders-link").trigger("click"); // Trigger the custom requests click event
  } 
  
  else if (url.endsWith("order-bin")) {
    $("#order-bin-link").trigger("click"); // Trigger the custom requests click event
  } 
  
  else if (url.endsWith("account")) {
    $("#account-link").trigger("click"); // Trigger the custom requests click event
  }
  
  else {
    $("#dashboard-link").trigger("click"); // Default to dashboard if no specific page
  }


  
 // Function to load analytics view
function viewAnalytics() {
  $.ajax({
    type: "GET", // Use GET request
    url: "view_analtics.php", // URL for the analytics view
    dataType: "html", // Expect HTML response
    success: function (response) {
      $(".content-page").html(response); // Load the response into the content area
      loadChart(); // Call function to load the chart with zero sales
    },
    error: function () {
      $(".content-page").html("<p>Error loading analytics data.</p>");
    },
  });
}


  // Function to load a sales chart using Chart.js
function loadChart() {
  const ctx = document.getElementById("salesChart").getContext("2d"); // Get context of the chart element
  const salesChart = new Chart(ctx, {
    type: "bar", // Set chart type to bar
    data: {
      labels: [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec",
      ], // Monthly labels
      datasets: [
        {
          label: "Sales", // Label for the dataset
          data: [
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
          ], // Reset sales data to zero
          backgroundColor: "#EE4C51", // Bar color
          borderColor: "#EE4C51", // Border color
          borderWidth: 1, // Border width
        },
      ],
    },
    options: {
      responsive: true, // Make chart responsive
      scales: {
        y: {
          beginAtZero: true, // Start y-axis at 0
          max: 10000, // Maximum value for y-axis
          ticks: {
            stepSize: 2000, // Set step size for y-axis ticks
          },
        },
      },
    },
  });
}



    // Function to load products view
  function viewProducts() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin-products/view-products.php", // URL for products view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area


        $("#custom-search").on("keyup", function () {
          table.search(this.value).draw(); // Search products based on input
        });

        $("#uni-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(1).search(this.value).draw(); // Filter products by selected category
          }
        });

        $("#gender-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(3).search(this.value).draw(); // Filter products by selected category
          }
        });

        $("#size-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(4).search(this.value).draw(); // Filter products by selected category
          }
        });


      // Event listener for editing a product
      $(".edit-product").on("click", function (e) {
        e.preventDefault(); // Prevent default behavior
        editPrice(this.dataset.id); // Call function to edit product
      });

        $(document).on("click", ".stock-action", function (e) {
          e.preventDefault();
          stockAction(this.dataset.id); // Call stock action function
        });

      },
    });
  }



// Function to load stock action modal
function stockAction(productId) {
  $.ajax({
    type: "GET",
    url: "../admin-products/stocks-action.html",
    dataType: "html",
    success: function (view) {
      $(".modal-container").empty().html(view); // Inject the modal content
      $("#stockModal").modal("show"); // Show the modal
      $("#product_id").val(productId); // Set the product ID to the hidden input

      // Handle form submission
      $("#form-stock-action").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission
        updateStockAction(productId); // Save the stock action
      });

    },
    error: function () {
      alert("Error loading stock action modal.");
    }
  });
}

// Function to update stock action (In/Out)
function updateStockAction(productId) {
  let quantity = $("#quantity").val();
  let reason = $("#reason").val();
  let stockAction = $("input[name='stock_action']:checked").val(); // Get selected stock action

  // Form validation
  if (!quantity || quantity <= 0) {
    alert("Quantity is required and should be greater than 0.");
    return;
  }

  if ((stockAction === 'in' || stockAction === 'out') && !reason) {
    alert("Reason for stock In/Out is required.");
    return;
  }

  // AJAX call to save stock action
  $.ajax({
    type: "POST",
    url: `../admin-products/update-stock.php?id=${productId}`, // Ensure the correct URL
    data: {
      quantity: quantity,
      stock_action: stockAction,
      reason: reason,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "error") {
        alert(response.error || "Error in stock action.");
      } else {
        $("#stockModal").modal("hide"); // Close the modal
        viewProducts(); // Reload the products list
      }
    },
    error: function () {
      alert("An error occurred while saving the stock action.");
    }
  });
}


    // Function to show the add product modal
  function editPrice(productId) {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin-products/edit-product.html", // URL to get product data
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdropedit").modal("show"); // Show the modal
        $("#staticBackdropedit").attr("data-id", productId);

        // Event listener for the edit product form submission
        $("#form-edit-product").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          updatePrice(productId); // Call function to update product
        });
      },
    });
  }

  // Function to update the price of a product
// Function to update the price of a product
function updatePrice(productId) {
  let price = $("#price").val(); // Get price input value

  // Client-side validation for price
  if (!price || price <= 0 || isNaN(price)) {
    $("#price").addClass("is-invalid"); // Highlight invalid field
    $("#price")
      .next(".invalid-feedback")
      .text("Price must be a positive number.")
      .show();
    return; // Prevent AJAX call if validation fails
  }

  // AJAX call
  $.ajax({
    type: "POST",
    url: `../admin-products/update-price.php?id=${productId}`,
    data: $("#form-edit-product").serialize(), // Serialize the form data
    dataType: "json",
    success: function (response) {
      if (response.status === "error") {
        if (response.priceErr) {
          $("#price").addClass("is-invalid");
          $("#price").next(".invalid-feedback").text(response.priceErr).show();
        } else {
          alert(response.message || "An error occurred.");
        }
      } else if (response.status === "success") {
        $("#staticBackdropedit").modal("hide");
        $("#form-edit-product")[0].reset();
        viewProducts(); // Reload products list
      }
    },
    error: function () {
      alert("An error occurred while updating the price.");
    },
  });
}

function viewOrders() {
  $.ajax({
    type: "GET", // Use GET request
    url: "../admin-orders/view-orders.php", // URL for the custom requests view
    dataType: "html", // Expect HTML response
    success: function (response) {
      $(".content-page").html(response); // Load the response into the content area

      $("#status-filter").on("change", function () {
        if (this.value !== "choose") {
          table.column(6).search(this.value).draw(); // Filter products by selected category
        }
      });



      $(document).on("click", ".edit-order", function (e) {
        e.preventDefault();
        seeStatusModal(this.dataset.id); // Call stock action function
      });

    
      
    },
    

    error: function () {
      $(".content-page").html("<p>Error loading custom requests.</p>");
    },
  });
}

// Function to show the add product modal
function seeStatusModal(order_id) {
  $.ajax({
    type: "GET",
    url: "../admin-orders/edit-status.html",
    dataType: "html",
    success: function (view) {
      fetchStatus(order_id);
      $(".modal-container").empty().html(view);
      $("#staticBackdropeditstatus").modal("show");
      $("#staticBackdropeditstatus").attr("data-id", order_id);
      
       // Move this here
    
      // Call the details fetching function
      $("#form-edit-status").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission
        updateStatus(order_id); // Call function to update product
      });
    },
  });
}


function fetchStatus(order_id) {
$.ajax({
  url: `../admin-orders/fetch-status.php?id=${order_id}`, // URL for fetching categories
  type: "POST", // Use GET request
  dataType: "json", // Expect JSON response
  success: function (order) {
    $("#status").val(order.status).trigger("change"); // Set the selected category
  },
});
}

function updateStatus(order_id) {
  $.ajax({
    type: "POST",
    url: `../admin-orders/update-status.php?id=${order_id}`,
    data: $("#form-edit-status").serialize(),
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        $("#staticBackdropeditstatus").modal("hide");
        $("#form-edit-status")[0].reset(); // Reset the specific form
        viewOrders(); // Reload orders to show updates
      } else {
        alert(response.message || "Failed to update status.");
      }
    },
    error: function (xhr) {
      alert("An error occurred while updating the status.");
      console.error(xhr.responseText);
    },
  });
}


 
  // Function to load products view
  function viewCustomRequests() {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin-custom-request/view-custom-request.php", // URL for the custom requests view
      dataType: "html", // Expect HTML response
      success: function (response) {
        $(".content-page").html(response); // Load the response into the content area


        // Event listener for viewing a custom-request
        $(".see-custom-details").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          seeDetailsModal(this.dataset.id); // Call function to edit product
        });

        // Event listener for adding price a custom-request
        $(".add-price").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          addPrice(this.dataset.id); // Call function to edit product
        });
      },
      

      error: function () {
        $(".content-page").html("<p>Error loading custom requests.</p>");
      },
    });
  }

// Function to show the add product modal
function seeDetailsModal(customUniformId) {
  $.ajax({
    type: "GET",
    url: "../admin-custom-request/view-custom-details.html",
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
      url: "../admin-custom-request/fetch-custom-details.php", // PHP script to fetch details
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

      // Function to show the add price modal
      function addPrice(customUniformId) {
        $.ajax({
            type: "GET",
            url: "../admin-custom-request/add-price.html",
            dataType: "html",
            success: function (view) {
                $(".modal-container").empty().html(view); // Load the modal view
                $("#staticBackdropPrice").modal("show"); // Show the modal
                $("#staticBackdropPrice").attr("data-id", customUniformId);
    
                // Populate hidden input with customUniformId
                $("#customUniformId").val(customUniformId);
    
                // Event listener for the form submission
                $("#form-add-price").off("submit").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission
                    savePrice(customUniformId); // Save the price
                });
            },
            error: function () {
                alert("Failed to load the modal. Please try again.");
            }
        });
    }
    
  
    function savePrice(customUniformId) {
      $.ajax({
          type: "POST",
          url: `../admin-custom-request/save-price.php?id=${customUniformId}`, // Correct URL to save price
          data: $("#form-add-price").serialize(), // Serialize form data
          dataType: "json",
          success: function (response) {
              if (response.status === "success") {
                  // On success, hide modal and reset form
                  $("#staticBackdropPrice").modal("hide");
                  $("form")[0].reset(); // Reset the form
                  // Optionally, reload products to show new entry
                  viewCustomRequests();
              } else {
                  // Handle errors (e.g., validation errors)
                  if (response.priceErr) {
                      alert(response.priceErr); // Show error for price field
                  } else {
                      alert(response.message || "An error occurred.");
                  }
              }
          },
          error: function () {
              alert("Failed to save the price. Please try again.");
          }
      });
  }
  
    
  

  // Function to load custom requests view
  function viewRefund() {
    $.ajax({
      type: "GET",
      url: "../admin-refund/view-refund.php",
      dataType: "html",
      success: function (response) {
        $(".content-page").html(response);

       
  
        // Restore the search values after updating the DOM
  
        // Reattach event listeners
        $("#custom-search").on("keyup", function () {
          table.search(this.value).draw(); // Search products based on input
        });

        $("#uni-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(3).search(this.value).draw(); // Filter products by selected category
          }
        });

        $(document).ready(function () {
          $("#refresh-cart-bin").on("click", function () {
            viewOrderBin();
          });

        // Event listener for editing a product
        $(".restore-bin").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          restoreOrderModal(this.dataset.id); // Call function to edit product
        });

        // Event listener for editing a product
        $(".delete-bin").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          DeleteOrderModal(this.dataset.id); // Call function to edit product
        });

      });
  
      },
      error: function () {
        $(".content-page").html("<p>Error loading cart bin.</p>");
      },
    });
  }

  // Function to show the add product modal
  function restoreOrderModal(order_bin_id) {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin-order-bin/restore-order.html", // URL to get product data
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdropRestore").modal("show"); // Show the modal
        $("#staticBackdropRestore").attr("data-id", order_bin_id);

        // Event listener for the edit product form submission
        $("#form-restore-order-bin").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          restoreOrder(order_bin_id); // Call function to update product
        });
      },
    });
  }

  function restoreOrder(order_bin_id) {
    $.ajax({
        type: "POST",  // Use POST method
        url: "../admin-order-bin/restore-order-bin.php",  // Correct path to the PHP file
        data: { order_bin_id: order_bin_id },  // Correct path to the PHP file
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                $("#staticBackdropRestore").modal("hide");
                $("form")[0].reset();
                viewOrderBin(); // Reload cart bin after restore
            } else {
                console.error(response.message); // Log error message
            }
        },
        error: function () {
            console.error("Error restoring cart bin."); // Log any errors
        }
    });
}

  // Function to show the add product modal
  function DeleteOrderModal(order_bin_id) {
    $.ajax({
      type: "GET", // Use GET request
      url: "../admin-order-bin/delete-order-bin.html", // URL to get product data
      dataType: "html", // Expect HTML response
      success: function (view) {
        $(".modal-container").empty().html(view); // Load the modal view
        $("#staticBackdropDeleteOrderBin").modal("show"); // Show the modal
        $("#staticBackdropDeleteOrderBin").attr("data-id", order_bin_id);

        // Event listener for the edit product form submission
        $("#form-delete-order-bin").on("submit", function (e) {
          e.preventDefault(); // Prevent default form submission
          deleteOrder(order_bin_id); // Call function to update product
        });
      },
    });
  }

  function deleteOrder(order_bin_id) {
    $.ajax({
        type: "POST",  // Use POST method
        url: "../admin-order-bin/delete-order-bin.php",  // Correct path to the PHP file
        data: { order_bin_id: order_bin_id },  // Send cart_bin_id in POST data
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                $("#staticBackdropDeleteCartBin").modal("hide");
                $("form")[0].reset();
                viewOrderBin(); // Reload cart bin after restore
            } else {
                console.error(response.message); // Log error message
            }
        },
        error: function () {
            console.error("Error deleting cart bin."); // Log any errors
        }
    });
}



  // Function to load accounts view
  function viewAccounts() {
    const searchKeyword = $("#search").val(); // Capture search input
    const roleFilter = $("#role-filter").val(); // Capture role filter

    $.ajax({
      type: "GET",
      url: "../admin-account/view-account.php",
      data: { search: searchKeyword, role: roleFilter },
      dataType: "html",
      success: function (response) {
        $(".content-page").html(response);

        $("#custom-search").on("keyup", function () {
          table.search(this.value).draw(); // Search products based on input
        });

        $("#role-filter").on("change", function () {
          if (this.value !== "choose") {
            table.column(4).search(this.value).draw(); // Filter products by selected category
          }
        });

        // Event listener for editing a account
        $(".edit-account").on("click", function (e) {
          e.preventDefault(); // Prevent default behavior
          editAccount(this.dataset.id); // Call function to edit product
        });
        
          // Event listener for deleting a account
          $(".delete-account").on("click", function (e) {
            e.preventDefault(); // Prevent default behavior
            deletingAccount(this.dataset.id); // Call function to edit product
          });

      },
      error: function () {
        $(".content-page").html("<p>Error loading accounts.</p>");
      },
    });
  }

    // Function to show the add product modal
    function editAccount(accountId) {
      $.ajax({
        type: "GET", // Use GET request
        url: "../admin-account/edit-account.html", // URL to get product data
        dataType: "html", // Expect HTML response
        success: function (view) {
          fetchRecord(accountId);
          $(".modal-container").empty().html(view); // Load the modal view
          $("#staticBackdropedit").modal("show"); // Show the modal
          $("#staticBackdropedit").attr("data-id", accountId);
  
          // Event listener for the edit product form submission
          $("#form-edit-account").on("submit", function (e) {
            e.preventDefault(); // Prevent default form submission
            updateAccount(accountId); // Call function to update product
          });
        },
      });
    }

    function updateAccount(accountId) {
      $.ajax({
          type: "POST",
          url: `../admin-account/update-account.php?id=${accountId}`, // Correct URL
          data: $("form").serialize(),
          dataType: "json",
          success: function (response) {
              if (response.status === "success") {
                  $("#staticBackdropedit").modal("hide");
                  $("form")[0].reset();
                  viewAccounts(); // Reload accounts after update
              }
          },
      });
  }

  function fetchRecord(accountId) {
    $.ajax({
      url: `../admin-account/fetch-account.php?id=${accountId}`, // URL for fetching categories
      type: "POST", // Use GET request
      dataType: "json", // Expect JSON response
      success: function (account) {
        $("#role").val(account.role).trigger("change"); // Set the selected category
      },
    });
  }

  function deletingAccount(accountId) {
    $.ajax({
        type: "GET", // Use GET request
        url: "../admin-account/delete-account.html", // URL to get product data
        dataType: "html", // Expect HTML response
        success: function (view) {
          fetchRecord(accountId);
          $(".modal-container").empty().html(view); // Load the modal view
          $("#staticBackdropDelete").modal("show"); // Show the modal
          $("#staticBackdropDelete").attr("data-id", accountId);
  
          // Event listener for the edit product form submission
          $("#form-delete-account").on("submit", function (e) {
            e.preventDefault(); // Prevent default form submission
            deleteAccount(accountId); // Call function to update product
          });
        },
      });
  }

// Function to delete account
function deleteAccount(accountId) {
    $.ajax({
        type: "POST",
        url: "../admin-account/delete-account.php", // Point to the delete script
        data: { id: accountId },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
              $("#staticBackdropedit").modal("hide");
              $("form")[0].reset();
              viewAccounts(); // Reload accounts after update
          }
      },
        error: function () {
            alert("An error occurred while deleting the account.");
        }
    });
}
  

  
});
