$(document).ready(function () {
    $(".refund-order").on("click", function (e) {
        e.preventDefault(); // Prevent default behavior
        refundingOrder(this.dataset.id); // Call function to delete order
    });

    // To show modal
    function refundingOrder(order_id) {
        $.ajax({
            type: "GET", // Use GET request
            url: "../orders/refund-order.html", // URL to get product data
            dataType: "html", // Expect HTML response
            success: function (view) {
                $(".modal-container").empty().html(view); // Load the modal view
                $("#staticBackdropRefundOrder").modal("show"); // Show the modal
                $("#staticBackdropRefundOrder").attr("data-id", order_id);

                // Set the cart_id in the hidden input
                $("#order_id").val(order_id);

                // Event listener for the form submission
                $("#form-refund-order").on("submit", function (e) {
                    e.preventDefault(); // Prevent default form submission
                    refundOrder(order_id); // Call the function to refund the order
                });
            },
        });
    }

    // Function to refund order
    function refundOrder(order_id) {
        let description = $("#description").val(); // Get the description value

        $.ajax({
            type: "POST", // Use POST request
            url: "../orders/refund-order-item.php", // URL of the PHP script
            data: { order_id: order_id, description: description }, // Send order_id and description
            dataType: "json", // Expect a JSON response
            success: function (response) {
                if (response.status === "success") {
                    $("#staticBackdropRefundOrder").modal("hide"); // Close the modal
                    $("form")[0].reset(); // Reset the form
                    window.location.href = 'orders.php'; // Redirect to orders page
                } else {
                    alert(response.message); // Show failure message
                }
            },
        });
    }
});
