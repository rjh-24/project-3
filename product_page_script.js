const modal = document.getElementById("product-modal");
const modalTitle = document.getElementById("modal-title");
const modalDescription = document.getElementById("modal-description");
const closeModal = document.querySelector(".modal .close");
const cartCountNumber = document.getElementById("cartCount");

const toggleModal = (isOpen, name = "", description = "") => {
  if (isOpen) {
    modalTitle.textContent = name;
    modalDescription.textContent = description;

    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";

    return;
  }
  modal.style.display = "none";
  modal.style.justifyContent = "";
  modal.style.alignItems = "";
};

document.querySelectorAll(".more").forEach((button) => {
  button.addEventListener("click", () => {
    const name = button.getAttribute("data-name");
    const description = button.getAttribute("data-description");

    toggleModal(true, name, description);
  });
});

closeModal.addEventListener("click", () => {
  toggleModal(false);
});

window.addEventListener("click", (event) => {
  if (event.target === modal) {
    toggleModal(false);
  }
});

window.addEventListener("keydown", (event) => {
  if (event.key === "Escape" && modal.style.display === "flex") {
    toggleModal(false);
  }
});

document.querySelectorAll(".add-to-cart").forEach((button) => {
  button.addEventListener("click", () => {
    const productItem = button.closest(".product-item");
    const productDetails = productItem.children[1];
    const productName = productDetails.firstChild.textContent;
    const productQuantityDetails = productDetails.lastChild;
    const productQuantity = productQuantityDetails.lastChild.value;

    updateCart(productName, productQuantity);
    window.location.href = "cart_page.php";
  });
});

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

const updateCart = (updatedItem, updatedQuantity) => {
  let cart = getCookie("currentCart");
  cart = cart ? JSON.parse(cart) : {};

  cart[updatedItem] = (cart[updatedItem] || 0) + parseInt(updatedQuantity);
  setCookie("currentCart", JSON.stringify(cart), 30);
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

  console.log("testing");
};

window.onload = () => {
  updateCartCount();
};
