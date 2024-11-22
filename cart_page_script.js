const cartCountNumber = document.getElementById("cartCount");
const continueShopBtn = document.getElementById("continueShopBtn");

// Cookie storage for current cart
function setCookie(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
  const expires = "expires=" + date.toUTCString();
  document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

const getCookie = (name) => {
  const nameEQ = `${name}=`;
  const cookiesArray = document.cookie.split(";");
  for (let i = 0; i < cookiesArray.length; i++) {
    let cookie = cookiesArray[i].trim();
    if (cookie.indexOf(nameEQ) === 0) {
      return cookie.substring(nameEQ.length, cookie.length);
    }
  }
  return null;
};

const updateCartCount = () => {
  let cart = getCookie("currentCart");

  // effectively return 0 if the cart cookie does not exist
  // otherwise perform reduce function to get sum of items in cart cookie
  const totalItems = cart
    ? Object.values(JSON.parse(cart)).reduce((accum, currValue) => {
        return accum + currValue;
      }, 0)
    : 0;

  cartCountNumber.textContent = totalItems;
};

document.querySelectorAll(".remove-item-button").forEach((button) => {
  button.addEventListener("click", () => {
    let cart = getCookie("currentCart");
    cart = cart ? JSON.parse(cart) : {};

    const itemRow = button.closest(".item-row");
    const itemText = itemRow.children[0].innerText;

    delete cart[itemText];

    // immediately delete cookie via setting expiration date to past date if there are no items in cart
    // otherwise update cart and add 7 days to expiration date
    Object.keys(cart).length === 0
      ? setCookie("currentCart", "", -1)
      : setCookie("currentCart", JSON.stringify(cart), 7);
    window.location.reload();
  });
});

continueShopBtn.addEventListener("click", () => {
  window.location.href = "products_page.php";
});

window.onload = () => {
  updateCartCount();
};
