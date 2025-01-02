document.addEventListener('DOMContentLoaded', () => {
    fetch('/get-products.php')
        .then(response => response.json())
        .then(data => {
            const productContainer = document.getElementById('product-list');
            data.forEach(product => {
                const item = document.createElement('div');
                item.textContent = `${product.name} - $${product.price}`;
                productContainer.appendChild(item);
            });
        });
});
