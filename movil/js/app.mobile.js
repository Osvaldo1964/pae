/**
 * Configuración de la App Móvil
 */
// Calculate API URL dynamically based on current location
// If we are at /pae/movil/index.html, we want /pae/api
// If we are at /movil/index.html, we want /api
const API_BASE_URL = window.location.pathname.includes('/pae/')
    ? window.location.origin + '/pae/api'
    : window.location.origin + '/api';
const APP_VERSION = '1.0.2';

class MobileApp {
    constructor() {
        const t = localStorage.getItem('pae_token');
        this.token = (t && t !== 'undefined' && t !== 'null') ? t : null;

        try {
            const u = localStorage.getItem('pae_user');
            this.user = (u && u !== 'undefined') ? JSON.parse(u) : {};
        } catch (e) { this.user = {}; }

        try {
            const b = localStorage.getItem('pae_branch');
            this.selectedBranch = (b && b !== 'undefined') ? JSON.parse(b) : null;
        } catch (e) { this.selectedBranch = null; }

        this.init();
    }

    init() {
        if (!this.token) {
            this.showLogin();
        } else {
            this.showDashboard();
            this.loadUserData();
            this.loadStats();
        }
    }

    showLogin() {
        document.getElementById('login-screen').classList.remove('d-none');
        document.getElementById('app-screen').classList.add('d-none');
    }

    showDashboard() {
        document.getElementById('login-screen').classList.add('d-none');
        document.getElementById('app-screen').classList.remove('d-none');

        if (this.selectedBranch) {
            document.getElementById('current-location').innerText = this.selectedBranch.name;
        } else {
            this.selectBranch(); // Force selection if none
        }
    }

