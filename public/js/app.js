(function () {
  const $ = (sel, root=document) => root.querySelector(sel);
  const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));

  function setActiveNav() {
    const page = document.body.getAttribute("data-page");
    $$(".menu a").forEach(a => {
      a.classList.toggle("active", a.getAttribute("data-link") === page);
    });
  }

  function toast(title, message) {
    const t = $("#toast");
    if (!t) return;
    $("#toastTitle").textContent = title;
    $("#toastMsg").textContent = message;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 3000);
  }

  // ---- Login ----
  const loginForm = $("#loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = $("#email").value.trim();
      const pass = $("#password").value.trim();
      if (!email || !pass) {
        toast("Campos requeridos", "Ingresa tu correo institucional y tu contraseña.");
        return;
      }
      localStorage.setItem("ep_user_name", "Juan Pablo");
      localStorage.setItem("ep_user_email", email);
      window.location.href = "dashboard.html";
    });
  }

  // ---- Dashboard ----
  const nameEl = $("#userName");
  if (nameEl) nameEl.textContent = localStorage.getItem("ep_user_name") || "Juan Pablo";

  // ---- Bitácoras ----
  const tbody = $("#bitacorasBody");
  if (tbody && window.BITACORAS) {
    tbody.innerHTML = "";
    window.BITACORAS.forEach((b) => {
      const tr = document.createElement("tr");
      const cls =
        b.estado === "Aprobado" ? "pill--ok" :
        b.estado === "Enviado" ? "pill--sent" : "pill--draft";

      tr.innerHTML = `
        <td>${b.numero}</td>
        <td>${b.fecha}</td>
        <td><span class="pill ${cls}">${b.estado}</span></td>
        <td>
          <div class="actions">
            <button class="icon-btn" type="button" data-action="Ver" aria-label="Ver">
              <svg aria-hidden="true"><use href="#icon-eye"></use></svg>
            </button>
            <button class="icon-btn" type="button" data-action="Descargar" aria-label="Descargar">
              <svg aria-hidden="true"><use href="#icon-download"></use></svg>
            </button>
            <button class="icon-btn" type="button" data-action="Eliminar" aria-label="Eliminar">
              <svg aria-hidden="true"><use href="#icon-trash"></use></svg>
            </button>
          </div>
        </td>
      `;
      tr.querySelectorAll("button").forEach(btn => {
        btn.addEventListener("click", () => {
          toast(btn.dataset.action, `${btn.dataset.action} ${b.numero} (demo)`);
        });
      });
      tbody.appendChild(tr);
    });
  }

  const newBitBtn = $("#newBitacora");
  if (newBitBtn) {
    newBitBtn.addEventListener("click", () => {
      toast("Nueva Bitácora", "Acción simulada: crear una nueva bitácora.");
    });
  }

  // ---- Evaluación ----
  const evalForm = $("#evalForm");
  if (evalForm) {
    evalForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const groups = Array.from(new Set($$("input[type=radio]").map(i => i.name))).filter(Boolean);

      const missing = groups.filter(name => !$(`input[name="${name}"]:checked`));
      if (missing.length) {
        toast("Faltan respuestas", "Selecciona una opción en cada criterio antes de enviar.");
        return;
      }
      const payload = {};
      groups.forEach(name => payload[name] = $(`input[name="${name}"]:checked`).value);
      payload.comentarios = $("#comments") ? $("#comments").value.trim() : "";
      payload.fecha = new Date().toISOString().slice(0,10);
      localStorage.setItem("ep_last_eval", JSON.stringify(payload));
      toast("Evaluación enviada", "Tu evaluación final fue enviada correctamente. (Demo)");
      setTimeout(() => window.location.href = "dashboard.html", 900);
    });
  }

  setActiveNav();
})();
