/* FINCREST 360 — nav.js
   Sticky header shadow, mega menu (hover, click, keyboard, Escape, outside click)
   and the mobile accordion menu. No dependencies. */
(function () {
  "use strict";

  var header = document.getElementById("site-header");
  if (!header) return;

  var burger = header.querySelector(".burger");
  var mobile = document.getElementById("mobile-menu");
  var items = [].slice.call(header.querySelectorAll(".nav-item[data-menu]"));
  var timers = new WeakMap();

  /* ---- sticky state ---- */
  var ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function () {
      header.classList.toggle("is-scrolled", window.scrollY > 24);
      ticking = false;
    });
  }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  /* ---- desktop menus ---- */
  function setOpen(item, open) {
    item.classList.toggle("is-open", open);
    item.querySelector(".nav-link").setAttribute("aria-expanded", open ? "true" : "false");
  }
  function closeAll(except) {
    items.forEach(function (i) { if (i !== except) setOpen(i, false); });
  }
  var canHover = window.matchMedia && window.matchMedia("(hover: hover)").matches;

  items.forEach(function (item) {
    var btn = item.querySelector(".nav-link");
    if (canHover) {
      item.addEventListener("mouseenter", function () {
        clearTimeout(timers.get(item));
        closeAll(item);
        setOpen(item, true);
      });
      item.addEventListener("mouseleave", function () {
        timers.set(item, setTimeout(function () { setOpen(item, false); }, 140));
      });
    }
    btn.addEventListener("click", function () {
      var open = !item.classList.contains("is-open");
      closeAll(item);
      setOpen(item, open);
    });
    item.addEventListener("focusout", function (e) {
      if (!item.contains(e.relatedTarget)) setOpen(item, false);
    });
  });

  document.addEventListener("click", function (e) {
    if (!header.contains(e.target)) closeAll();
  });
  document.addEventListener("keydown", function (e) {
    if (e.key !== "Escape") return;
    var open = header.querySelector(".nav-item.is-open");
    closeAll();
    if (open) open.querySelector(".nav-link").focus();
    closeMobile();
  });

  /* ---- mobile menu ---- */
  function closeMobile() {
    if (!mobile) return;
    mobile.classList.remove("is-open");
    burger.setAttribute("aria-expanded", "false");
    burger.setAttribute("aria-label", "Open menu");
    document.body.classList.remove("is-locked");
  }
  if (burger && mobile) {
    burger.addEventListener("click", function () {
      var open = !mobile.classList.contains("is-open");
      mobile.classList.toggle("is-open", open);
      burger.setAttribute("aria-expanded", open ? "true" : "false");
      burger.setAttribute("aria-label", open ? "Close menu" : "Open menu");
      document.body.classList.toggle("is-locked", open);
    });
    [].forEach.call(mobile.querySelectorAll(".m-item > button"), function (b) {
      b.addEventListener("click", function () {
        var wrap = b.parentElement;
        var open = !wrap.classList.contains("is-open");
        wrap.classList.toggle("is-open", open);
        b.setAttribute("aria-expanded", open ? "true" : "false");
      });
    });
    mobile.addEventListener("click", function (e) {
      if (e.target.closest("a")) closeMobile();
    });
    window.addEventListener("resize", function () {
      if (window.innerWidth > 1120) closeMobile();
    });
  }
})();
