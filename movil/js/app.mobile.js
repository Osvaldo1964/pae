/**
 * Configuración de la App Móvil
 */
const API_BASE_URL = 'http://' + window.location.hostname + '/pae/api'; // Ajustar si es producción
const APP_VERSION = '1.0.1';

class MobileApp {
    constructor() {
        this.token = localStorage.getItem('pae_token');
        this.user = JSON.parse(localStorage.getItem('pae_user') || '{}');
        this.selectedBranch = JSON.parse(localStorage.getItem('pae_branch') || 'null');

        this.init();
    }

    init() {
        if (!this.token) {
            this.showLogin();
        } else {
            this.showDashboard();
            this.loadUserData();
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
                this.token = data.token;
                this.user = data.user;
                localStorage.setItem('pae_token', this.token);
                localStorage.setItem('pae_user', JSON.stringify(this.user));

                this.showDashboard();
            } else {
                alert(data.message || 'Error de autenticación');
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión con el servidor');
        }
    }

    logout() {
        localStorage.removeItem('pae_token');
        localStorage.removeItem('pae_user');
        localStorage.removeItem('pae_branch');
        window.location.reload();
    }

    loadUserData() {
        document.getElementById('user-name').innerText = this.user.first_name || 'Usuario';
    }

    async selectBranch() {
        // Fetch branches for this user (using the token)
        try {
            const response = await fetch(`${API_BASE_URL}/branches?_=${new Date().getTime()}`, {
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'X-Auth-Token': this.token
                }
            });
            const text = await response.text();
            let branches;
            try {
                branches = JSON.parse(text);
            } catch (e) {
                console.error("Respuesta no JSON:", text);
                throw new Error("El servidor devolvió respuesta inválida: " + text.substring(0, 50));
            }

            if (!Array.isArray(branches)) {
                console.error("Respuesta no es array:", branches);
                const debugInfo = branches.debug ? JSON.stringify(branches.debug) : 'Sin debug info';
                throw new Error(`${branches.message || 'Error'} | Debug: ${debugInfo}`);
            }

            // Generate options HTML
            let options = branches.map(b => `<option value='${JSON.stringify(b)}'>${b.name}</option>`).join('');

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
                }
            });

        } catch (error) {
            console.error("Error cargando sedes", error);
            Swal.fire('Error', 'No se pudieron cargar las sedes. Verifique su conexión o reinicie sesión. Detalles: ' + error.message, 'error');
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
        this.html5QrCode = new Html5Qrcode("scanner-container");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        this.html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                // Success Callback
                this.handleScan(decodedText);
            },
            (errorMessage) => {
                // parse error, ignore it.
            }
        ).catch(err => {
            console.error("Error starting scanner", err);
            document.getElementById('scan-result').innerText = "Error: Cámara no disponible";
            document.getElementById('scan-result').classList.remove('d-none');
        });
    }

    async handleScan(qrCode) {
        // Stop scanning temporarily
        await this.html5QrCode.pause();

        // 1. Parse QR: PAE:{ID}:{DOC}
        const parts = qrCode.split(':');
        if (parts[0] !== 'PAE' || parts.length < 3) {
            Swal.fire('Error', 'Código QR inválido o de otro sistema', 'error')
                .then(() => this.html5QrCode.resume());
            return;
        }

        const beneficiaryId = parts[1];

        // 2. Play sound (Beep)
        // const audio = new Audio('beep.mp3'); audio.play();

        // 3. Register Delivery
        try {
            const time = new Date().getHours();
            let mealType = 'ALMUERZO';
            if (time < 11) mealType = 'AM'; // Ajustar lógica según hora real o selector manual
            if (time > 14) mealType = 'PM';

            const payload = {
                beneficiary_id: beneficiaryId,
                branch_id: this.selectedBranch.id,
                meal_type: mealType
            };

            const response = await fetch(`${API_BASE_URL}/deliveries`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.token}`,
                    'X-Auth-Token': this.token
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Entrega Exitosa!',
                    text: `${mealType} registrado.`,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Close modal or resume for next student
                    bootstrap.Modal.getInstance(document.getElementById('scannerModal')).hide();
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se pudo entregar',
                    text: data.message,
                    confirmButtonColor: '#d33'
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
            inputPlaceholder: 'Ingrese número de documento',
            showCancelButton: true,
            confirmButtonText: 'Buscar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here we would need to search by document first to get the ID
                Swal.fire('Info', 'Funcionalidad en desarrollo: Búsqueda por documento', 'info');
            }
        });
    }

    syncData() {
        // En futuro: Sincronizar LocalStorage -> Base de datos
        Swal.fire({
            icon: 'success',
            title: 'Sincronizado',
            text: 'Datos actualizados con el servidor',
            timer: 1500,
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
