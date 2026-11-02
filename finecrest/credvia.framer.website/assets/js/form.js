/* FINCREST 360 — form.js
   Validation and AJAX submit for every form[data-enquiry].
   The server (submit.php) validates again, so this is a convenience layer. */
(function () {
  "use strict";

  var rules = {
    name: function (v) { return v.trim().length >= 2 ? "" : "Please enter your full name."; },
    mobile: function (v) { return /^[6-9]\d{9}$/.test(v.replace(/[\s-]/g, "").replace(/^(\+91|91|0)(?=\d{10}$)/, "")) ? "" : "Enter a valid 10-digit Indian mobile number."; },
    email: function (v) { return !v || /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) ? "" : "Enter a valid email address."; },
    city: function (v) { return v.trim().length >= 2 ? "" : "Please enter your city."; },
    loan: function (v) { return v ? "" : "Please choose a loan type."; },
    consent: function (v, el) { return el.checked ? "" : "Please tick the box to continue."; }
  };

  function errBox(el) { return el.closest(".field, .consent-wrap").querySelector(".field-error"); }
  function check(el) {
    var rule = rules[el.name];
    if (!rule) return true;
    var msg = rule(el.value, el);
    var box = errBox(el);
    if (box) box.textContent = msg;
    if (msg) el.setAttribute("aria-invalid", "true"); else el.removeAttribute("aria-invalid");
    return !msg;
  }

  [].forEach.call(document.querySelectorAll("form[data-enquiry]"), function (form) {
    var status = form.querySelector(".form-status");
    var button = form.querySelector('button[type="submit"]');
    var fields = [].slice.call(form.querySelectorAll("input, select, textarea")).filter(function (el) { return rules[el.name]; });

    fields.forEach(function (el) {
      el.addEventListener("blur", function () { check(el); });
      el.addEventListener("input", function () { if (el.getAttribute("aria-invalid")) check(el); });
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var bad = fields.filter(function (el) { return !check(el); });
      if (bad.length) { bad[0].focus(); return; }

      button.disabled = true;
      status.className = "form-status";
      status.textContent = "";
      var label = button.textContent;
      button.textContent = "Sending…";

      fetch(form.action, { method: "POST", body: new FormData(form), headers: { Accept: "application/json" } })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.ok) {
            var ok = form.parentElement.querySelector(".form-success");
            form.hidden = true;
            if (ok) { ok.hidden = false; ok.focus(); }
            return;
          }
          if (data.errors) {
            Object.keys(data.errors).forEach(function (k) {
              var el = form.elements[k];
              if (el && errBox(el)) { errBox(el).textContent = data.errors[k]; el.setAttribute("aria-invalid", "true"); }
            });
          }
          status.className = "form-status is-err";
          status.textContent = data.message || "Please check the highlighted fields.";
        })
        .catch(function () {
          status.className = "form-status is-err";
          status.textContent = "We could not send your enquiry. Please try again in a moment.";
        })
        .then(function () { button.disabled = false; button.textContent = label; });
    });
  });
})();
