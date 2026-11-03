/* FINCREST 360 — ui.js
   Scroll reveal, number counters and the hero carousel. Every block is optional:
   pages that do not contain the markup simply skip it. */
(function () {
  "use strict";

  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  document.documentElement.classList.remove("no-js");

  /* ---------- counters ---------- */
  function countUp(el) {
    var to = parseFloat(el.getAttribute("data-count"));
    var suffix = el.getAttribute("data-suffix") || "";
    if (isNaN(to)) return;
    if (reduce) { el.textContent = to + suffix; return; }
    var start = null, dur = 1500;
    function step(t) {
      if (start === null) start = t;
      var p = Math.min(1, (t - start) / dur);
      el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3))) + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  /* ---------- reveal on scroll ---------- */
  var targets = [].slice.call(document.querySelectorAll(".reveal, .steps"));
  function show(el) {
    el.classList.add("is-in");
    [].forEach.call(el.querySelectorAll("[data-count]"), countUp);
    if (el.hasAttribute("data-count")) countUp(el);
  }
  if (!("IntersectionObserver" in window) || reduce) {
    targets.forEach(show);
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        show(en.target);
        io.unobserve(en.target);
      });
    }, { threshold: 0.2, rootMargin: "0px 0px -40px 0px" });
    targets.forEach(function (t) { io.observe(t); });
  }

  /* ---------- hero carousel ---------- */
  var hero = document.querySelector("[data-carousel]");
  if (hero) {
    var slides = [].slice.call(hero.querySelectorAll(".slide"));
    var dots = [].slice.call(hero.querySelectorAll(".hero-dot"));
    var current = 0, timer = null;

    function go(n) {
      current = (n + slides.length) % slides.length;
      slides.forEach(function (s, i) {
        var on = i === current;
        s.classList.toggle("is-active", on);
        s.setAttribute("aria-hidden", on ? "false" : "true");
      });
      dots.forEach(function (d, i) {
        d.classList.toggle("is-active", i === current);
        d.setAttribute("aria-current", i === current ? "true" : "false");
      });
    }
    function play() {
      if (reduce || slides.length < 2) return;
      stop();
      timer = setInterval(function () { go(current + 1); }, 7000);
    }
    function stop() { clearInterval(timer); }

    hero.querySelector("[data-prev]").addEventListener("click", function () { go(current - 1); play(); });
    hero.querySelector("[data-next]").addEventListener("click", function () { go(current + 1); play(); });
    dots.forEach(function (d, i) { d.addEventListener("click", function () { go(i); play(); }); });
    hero.addEventListener("mouseenter", stop);
    hero.addEventListener("mouseleave", play);
    hero.addEventListener("focusin", stop);
    hero.addEventListener("focusout", play);
    hero.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") { go(current - 1); play(); }
      if (e.key === "ArrowRight") { go(current + 1); play(); }
    });
    go(0);
    play();
  }
})();
