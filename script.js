const modal = document.getElementById("product-modal");
const modalTitle = document.getElementById("modal-title");
const modalDescription = document.getElementById("modal-description");
const closeModal = document.querySelector(".modal .close");

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
