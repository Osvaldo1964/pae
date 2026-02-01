const App = {
    apiBase: '/pae/api',
    state: {
        user: null,
        token: localStorage.getItem('pae_token') || null,
        menu: []
    },

    init: async () => {
        if (App.state.token) {
            // Validate token or just load menu
            await App.loadMenu();
            if (window.location.hash === '' || window.location.hash === '#login') {
                window.location.hash = '#dashboard';
            }
        } else {
            window.location.hash = '#login';
        }

        window.addEventListener('hashchange', App.router);
        App.router();
    },

    router: async () => {
        const hash = window.location.hash.slice(1) || 'login';
        const appContainer = document.getElementById('app');

        // Layout handling
        if (hash === 'login') {
            document.getElementById('sidebar').classList.add('d-none');
            document.getElementById('main-content').classList.remove('col-md-9', 'col-lg-10');
            document.getElementById('main-content').classList.add('col-12');
            App.renderLogin();
        } else {
            if (!App.state.token) {
                window.location.hash = '#login';
                return;
            }

            document.getElementById('sidebar').classList.remove('d-none');
            // document.getElementById('main-content').classList.add('col-md-9', 'col-lg-10');

            // Render basic layout parts if needed
            App.renderSidebar();

            if (hash === 'dashboard') {
                // Default view (first group usually, or welcome)
                appContainer.innerHTML = '<h2>Bienvenido al Sistema PAE</h2><p class="text-muted">Seleccione una opción del menú para comenzar.</p>';
            } else if (hash.startsWith('group/')) {
                const groupId = hash.split('/')[1];
                App.renderGroupHub(groupId);
            } else {
                appContainer.innerHTML = `<h2>Módulo: ${hash}</h2><p>En construcción...</p>`;
            }
        }
    },

    api: async (endpoint, method = 'GET', body = null) => {
        const headers = { 'Content-Type': 'application/json' };
        if (App.state.token) {
            headers['Authorization'] = `Bearer ${App.state.token}`;
        }

        const options = { method, headers };
        if (body) options.body = JSON.stringify(body);

        try {
            const res = await fetch(`${App.apiBase}${endpoint}`, options);
            return await res.json();
        } catch (err) {
            console.error(err);
            return { error: true, message: 'Network Error' };
        }
    },

    login: async (username, password) => {
        const res = await App.api('/auth/login', 'POST', { username, password });
        if (res.token) {
            App.state.token = res.token;
            App.state.user = res.user;
            localStorage.setItem('pae_token', res.token);
            await App.loadMenu();
            window.location.hash = '#dashboard';
        } else {
            Swal.fire('Error', res.message || 'Login fallido', 'error');
        }
    },

    logout: () => {
        App.state.token = null;
        App.state.user = null;
        localStorage.removeItem('pae_token');
        window.location.hash = '#login';
    },

    loadMenu: async () => {
        const menu = await App.api('/auth/menu');
        if (Array.isArray(menu)) {
            App.state.menu = menu;
        }
    },

    renderLogin: () => {
        document.getElementById('app').innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-white text-center py-3 border-bottom-0">
                            <h4 class="text-primary-custom mb-3"><i class="fas fa-lock me-2"></i>Acceso Seguro</h4>
                            <img src="assets/img/logo_ovc.png" alt="OVCSYSTEMS" class="img-fluid mb-2" style="max-height: 120px;">
                        </div>
                        <div class="card-body p-4">
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label>Usuario</label>
                                    <input type="text" id="username" class="form-control" placeholder="admin" required>
                                </div>
                                <div class="mb-3">
                                    <label>Contraseña</label>
                                    <input type="password" id="password" class="form-control" placeholder="admin" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <small class="text-muted">PAE Control v1.0</small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('loginForm').onsubmit = (e) => {
            e.preventDefault();
            App.login(
                document.getElementById('username').value,
                document.getElementById('password').value
            );
        };
    },

    renderSidebar: () => {
        const sidebarList = document.getElementById('sidebar-list');
        sidebarList.innerHTML = '';

        App.state.menu.forEach(group => {
            const li = document.createElement('li');
            li.className = 'nav-item mb-2';
            li.innerHTML = `
                <a href="#group/${group.id}" class="nav-link text-white">
                    <i class="${group.icon} me-2"></i> ${group.name}
                </a>
            `;
            sidebarList.appendChild(li);
        });

        // Add Logout
        const logoutLi = document.createElement('li');
        logoutLi.className = 'nav-item mt-5 pt-3 border-top border-secondary';
        logoutLi.innerHTML = `
            <a href="#" class="nav-link text-danger" onclick="App.logout()">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </a>
        `;
        sidebarList.appendChild(logoutLi);
    },

    renderGroupHub: (groupId) => {
        const group = App.state.menu.find(g => g.id == groupId);
        if (!group) return;

        let cardsHtml = '';
        group.modules.forEach(mod => {
            cardsHtml += `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body text-center">
                            <div class="icon-circle mb-3 mx-auto">
                                <i class="${mod.icon} fa-2x text-primary"></i>
                            </div>
                            <h5 class="card-title">${mod.name}</h5>
                            <p class="card-text small text-muted">${mod.description || 'Acceso al módulo'}</p>
                            <a href="#module/${mod.route}" class="btn btn-outline-primary btn-sm stretched-link">Ingresar</a>
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('app').innerHTML = `
            <h3 class="mb-4 text-primary-custom border-bottom pb-2">
                <i class="${group.icon} me-2"></i>${group.name}
            </h3>
            <div class="row">
                ${cardsHtml}
            </div>
        `;
    }
};

// Initialize on load
document.addEventListener('DOMContentLoaded', App.init);
