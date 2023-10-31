function isElementInViewport(element) {
  const rect = element.getBoundingClientRect();
  return (
  rect.top >= 0 &&
  rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
  );
}

function animateOnScroll() {
  const sections = document.querySelectorAll(".section__wrapper");

  sections.forEach((section) => {
    const rect = section.getBoundingClientRect();
    const windowHeight = window.innerHeight;
    const visibilityHeight = windowHeight - 50;

    let opacity = 1;
    if (rect.top > visibilityHeight) {
      opacity = 0;
    } else if (rect.top < 0) {
      opacity = 1;
    } else {
      opacity = 1 - rect.top / visibilityHeight;
    }

    section.style.opacity = opacity;
  });
}


function scrollHandler() {
  animateOnScroll();
}

function smoothScroll(target) {
  const element = document.querySelector(target);
  if (element) {
  const offset = 50;
  window.scrollTo({
      behavior: "smooth",
      top: element.offsetTop - offset,
  });

  setTimeout(function () {
      element.classList.add("active");
  }, 500);
  }
}

window.addEventListener("scroll", scrollHandler);
window.addEventListener("load", scrollHandler);