    async login(email, password) {
        try {
            const response = await fetch(`${API_BASE_URL}/auth/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username: email, password })
            });

            const data = await response.json();

            if (response.ok) {
                if (data.select_tenant) {
                    Swal.fire('Atención', 'Usuario Global Admin: La selección de tenant no está soportada en móvil aún. Use un usuario de PAE específico.', 'warning');
                    return;
                }

                if (!data.token) {
                    Swal.fire('Error', 'Respuesta del servidor inválida (Sin token).', 'error');
                    return;
                }

                this.token = data.token;
                this.user = data.user;
                localStorage.setItem('pae_token', this.token);
                localStorage.setItem('pae_user', JSON.stringify(this.user));

                this.showDashboard();
                this.loadUserData();
                this.loadStats();
            } else {
                Swal.fire('Error', data.message || 'Error de autenticación', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
        }
    }

    logout() {
        localStorage.removeItem('pae_token');
        localStorage.removeItem('pae_user');
        localStorage.removeItem('pae_branch');
        window.location.reload();
    }

    loadUserData() {
        document.getElementById('user-name').innerText = this.user.full_name?.split(' ')[0] || 'Usuario';
    }

    async loadStats() {
        if (!this.selectedBranch) return;

        try {
            const response = await fetch(`${API_BASE_URL}/consumptions/stats?branch_id=${this.selectedBranch.id}`, {
                headers: { 'Authorization': `Bearer ${this.token}` }
            });

            if (response.ok) {
                const data = await response.json();
                // Update specific elements if they existed, for now we update generic stats
                const statElements = document.querySelectorAll('.action-card h2');
                if (statElements.length >= 2) {
                    statElements[0].innerText = data.today_count || 0;
                    statElements[1].innerText = (data.progress || 0) + '%';
                }
            }
        } catch (error) {
            console.error("Error loading stats", error);
        }
    }

    async selectBranch() {
        try {
            const response = await fetch(`${API_BASE_URL}/branches`, {
                headers: { 'Authorization': `Bearer ${this.token}` }
            });

            if (response.status === 401 || response.status === 403) {
                this.logout();
                return;
            }

            if (!response.ok) throw new Error('Error cargando sedes');

            const branches = await response.json(); // Assuming standard API wrapper

            // Unwrap if needed (standard API returns {success:true, data: []} usually, OR just array)
            const list = Array.isArray(branches) ? branches : (branches.data || []);

            if (list.length === 0) {
                Swal.fire('Error', 'No tiene sedes asignadas o disponibles.', 'error');
                return;
            }

            let options = list.map(b => `<option value='${JSON.stringify(b)}'>${b.name}</option>`).join('');

            Swal.fire({
                title: 'Seleccionar Sede',
                html: `<select id="swal-branch-select" class="form-select">${options}</select>`,
                confirmButtonText: 'Confirmar',
                allowOutsideClick: false,
                preConfirm: () => {
                    const selected = document.getElementById('swal-branch-select').value;
                    return JSON.parse(selected);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.selectedBranch = result.value;
                    localStorage.setItem('pae_branch', JSON.stringify(this.selectedBranch));
                    document.getElementById('current-location').innerText = this.selectedBranch.name;
                    this.loadStats();
                }
            });

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'No se pudieron cargar las sedes.', 'error');
        }
    }

    openScanner() {
        if (!this.selectedBranch) {
            Swal.fire('Atención', 'Seleccione una sede primero', 'warning').then(() => this.selectBranch());
            return;
        }

        const modalEl = document.getElementById('scannerModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        // Wait for modal transition
        setTimeout(() => {
            this.startScanner();
        }, 500);

        // Stop scanner on close
        modalEl.addEventListener('hidden.bs.modal', () => {
            if (this.html5QrCode) {
                this.html5QrCode.stop().then(() => {
                    this.html5QrCode.clear();
                }).catch(err => console.error(err));
            }
        });
    }

    startScanner() {
        // Stop any previous instance
        if (this.html5QrCode) {
            try { this.html5QrCode.clear(); } catch (e) { }
        }

        // Check for HTTPS/Secure Context
        if (!window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
            Swal.fire({
                icon: 'error',
                title: 'Requiere HTTPS',
                html: 'El navegador bloquea la cámara en sitios no seguros (HTTP).<br><br><b>Solución:</b><br>1. Use HTTPS o localhost.<br>2. O en Chrome móvil vaya a: <code>chrome://flags/#unsafely-treat-insecure-origin-as-secure</code> y agregue esta IP.',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        this.html5QrCode = new Html5Qrcode("scanner-container");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        this.html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                this.handleScan(decodedText);
            },
            (errorMessage) => {
                // ignore
            }
        ).catch(err => {
            console.error("Error starting scanner", err);
            let msg = "Error: Cámara no disponible (" + err + ")";
            if (err.toString().includes("streaming not supported") || err.name === "NotAllowedError") {
                msg = "<b>Permiso denegado o no seguro.</b><br>Verifique permisos de cámara y use HTTPS.";
            }
            document.getElementById('scan-result').innerHTML = msg;
            document.getElementById('scan-result').classList.remove('d-none');
        });
    }

    async handleScan(qrCode) {
        // Stop scanning temporarily
        await this.html5QrCode.pause();

        // Format is expected to be PAE:{ID}:{DOC}
        const parts = qrCode.split(':');
        // Validate format
        if (parts[0] !== 'PAE' || parts.length < 3) {
            // Fallback: If it's just numbers, treat as document number?
            // For now strict QR check
            Swal.fire('Error', 'QR inválido. Use el carnet oficial.', 'error')
                .then(() => this.html5QrCode.resume());
            return;
        }

        const beneficiaryId = parts[1];

        try {
            const time = new Date().getHours();
            let mealType = 'ALMUERZO';
            if (time < 11) mealType = 'AM'; // Complemento AM
            if (time > 15) mealType = 'PM'; // Complemento PM / Cena logic

            // Prompt for meal type override? For speed, we auto-detect
            // Ideally we show a selector in the UI BEFORE scanning.

            const payload = {
                beneficiary_id: beneficiaryId,
                branch_id: this.selectedBranch.id,
                meal_type: mealType
            };

            const response = await fetch(`${API_BASE_URL}/consumptions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Entrega Registrada!',
                    html: `<b>${data.beneficiary_name || ''}</b><br>${mealType}`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    this.loadStats(); // Refresh stats
                    // Resume scanning seamlessly
                    this.html5QrCode.resume();
                });
            } else {
                // Determine if it's a duplicate or error
                const isDuplicate = response.status === 409;

                Swal.fire({
                    icon: isDuplicate ? 'warning' : 'error',
                    title: isDuplicate ? 'Ya entregado' : 'Error',
                    text: data.message,
                    confirmButtonColor: '#d33',
                    timer: 3000
                }).then(() => this.html5QrCode.resume());
            }

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Fallo de conexión', 'error').then(() => this.html5QrCode.resume());
        }
    }

    manualEntry() {
        Swal.fire({
            title: 'Búsqueda Manual',
            input: 'text',
            inputLabel: 'Documento del Estudiante',
            showCancelButton: true,
            confirmButtonText: 'Buscar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement manual lookup API here if needed
                Swal.fire('Info', 'La búsqueda manual estará disponible próximamente.', 'info');
            }
        });
    }

    syncData() {
        this.loadStats();
        Swal.fire({
            icon: 'success',
            title: 'Sincronizado',
            text: 'Datos actualizados.',
            timer: 1000,
            showConfirmButton: false
        });
    }
}

// Global Instance
window.app = new MobileApp();

// Login Form Handler
document.getElementById('login-form')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const pass = document.getElementById('password').value;
    window.app.login(email, pass);
});
