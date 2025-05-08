async function addProductToCart(id) {
    let cartOrderId = sessionStorage.getItem("cartOrderId");

    if (!cartOrderId) {
        const response = await sendAjaxRequest("./functions/shop-order.php", "POST", { 
            productId: id,
            action: 'addItem'
         });
        cartOrderId = response.cartOrderId;
        
        sessionStorage.setItem("cartOrderId", cartOrderId);
    }

    const addResponse = await sendAjaxRequest(baseDir+"/functions/shop-order.php", "POST", {
        cartOrderId,
        productId: id,
        action: 'addItem'
    });

    if (addResponse.success) {
        console.log("Product added!");
        updateItemsInCartCount (addResponse.itemCount);
    }
}

function updateItemsInCartCount (itemcount){
    const headerItemCount = document.getElementById('shop-cart-item-count')
    headerItemCount.innerText = itemcount
}

async function removeProductFromCart(id) {
    let cartOrderId = sessionStorage.getItem("cartOrderId");

    const addResponse = await sendAjaxRequest(baseDir+"/functions/shop-order.php", "POST", {
        cartOrderId,
        productId: id,
        action: 'removeItem'
    });

    if (addResponse.success) {
        console.log("Product removed!");
        updateItemsInCartCount (addResponse.itemCount);
    }
}