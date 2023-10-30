function toggleMenu() {
  var menu = document.getElementById("menu");
  var menuToggle = document.getElementById("menuToggle");

  menu.classList.toggle("active");
  menuToggle.classList.toggle("active");

  if (window.innerWidth > 512) {
    menu.classList.remove("active");
    menuToggle.classList.remove("active");
  }
}

// Função para verificar se uma seção está visível no viewport
function isElementInViewport(element) {
  const rect = element.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
  );
}

// Função para ativar animações de deslize quando as seções estão no viewport
function animateOnScroll() {
  const sections = document.querySelectorAll(".container-sections section");

  sections.forEach((section) => {
    const isVisible = isElementInViewport(section);

    if (isVisible) {
      section.classList.add("active");
    } else {
      section.classList.remove("active");
    }
  });
}

// Adicione um ouvinte de eventos de rolagem para ativar as animações
window.addEventListener("scroll", animateOnScroll);
window.addEventListener("load", animateOnScroll); // Ative as animações quando a página é carregada

// Função para rolar suavemente para uma seção
function smoothScroll(target) {
  const element = document.querySelector(target);
  if (element) {
    window.scrollTo({
      behavior: "smooth",
      top: element.offsetTop,
    });
  }
}

// Ouvintes de eventos para links do menu
document.querySelectorAll(".menu button").forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    smoothScroll(button.getAttribute("onclick").split("'")[1]);
  });
});

window.addEventListener("resize", function () {
  if (window.innerWidth > 512) {
    var menu = document.getElementById("menu");
    var menuToggle = document.getElementById("menuToggle");

    menu.classList.remove("active");
    menuToggle.classList.remove("active");
  }
});
