// Interaksi kecil Bari Coffee: animasi muncul saat digulir dan notifikasi "ditambahkan".
(function () {
  var reduce = window.matchMedia && matchMedia("(prefers-reduced-motion: reduce)").matches;

  // Muncul saat digulir. Hanya aktif bila didukung; tanpa JS semua konten tetap terlihat.
  if (!reduce && "IntersectionObserver" in window) {
    var targets = document.querySelectorAll(
      ".hero > *, .featured > *, .signature > *, .menu-head, .item, .perks > div, .about > *, .cta-block, footer > div:not(.copy), .stat, .dash-grid > .panel"
    );
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add("in"); io.unobserve(e.target); }
      });
    }, { rootMargin: "0px 0px -8% 0px", threshold: 0.08 });
    targets.forEach(function (el, i) {
      el.classList.add("reveal");
      el.style.transitionDelay = (i % 4) * 70 + "ms";
      io.observe(el);
    });
  }

  // Notifikasi singkat di bawah layar
  var toast, timer;
  window.bariToast = function (text) {
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "toast";
      toast.setAttribute("role", "status");
      toast.setAttribute("aria-live", "polite");
      toast.innerHTML = '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 9h12v4a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5V9Z"/><path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/></svg><span></span>';
      document.body.appendChild(toast);
    }
    toast.querySelector("span").textContent = text;
    toast.classList.add("show");
    clearTimeout(timer);
    timer = setTimeout(function () { toast.classList.remove("show"); }, 2200);
  };

  // Efek "melompat" sekali pada elemen (mis. tombol keranjang)
  window.bariBump = function (el) {
    if (!el || reduce) return;
    el.classList.remove("bump");
    void el.offsetWidth; // mulai ulang animasi
    el.classList.add("bump");
  };
})();
