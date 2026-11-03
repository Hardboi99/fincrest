/* FINCREST 360 — home-premium.js
   Home page only: EMI calculator studio, EMI budget mode, and the interaction layer (3D cards,
   journey stepper, loan filters, parallax, scroll progress, FAQ accordion).
   Every block is optional; missing markup is skipped. The calculator uses the standard
   reducing-balance formula: EMI = P·r·(1+r)^n / ((1+r)^n − 1). */
(function () {
  "use strict";

  var doc = document.documentElement;
  doc.classList.add("hp-js");
  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var fine = !!(window.matchMedia && window.matchMedia("(hover: hover) and (pointer: fine)").matches);
  var lastCalc = null;
  var nf = new Intl.NumberFormat("en-IN", { maximumFractionDigits: 0 });

  /* ---------- formatting ---------- */
  function inr(v) { return "₹" + nf.format(Math.round(v)); }
  function trim(n) { return String(+n.toFixed(2)); }
  function short(v) {
    if (v >= 1e7) return "₹" + trim(v / 1e7) + " crore";
    if (v >= 1e5) return "₹" + trim(v / 1e5) + " lakh";
    return inr(v);
  }
  function tenureText(m) {
    var y = Math.floor(m / 12), r = m % 12, out = [];
    if (y) out.push(y + (y === 1 ? " year" : " years"));
    if (r) out.push(r + (r === 1 ? " month" : " months"));
    return out.join(" ");
  }
  function parse(text) {
    var t = String(text).replace(/[,\s₹%]/g, "");
    if (t === "" || !/^\d*\.?\d*$/.test(t)) return NaN;
    return parseFloat(t);
  }
  function clamp(v, lo, hi) { return Math.min(hi, Math.max(lo, v)); }

  /* ---------- maths ---------- */
  function emiOf(P, annual, n) {
    var r = annual / 1200;
    if (n <= 0 || P <= 0) return 0;
    if (r === 0) return Math.round(P / n);
    var f = Math.pow(1 + r, n);
    return Math.round(P * r * f / (f - 1));
  }
  /* Whole-rupee schedule; the last instalment absorbs rounding so the balance ends at exactly 0. */
  function schedule(P, annual, n) {
    var emi = emiOf(P, annual, n), r = annual / 1200, bal = P, rows = [], ti = 0, i;
    for (i = 1; i <= n && bal > 0; i++) {
      var interest = Math.round(bal * r), pay = emi, prin = pay - interest;
      if (i === n || prin > bal) { prin = bal; pay = bal + interest; }
      rows.push([i, bal, pay, prin, interest, bal - prin]);
      bal -= prin;
      ti += interest;
    }
    return { emi: emi, rows: rows, interest: ti, total: P + ti };
  }

  /* ---------- number tween ---------- */
  function setNum(el, to, fmt) {
    if (!el) return;
    var from = el._v;
    el._v = to;
    if (reduce || from === undefined || from === to) { el.textContent = fmt(to); return; }
    var t0 = null, id = (el._t = (el._t || 0) + 1);
    function step(t) {
      if (el._t !== id) return;
      if (t0 === null) t0 = t;
      var p = Math.min(1, (t - t0) / 380), k = 1 - Math.pow(1 - p, 3);
      el.textContent = fmt(from + (to - from) * k);
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  /* ---------- calculator ---------- */
  var root = document.querySelector("[data-hp-calc]");
  if (root) initCalc(root);

  function initCalc(root) {
    var cats = {};
    try { cats = JSON.parse(root.getAttribute("data-cats") || "{}"); } catch (e) { return; }
    var q = function (s) { return root.querySelector(s); };
    var qa = function (s) { return [].slice.call(root.querySelectorAll(s)); };
    var out = function (k) { return q('[data-out="' + k + '"]'); };

    var inAmount = q('[data-in="amount"]'), inRate = q('[data-in="rate"]'), inTenure = q('[data-in="tenure"]');
    var rAmount = q('[data-rng="amount"]'), rRate = q('[data-rng="rate"]'), rTenure = q('[data-rng="tenure"]');
    var errBox = q("[data-error]"), donut = q("[data-donut]");
    var arcP = q('[data-arc="p"]'), arcI = q('[data-arc="i"]'), tipEl = out("tip");
    var previewEmi = document.querySelector("[data-hp-preview-emi]");
    var previewNote = document.querySelector("[data-hp-preview-note]");
    var scheduleBox = q("[data-schedule]"), compareBody = q("[data-compare-body]"), scheduleBody = q("[data-schedule-body]");
    if (!inAmount || !inRate || !inTenure || !rAmount || !rRate || !rTenure) return;

    var state = {
      cat: root.getAttribute("data-default"),
      amount: parse(inAmount.value) || 500000,
      rate: parse(inRate.value),
      months: (parse(inTenure.value) || 3) * 12,
      unit: "years"
    };
    if (isNaN(state.rate)) state.rate = 12;
    var cat = function () { return cats[state.cat]; };

    function limits() {
      var c = cat();
      return {
        aMin: c.min, aMax: c.max, step: c.step,
        mMin: state.unit === "years" ? 12 : 6, mMax: c.maxYears * 12
      };
    }
    function fill(r) {
      var p = (r.value - r.min) / (r.max - r.min) * 100;
      r.style.setProperty("--hp-fill", clamp(p, 0, 100) + "%");
    }
    function showError(msg) { errBox.textContent = msg || ""; errBox.classList.toggle("is-on", !!msg); }
    function mark(input, bad) { input.setAttribute("aria-invalid", bad ? "true" : "false"); }

    /* writes state into inputs/sliders/labels */
    function syncControls(skip) {
      var L = limits(), isY = state.unit === "years";
      rAmount.min = L.aMin; rAmount.max = L.aMax; rAmount.step = L.step;
      rTenure.min = isY ? 1 : 6; rTenure.max = isY ? cat().maxYears : L.mMax; rTenure.step = 1;
      rAmount.value = state.amount; rRate.value = state.rate;
      rTenure.value = isY ? Math.round(state.months / 12) : state.months;
      if (skip !== inAmount) inAmount.value = nf.format(state.amount);
      if (skip !== inRate) inRate.value = String(state.rate);
      if (skip !== inTenure) inTenure.value = isY ? Math.round(state.months / 12) : state.months;
      [rAmount, rRate, rTenure].forEach(fill);
      out("amount-short").textContent = short(state.amount);
      out("amount-min").textContent = short(L.aMin);
      out("amount-max").textContent = short(L.aMax);
      out("tenure-text").textContent = tenureText(state.months);
      out("tenure-min").textContent = isY ? "1 year" : "6 months";
      out("tenure-max").textContent = isY ? cat().maxYears + " years" : L.mMax + " months";
    }

    function render() {
      var s = schedule(state.amount, state.rate, state.months);
      var pct = s.total ? s.interest / s.total * 100 : 0;
      setNum(out("emi"), s.emi, inr);
      setNum(out("principal"), state.amount, inr);
      setNum(out("interest"), s.interest, inr);
      setNum(out("total"), s.total, inr);
      lastPct = pct;
      showShare();
      setArcs(pct);
      donut.setAttribute("aria-label", "Principal " + inr(state.amount) + ", interest " + inr(s.interest));
      if (previewEmi) previewEmi.textContent = inr(s.emi);
      if (previewNote) previewNote.textContent = inr(state.amount) + " · " + state.rate + "% · " + tenureText(state.months);
      renderCompare();
      if (scheduleBox && scheduleBox.open) renderSchedule(s);
      out("schedule-count").textContent = "(" + s.rows.length + " instalments)";
      lastCalc = { principal: state.amount, interest: s.interest, emi: s.emi, total: s.total, pct: pct };
      document.dispatchEvent(new CustomEvent("hp:calc", { detail: lastCalc }));
    }

    /* donut: two stroke arcs (principal, interest) drawn on a 100-unit path */
    var lastPct = 0, hl = "";
    function setArcs(pi) {
      var gap = pi > 0.5 && pi < 99.5 ? 0.8 : 0, pp = 100 - pi;
      var a = Math.max(pp - gap, 0), b = Math.max(pi - gap, 0);
      if (!arcP || !arcI) return;
      arcP.style.strokeDasharray = a.toFixed(2) + " " + (100 - a).toFixed(2);
      arcP.style.strokeDashoffset = "0";
      arcI.style.strokeDasharray = b.toFixed(2) + " " + (100 - b).toFixed(2);
      arcI.style.strokeDashoffset = (-pp).toFixed(2);
    }
    function showShare() {
      var showP = hl === "p";
      out("donut-label").textContent = showP ? "Principal share" : "Interest share";
      setNum(out("interest-pct"), showP ? 100 - lastPct : lastPct, function (v) { return v.toFixed(1) + "%"; });
    }
    var tips = {
      p: "Principal: the amount you borrow. Each EMI repays a part of it.",
      i: "Interest: the cost of borrowing, charged on the outstanding balance.",
      t: "Total repayment: the principal plus all the interest over the full tenure."
    };
    function setTip(text) {
      if (!tipEl || tipEl.textContent === text) return;
      tipEl.classList.add("is-swap");
      setTimeout(function () { tipEl.textContent = text; tipEl.classList.remove("is-swap"); }, reduce ? 0 : 130);
    }
    qa("[data-tip]").forEach(function (el) {
      var k = el.getAttribute("data-tip");
      function on() { hl = k === "t" ? "" : k; donut.setAttribute("data-hl", hl); showShare(); setTip(tips[k]); }
      function off() { hl = ""; donut.removeAttribute("data-hl"); showShare(); setTip(tipEl.getAttribute("data-default")); }
      el.addEventListener("mouseenter", on); el.addEventListener("focus", on);
      el.addEventListener("mouseleave", off); el.addEventListener("blur", off);
    });

    function renderCompare() {
      if (!compareBody) return;
      var maxM = cat().maxYears * 12, list = [36, 60, 84].filter(function (m) { return m <= maxM; });
      if (list.indexOf(state.months) < 0) list.push(state.months);
      list.sort(function (a, b) { return a - b; });
      var frag = document.createDocumentFragment();
      list.forEach(function (m) {
        var s = schedule(state.amount, state.rate, m), tr = document.createElement("tr");
        if (m === state.months) tr.className = "is-now";
        [tenureText(m) + (m === state.months ? " (selected)" : ""), inr(s.emi), inr(s.interest), inr(s.total)].forEach(function (t, i) {
          var c = document.createElement(i ? "td" : "th");
          if (!i) c.scope = "row";
          c.textContent = t;
          tr.appendChild(c);
        });
        frag.appendChild(tr);
      });
      compareBody.textContent = "";
      compareBody.appendChild(frag);
    }

    function renderSchedule(s) {
      var frag = document.createDocumentFragment();
      s.rows.forEach(function (row) {
        var tr = document.createElement("tr");
        row.forEach(function (v, i) {
          var c = document.createElement("td");
          c.textContent = i ? nf.format(v) : v;
          tr.appendChild(c);
        });
        frag.appendChild(tr);
      });
      scheduleBody.textContent = "";
      scheduleBody.appendChild(frag);
    }

    /* ----- input handling ----- */
    function numericHandler(input, key) {
      input.addEventListener("input", function () {
        var v = parse(input.value), L = limits(), isY = state.unit === "years";
        var lo, hi, label;
        if (key === "amount") { lo = L.aMin; hi = L.aMax; label = "an amount between " + inr(lo) + " and " + inr(hi); }
        if (key === "rate") { lo = 0; hi = 40; label = "a rate between 0% and 40%"; }
        if (key === "tenure") {
          lo = isY ? 1 : 6; hi = isY ? cat().maxYears : L.mMax;
          label = "a tenure between " + lo + " and " + hi + (isY ? " years" : " months");
        }
        var bad = isNaN(v) || v < lo || v > hi || (key === "tenure" && v % 1 !== 0);
        mark(input, bad);
        if (bad) { showError(isNaN(v) ? "Please enter a valid number." : "Enter " + label + "."); return; }
        showError("");
        if (key === "amount") state.amount = v;
        if (key === "rate") state.rate = v;
        if (key === "tenure") state.months = isY ? v * 12 : v;
        syncControls(input);
        render();
      });
      input.addEventListener("change", function () {
        var v = parse(input.value), L = limits(), isY = state.unit === "years";
        if (!isNaN(v)) {
          if (key === "amount") state.amount = clamp(Math.round(v), L.aMin, L.aMax);
          if (key === "rate") state.rate = clamp(Math.round(v * 10) / 10, 0, 40);
          if (key === "tenure") {
            var m = isY ? Math.round(v) * 12 : Math.round(v);
            state.months = clamp(m, L.mMin, L.mMax);
          }
        }
        mark(input, false);
        showError("");
        syncControls();
        render();
      });
    }
    numericHandler(inAmount, "amount");
    numericHandler(inRate, "rate");
    numericHandler(inTenure, "tenure");

    rAmount.addEventListener("input", function () { state.amount = parseFloat(rAmount.value); showError(""); syncControls(); render(); });
    rRate.addEventListener("input", function () { state.rate = parseFloat(rRate.value); showError(""); syncControls(); render(); });
    rTenure.addEventListener("input", function () {
      var v = parseInt(rTenure.value, 10);
      state.months = state.unit === "years" ? v * 12 : v;
      showError(""); syncControls(); render();
    });

    qa("[data-unit]").forEach(function (b) {
      b.addEventListener("click", function () {
        state.unit = b.getAttribute("data-unit");
        qa("[data-unit]").forEach(function (o) {
          var on = o === b;
          o.classList.toggle("is-on", on);
          o.setAttribute("aria-pressed", on ? "true" : "false");
        });
        if (state.unit === "years") state.months = Math.max(12, Math.round(state.months / 12) * 12);
        showError(""); syncControls(); render();
      });
    });

    function setCategory(slug, keepValues) {
      if (!cats[slug]) return;
      state.cat = slug;
      var c = cat();
      if (!keepValues) { state.amount = c.amount; state.months = c.years * 12; }
      state.amount = clamp(state.amount, c.min, c.max);
      state.months = clamp(state.months, state.unit === "years" ? 12 : 6, c.maxYears * 12);
      qa("[data-cat]").forEach(function (b) {
        var on = b.getAttribute("data-cat") === slug;
        b.classList.toggle("is-on", on);
        b.setAttribute("aria-pressed", on ? "true" : "false");
      });
      var link = q("[data-explore]");
      link.href = c.href;
      out("explore").textContent = "Explore " + c.title;
      showError(""); syncControls(); render();
    }
    qa("[data-cat]").forEach(function (b) {
      b.addEventListener("click", function () { setCategory(b.getAttribute("data-cat")); });
    });

    if (scheduleBox) scheduleBox.addEventListener("toggle", function () {
      if (scheduleBox.open) renderSchedule(schedule(state.amount, state.rate, state.months));
    });

    /* ----- mode switch + budget ----- */
    var panels = qa("[data-panel]");
    function setMode(mode) {
      qa("[data-mode]").forEach(function (b) {
        var on = b.getAttribute("data-mode") === mode;
        b.classList.toggle("is-on", on);
        b.setAttribute("aria-pressed", on ? "true" : "false");
      });
      panels.forEach(function (p) { p.hidden = p.getAttribute("data-panel") !== mode; });
    }
    qa("[data-mode]").forEach(function (b) {
      b.addEventListener("click", function () { setMode(b.getAttribute("data-mode")); });
    });

    var bIn = {};
    qa("[data-b]").forEach(function (el) { bIn[el.getAttribute("data-b")] = el; });
    var bErr = q("[data-berror]"), bResult = { emi: 0, amount: 0 };
    function budget() {
      var income = parse(bIn.income.value), existing = parse(bIn.existing.value), pct = parseFloat(bIn.pct.value);
      var rate = parse(bIn.rate.value), years = parse(bIn.years.value), msg = "";
      fill(bIn.pct);
      out("pct-text").textContent = pct + "%";
      if (isNaN(income) || income <= 0 || income > 1e7) msg = "Enter your monthly take-home income.";
      else if (isNaN(existing) || existing < 0) msg = "Enter existing EMIs as 0 or more.";
      else if (isNaN(rate) || rate > 40) msg = "Enter a rate between 0% and 40%.";
      else if (isNaN(years) || years < 1 || years > 30 || years % 1) msg = "Enter a tenure of 1 to 30 whole years.";
      bErr.textContent = msg;
      bErr.classList.toggle("is-on", !!msg);
      if (msg) { out("b-emi").textContent = "—"; out("b-amount").textContent = "—"; bResult = { emi: 0, amount: 0 }; return; }
      var emi = Math.max(0, Math.round(income * pct / 100 - existing)), r = rate / 1200, n = years * 12, P;
      P = r === 0 ? emi * n : emi * (1 - Math.pow(1 + r, -n)) / r;
      P = Math.floor(P / 1000) * 1000;
      bResult = { emi: emi, amount: P, rate: rate, years: years };
      setNum(out("b-emi"), emi, inr);
      setNum(out("b-amount"), P, inr);
    }
    Object.keys(bIn).forEach(function (k) { bIn[k].addEventListener("input", budget); });
    var use = q("[data-use-budget]");
    if (use) use.addEventListener("click", function () {
      if (!bResult.amount) { bErr.textContent = "Adjust the inputs to get a budget first."; bErr.classList.add("is-on"); return; }
      var c = cat();
      state.amount = clamp(Math.round(bResult.amount / c.step) * c.step, c.min, c.max);
      state.rate = clamp(bResult.rate, 0, 40);
      state.months = clamp(bResult.years * 12, state.unit === "years" ? 12 : 6, c.maxYears * 12);
      setMode("emi");
      syncControls(); render();
    });

    setCategory(state.cat, true);
    budget();
  }

  /* ---------- scroll-driven helpers: section entrances, progress bar, background parallax ---------- */
  var raf = window.requestAnimationFrame.bind(window);
  var scrollHooks = [];

  var seqs = [].slice.call(document.querySelectorAll("[data-hp-seq]"));
  if (seqs.length) {
    if (!("IntersectionObserver" in window) || reduce) {
      seqs.forEach(function (s) { s.classList.add("is-in"); });
    } else {
      var seqIo = new IntersectionObserver(function (en) {
        en.forEach(function (e) {
          if (!e.isIntersecting) return;
          e.target.classList.add("is-in");
          seqIo.unobserve(e.target);
        });
      }, { threshold: 0.2, rootMargin: "0px 0px -8% 0px" });
      seqs.forEach(function (s) { seqIo.observe(s); });
    }
  }

  var bar = document.createElement("div");
  bar.className = "hp-progress";
  bar.setAttribute("aria-hidden", "true");
  bar.innerHTML = "<i></i>";
  document.body.appendChild(bar);
  var bgs = [].slice.call(document.querySelectorAll("[data-hp-bg]"));
  var ticking = false;
  function onScroll() {
    ticking = false;
    var h = doc.scrollHeight - window.innerHeight;
    bar.style.setProperty("--hp-sp", h > 0 ? clamp(window.scrollY / h, 0, 1).toFixed(4) : "0");
    if (!reduce) bgs.forEach(function (s) {
      var r = s.getBoundingClientRect(), vh = window.innerHeight;
      if (r.bottom < -100 || r.top > vh + 100) return;
      s.style.setProperty("--hp-sy", clamp((r.top + r.height / 2 - vh / 2) / (vh / 2 + r.height / 2), -1, 1).toFixed(3));
    });
    scrollHooks.forEach(function (f) { f(); });
  }
  window.addEventListener("scroll", function () { if (!ticking) { ticking = true; raf(onScroll); } }, { passive: true });
  window.addEventListener("resize", function () { if (!ticking) { ticking = true; raf(onScroll); } });

  /* ---------- hero: pointer parallax ---------- */
  var hero = document.querySelector("[data-hp-hero]");
  if (hero && fine && !reduce) {
    var hf = 0, hx = 0, hy = 0;
    hero.addEventListener("pointermove", function (e) {
      var r = hero.getBoundingClientRect();
      hx = ((e.clientX - r.left) / r.width - 0.5) * 2;
      hy = ((e.clientY - r.top) / r.height - 0.5) * 2;
      if (!hf) hf = raf(function () { hf = 0; hero.style.setProperty("--px", hx.toFixed(3)); hero.style.setProperty("--py", hy.toFixed(3)); });
    });
    hero.addEventListener("pointerleave", function () { hero.style.setProperty("--px", "0"); hero.style.setProperty("--py", "0"); });
  }

  /* ---------- hero: sliding image window ---------- */
  var track = document.querySelector("[data-hp-track]");
  if (track && !reduce) {
    var total = track.children.length, idx = 0, timer = 0;
    var go = function () {
      idx++;
      track.style.transform = "translate3d(" + (-idx * 100) + "%,0,0)";
    };
    track.addEventListener("transitionend", function (e) {
      if (e.target !== track || idx < total - 1) return;
      track.classList.add("is-snap");
      idx = 0;
      track.style.transform = "translate3d(0,0,0)";
      void track.offsetWidth;
      track.classList.remove("is-snap");
    });
    var start = function () { stop(); timer = setInterval(go, 4500); };
    var stop = function () { clearInterval(timer); };
    document.addEventListener("visibilitychange", function () { document.hidden ? stop() : start(); });
    start();
  }

  /* ---------- magnetic buttons (very small range) ---------- */
  if (fine && !reduce) {
    [].forEach.call(document.querySelectorAll("[data-hp-magnet]"), function (b) {
      var cx = 0, cy = 0;
      b.addEventListener("pointermove", function (e) {
        var r = b.getBoundingClientRect(), mx = r.left + r.width / 2 - cx, my = r.top + r.height / 2 - cy;
        cx = clamp((e.clientX - mx) / r.width * 12, -6, 6);
        cy = clamp((e.clientY - my) / r.height * 10, -5, 5);
        b.classList.add("is-mag");
        b.style.setProperty("--tx", cx.toFixed(1) + "px");
        b.style.setProperty("--ty", cy.toFixed(1) + "px");
      });
      b.addEventListener("pointerleave", function () {
        cx = cy = 0;
        b.classList.remove("is-mag");
        b.style.removeProperty("--tx");
        b.style.removeProperty("--ty");
      });
    });
  }

  /* ---------- 3D tilt + cursor light (mouse only) ---------- */
  if (fine && !reduce) {
    [].forEach.call(document.querySelectorAll("[data-hp-tilt]"), function (el) {
      var host = el.closest(".hp-why-card, .hp-loan") || el, group = el.closest("[data-hp-cards]");
      var max = parseFloat(el.getAttribute("data-tilt-max")) || 3, frame = 0, px = 0.5, py = 0.5;
      function apply() {
        frame = 0;
        el.style.setProperty("--rx", ((0.5 - py) * 2 * max).toFixed(2) + "deg");
        el.style.setProperty("--ry", ((px - 0.5) * 2 * max).toFixed(2) + "deg");
        el.style.setProperty("--mx", (px * 100).toFixed(1) + "%");
        el.style.setProperty("--my", (py * 100).toFixed(1) + "%");
      }
      function reset() {
        ["--rx", "--ry", "--mx", "--my"].forEach(function (k) { el.style.removeProperty(k); });
      }
      host.addEventListener("pointerenter", function (e) {
        if (e.pointerType !== "mouse") return;
        host.classList.add("is-active");
        if (group) group.classList.add("has-active");
        el.classList.add("is-live");
      });
      host.addEventListener("pointermove", function (e) {
        if (e.pointerType !== "mouse") return;
        var r = host.getBoundingClientRect();
        px = clamp((e.clientX - r.left) / r.width, 0, 1);
        py = clamp((e.clientY - r.top) / r.height, 0, 1);
        if (!frame) frame = raf(apply);
      });
      host.addEventListener("pointerleave", function () {
        host.classList.remove("is-active");
        if (group && !group.querySelector(".is-active")) group.classList.remove("has-active");
        el.classList.remove("is-live");
        if (frame) { cancelAnimationFrame(frame); frame = 0; }
        reset();
      });
    });
  }

  /* ---------- how it works: interactive journey ---------- */
  var jr = document.querySelector("[data-hp-journey]");
  if (jr) {
    var jSteps = [].slice.call(jr.querySelectorAll(".hp-j-step"));
    var jBtns = jSteps.map(function (s) { return s.querySelector("[data-j-btn]"); });
    var jCur = -1, jLock = null, jMobile = window.matchMedia("(max-width: 640px)");
    function jSet(i) {
      if (i === jCur) return;
      jCur = i;
      jSteps.forEach(function (s, k) {
        s.classList.toggle("is-active", k === i);
        s.classList.toggle("is-done", k < i);
        jBtns[k].setAttribute("aria-pressed", k === i ? "true" : "false");
      });
    }
    function jChoose(i, focus) {
      i = clamp(i, 0, jSteps.length - 1);
      jLock = window.scrollY;
      jSet(i);
      if (focus) jBtns[i].focus({ preventScroll: true });
      if (jMobile.matches) setTimeout(function () {
        var r = jBtns[i].getBoundingClientRect();
        if (r.top < 90 || r.top > window.innerHeight * 0.55) window.scrollTo({ top: window.scrollY + r.top - 110, behavior: reduce ? "auto" : "smooth" });
      }, reduce ? 0 : 520);
    }
    jBtns.forEach(function (b, i) { b.addEventListener("click", function () { jChoose(i, false); }); });
    [].forEach.call(jr.querySelectorAll("[data-j-go]"), function (b) {
      b.addEventListener("click", function () { jChoose(parseInt(b.getAttribute("data-j-go"), 10), true); });
    });
    jr.addEventListener("keydown", function (e) {
      var t = e.target.closest && e.target.closest("[data-j-btn]");
      if (!t) return;
      var i = parseInt(t.getAttribute("data-j-btn"), 10), n = i;
      if (e.key === "ArrowRight" || e.key === "ArrowDown") n = i + 1;
      else if (e.key === "ArrowLeft" || e.key === "ArrowUp") n = i - 1;
      else if (e.key === "Home") n = 0;
      else if (e.key === "End") n = jSteps.length - 1;
      else return;
      e.preventDefault();
      jChoose(n, true);
    });
    /* scroll-linked: the step follows the page, until the visitor picks one by hand */
    scrollHooks.push(function () {
      if (jMobile.matches) return;
      if (jLock !== null) { if (Math.abs(window.scrollY - jLock) < 160) return; jLock = null; }
      var r = jr.getBoundingClientRect(), vh = window.innerHeight;
      var p = clamp((vh * 0.72 - r.top) / (vh * 0.68), 0, 0.999);
      jSet(Math.floor(p * jSteps.length));
    });
    jSet(0);
  }

  /* ---------- loan solutions: filter ---------- */
  var fbar = document.querySelector("[data-hp-filter]"), lgrid = document.querySelector("[data-hp-loans]");
  if (fbar && lgrid) {
    var lcards = [].slice.call(lgrid.querySelectorAll("a.hp-loan")), promo = lgrid.querySelector("[data-promo]");
    var fbtns = [].slice.call(fbar.querySelectorAll("[data-f]")), thumb = fbar.querySelector(".hp-filter-thumb");
    var countEl = fbar.querySelector("[data-hp-count]"), filt = "all", fTimer = 0, fTimer2 = 0;
    var names = { all: "", secured: "secured ", unsecured: "unsecured " };
    var inner = function (c) { return c.firstElementChild; };

    var featured = lcards[0];
    function columns() { return getComputedStyle(lgrid).gridTemplateColumns.split(" ").length; }
    /* The selected loan is always the large card in the first grid position; the promo card fills the last row. */
    function layout(animate) {
      var vis = lcards.filter(function (c) { return !c.hidden; }), c = columns(), first = {};
      if (!vis.length) return;
      if (!featured || featured.hidden) featured = vis[0];
      if (animate && !reduce) vis.concat(promo).forEach(function (x, i) { first[i] = x.getBoundingClientRect(); });
      lcards.forEach(function (x) { x.classList.toggle("hp-loan--feature", x === featured); });
      lgrid.insertBefore(featured, lgrid.firstChild);
      var k = vis.length - 1, span = c === 4 ? (k < 4 ? 2 : 4 - ((k - 4) % 4)) : c;
      lgrid.classList.toggle("hp-loans--few", c === 4 && k < 4);
      promo.style.setProperty("--hp-promo", String(c === 4 && k < 4 ? 4 : Math.min(span, c)));
      if (animate && !reduce) {
        vis.concat(promo).forEach(function (x, i) {
          var a = first[i], b = x.getBoundingClientRect(), dx = a.left - b.left, dy = a.top - b.top;
          if (dx || dy) x.animate([{ transform: "translate(" + dx + "px," + dy + "px)" }, { transform: "none" }], { duration: 650, easing: "cubic-bezier(0.22, 1, 0.36, 1)" });
        });
        var fi = inner(featured);
        fi.animate([{ opacity: 0.55, scale: "0.97" }, { opacity: 1, scale: "1" }], { duration: 650, easing: "cubic-bezier(0.22, 1, 0.36, 1)" });
      }
    }
    lcards.forEach(function (card) {
      card.addEventListener("click", function (e) {
        if (card === featured) return;
        e.preventDefault();
        featured = card;
        layout(true);
      });
    });
    function place() {
      var on = fbar.querySelector("[data-f].is-on");
      if (!on || !thumb) return;
      thumb.style.width = on.offsetWidth + "px";
      thumb.style.transform = "translateX(" + on.offsetLeft + "px)";
    }
    function setCount() {
      var n = lcards.filter(function (c) { return !c.hidden; }).length;
      countEl.innerHTML = "Showing <b>" + n + "</b> " + names[filt] + "loan solution" + (n === 1 ? "" : "s");
    }
    function applyFilter(f) {
      if (f === filt) return;
      filt = f;
      fbtns.forEach(function (b) {
        var on = b.getAttribute("data-f") === f;
        b.classList.toggle("is-on", on);
        b.setAttribute("aria-pressed", on ? "true" : "false");
      });
      place();
      clearTimeout(fTimer); clearTimeout(fTimer2);
      var oldH = lgrid.offsetHeight;
      function swap() {
        lcards.forEach(function (c) { c.hidden = !(f === "all" || c.getAttribute("data-group") === f); });
        layout(); setCount();
        var shown = lcards.filter(function (c) { return !c.hidden; }).concat(promo);
        if (reduce) { shown.forEach(function (c) { c.classList.remove("is-out"); }); return; }
        shown.forEach(function (c) { c.classList.add("is-out"); inner(c).style.transitionDelay = "0ms"; });
        lgrid.style.height = "auto";
        var newH = lgrid.offsetHeight;
        lgrid.style.height = oldH + "px";
        void lgrid.offsetHeight;
        lgrid.style.transition = "height 0.55s cubic-bezier(0.22, 1, 0.36, 1)";
        lgrid.style.height = newH + "px";
        shown.forEach(function (c, i) {
          setTimeout(function () { inner(c).style.transitionDelay = ""; c.classList.remove("is-out"); }, 40 + i * 55);
        });
        fTimer2 = setTimeout(function () { lgrid.style.height = ""; lgrid.style.transition = ""; }, 700);
      }
      if (reduce) { swap(); return; }
      lgrid.style.height = oldH + "px";
      lcards.concat(promo).forEach(function (c) { if (!c.hidden) c.classList.add("is-out"); });
      fTimer = setTimeout(swap, 300);
    }
    fbtns.forEach(function (b) { b.addEventListener("click", function () { applyFilter(b.getAttribute("data-f")); }); });
    layout(); place();
    window.addEventListener("resize", function () { layout(); place(); });
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(place);
    window.addEventListener("load", place);
  }

  /* ---------- clarity flow: related concepts + live figures from the calculator ---------- */
  var flow = document.querySelector("[data-hp-flow]");
  if (flow) {
    var ncs = [].slice.call(flow.querySelectorAll(".hp-nc"));
    var rel = { principal: ["interest", "total"], interest: ["principal", "total"], emi: ["total"], total: ["principal", "interest", "emi"] };
    var pinned = null;
    function mark(k) {
      ncs.forEach(function (n) { n.classList.toggle("is-rel", !!k && rel[k].indexOf(n.getAttribute("data-k")) > -1); });
    }
    ncs.forEach(function (n) {
      var k = n.getAttribute("data-k");
      n.addEventListener("mouseenter", function () { mark(k); });
      n.addEventListener("focus", function () { mark(k); });
      n.addEventListener("mouseleave", function () { mark(pinned); });
      n.addEventListener("blur", function () { mark(pinned); });
      n.addEventListener("click", function () {
        pinned = pinned === k ? null : k;
        ncs.forEach(function (o) { o.classList.toggle("is-on", o.getAttribute("data-k") === pinned); });
        mark(pinned);
      });
    });
    var live = {};
    ncs.forEach(function (n) { live[n.getAttribute("data-k")] = n.querySelector("[data-hp-val]"); });
    var eqP = document.querySelector(".hp-eq-p"), eqI = document.querySelector(".hp-eq-i");
    function paint(d) {
      live.principal.textContent = inr(d.principal);
      live.interest.textContent = inr(d.interest);
      live.emi.textContent = inr(d.emi) + " / month";
      live.total.textContent = inr(d.total);
      if (eqP && eqI) { eqP.style.width = (100 - d.pct).toFixed(1) + "%"; eqI.style.width = d.pct.toFixed(1) + "%"; }
    }
    document.addEventListener("hp:calc", function (e) { paint(e.detail); });
    if (lastCalc) paint(lastCalc);
  }

  /* ---------- FAQ: one open at a time ---------- */
  var faq = document.querySelector("[data-hp-faq]");
  if (faq) {
    var items = [].slice.call(faq.querySelectorAll("details"));
    items.forEach(function (d) {
      d.addEventListener("toggle", function () {
        if (!d.open) return;
        items.forEach(function (o) { if (o !== d) o.open = false; });
      });
    });
  }
})();
