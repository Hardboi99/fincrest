/* FINCREST 360 — home-premium.js
   Home page only: EMI calculator studio, EMI budget mode, timeline progress and FAQ accordion.
   Every block is optional; missing markup is skipped. The calculator uses the standard
   reducing-balance formula: EMI = P·r·(1+r)^n / ((1+r)^n − 1). */
(function () {
  "use strict";

  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
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
      r.style.setProperty("--fill", clamp(p, 0, 100) + "%");
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
      setNum(out("interest-pct"), pct, function (v) { return v.toFixed(1) + "%"; });
      donut.style.setProperty("--hp-p", pct.toFixed(2));
      donut.setAttribute("aria-label", "Principal " + inr(state.amount) + ", interest " + inr(s.interest));
      if (previewEmi) previewEmi.textContent = inr(s.emi);
      if (previewNote) previewNote.textContent = inr(state.amount) + " · " + state.rate + "% · " + tenureText(state.months);
      renderCompare();
      if (scheduleBox && scheduleBox.open) renderSchedule(s);
      out("schedule-count").textContent = "(" + s.rows.length + " instalments)";
    }

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

  /* ---------- timeline progress ---------- */
  var tl = document.querySelector("[data-hp-timeline]");
  if (tl) {
    if (!("IntersectionObserver" in window) || reduce) tl.classList.add("is-in");
    else {
      var io = new IntersectionObserver(function (en) {
        if (en[0].isIntersecting) { tl.classList.add("is-in"); io.disconnect(); }
      }, { threshold: 0.35 });
      io.observe(tl);
    }
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
