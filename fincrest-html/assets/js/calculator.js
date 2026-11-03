/* FINCREST 360 — calculator.js
   EMI calculator using the standard reducing-balance formula:
   EMI = P * r * (1+r)^n / ((1+r)^n - 1), r = annual rate / 12 / 100, n = months.
   Markup: any element with [data-emi]. Inputs are named amount, rate, years. */
(function () {
  "use strict";

  function inr(n) { return "₹" + Math.round(n).toLocaleString("en-IN"); }
  function pct(el) {
    var min = +el.min, max = +el.max, v = +el.value;
    el.style.setProperty("--fill", (((v - min) / (max - min)) * 100) + "%");
  }

  [].forEach.call(document.querySelectorAll("[data-emi]"), function (root) {
    var f = {
      amount: root.querySelectorAll('[name="amount"]'),
      rate: root.querySelectorAll('[name="rate"]'),
      years: root.querySelectorAll('[name="years"]')
    };
    var out = {
      emi: root.querySelector("[data-out-emi]"),
      interest: root.querySelector("[data-out-interest]"),
      total: root.querySelector("[data-out-total]"),
      principal: root.querySelector("[data-out-principal]"),
      donut: root.querySelector(".donut"),
      donutText: root.querySelector("[data-out-donut]"),
      err: root.querySelector(".calc-error")
    };
    var amtLabel = root.querySelector("[data-label-amount]");
    var rateLabel = root.querySelector("[data-label-rate]");
    var yearLabel = root.querySelector("[data-label-years]");

    function read(name) { return parseFloat(f[name][0].value); }
    function sync(name, value, skip) {
      [].forEach.call(f[name], function (el) { if (el !== skip) el.value = value; });
    }

    function calc() {
      var P = read("amount"), R = read("rate"), Y = read("years");
      var msg = "";
      if (!(P >= 10000)) msg = "Enter a loan amount of at least ₹10,000.";
      else if (!(R >= 1 && R <= 40)) msg = "Enter an interest rate between 1% and 40%.";
      else if (!(Y >= 1 && Y <= 30)) msg = "Enter a tenure between 1 and 30 years.";
      if (out.err) out.err.textContent = msg;
      [].forEach.call(root.querySelectorAll('input[type="range"]'), pct);
      if (amtLabel) amtLabel.textContent = isNaN(P) ? "" : inr(P);
      if (rateLabel) rateLabel.textContent = isNaN(R) ? "" : R + "%";
      if (yearLabel) yearLabel.textContent = isNaN(Y) ? "" : Y + (Y === 1 ? " year" : " years");
      if (msg) {
        [out.emi, out.interest, out.total, out.principal].forEach(function (e) { if (e) e.textContent = "—"; });
        return;
      }
      var n = Math.round(Y * 12), r = R / 12 / 100;
      var emi = (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
      var total = emi * n, interest = total - P;
      if (out.emi) out.emi.textContent = inr(emi);
      if (out.interest) out.interest.textContent = inr(interest);
      if (out.total) out.total.textContent = inr(total);
      if (out.principal) out.principal.textContent = inr(P);
      if (out.donut) out.donut.style.setProperty("--p", (P / total) * 100);
      if (out.donutText) out.donutText.textContent = inr(emi);
    }

    ["amount", "rate", "years"].forEach(function (name) {
      [].forEach.call(f[name], function (el) {
        el.addEventListener("input", function () { sync(name, el.value, el); calc(); });
      });
    });
    root.addEventListener("submit", function (e) { e.preventDefault(); });
    calc();
  });
})();
