const cartCountNumber = document.getElementById("cartCount");

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

window.onload = () => {
  updateCartCount();
};
