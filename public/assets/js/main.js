// menyembunyikan flash setelah beberapa detik

document.addEventListener("DOMContentLoaded", function () {
  setTimeout(() => {
    document
      .querySelectorAll(".flash")
      .forEach((f) => (f.style.display = "none"));
  }, 5000);
});
