// Change navbar transparency on scroll
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("transparent");
  } else {
    navbar.classList.remove("transparent");
  }
});
