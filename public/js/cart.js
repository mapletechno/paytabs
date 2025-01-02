document.addEventListener("DOMContentLoaded", () => {
    console.log("Cart.js loaded"); // Debugging: Check if this runs

    // Find all 'Add to Cart' buttons
    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    if (addToCartButtons.length === 0) {
        console.error("No Add to Cart buttons found.");
        return;
    }

    addToCartButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            console.log("Add to Cart button clicked"); // Debugging

            const productId = button.dataset.productId;
            const csrf = button.dataset.csrf;
            const quantityInput = document.querySelector(`#quantity-${productId}`);
            const quantity = parseInt(quantityInput.value, 10);

           // const quantity = button.dataset.quantity;
            console.log(csrf + " csrf"); // Debugging

            if (!productId) {
                console.error("No product ID found on button.");
                return;
            }

            // Send AJAX request
            fetch("add-to-cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity,csrf: csrf}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Product added successfully:", data);
                    alert("Product added to cart!");
                    document.getElementById('proceed-to-cart').style.display = 'block';
                } else {
                    console.error("Error adding product:", data.message);
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("AJAX error:", error);
                alert("An error occurred. Please try again.");
            });
        });
    });
});
