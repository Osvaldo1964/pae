/**
 * Roles and Permissions View
 * Manages role permissions with multitenancy support
 * 
 * Business Rules:
 * - Super Admin: Full CRUD on roles + global permissions
 * - PAE Admin: Can only assign/deny permissions for their PAE
 */

const RolesPermissionsView = {
    roles: [],
    modules: [],
    selectedRole: null,
    permissions: {},
    canModifyRoles: false,
    isPaeAdmin: false,

    /**
     * Render the view
     */
    render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2><i class="fas fa-shield-alt me-2"></i>Roles y Permisos</h2>
                        <p class="text-muted">Gestión de permisos por rol${this.isPaeAdmin ? ' (Solo para tu programa PAE)' : ''}</p>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Roles</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3" id="role-buttons-container">
                                    <!-- Role buttons will be inserted here -->
                                </div>
                                <div id="role-actions" style="display: none;">
                                    <button class="btn btn-success btn-sm" id="btn-new-role">
                                        <i class="fas fa-plus me-1"></i>Nuevo Rol
                                    </button>
                                    <button class="btn btn-danger btn-sm" id="btn-delete-role" disabled>
                                        <i class="fas fa-trash me-1"></i>Eliminar Rol
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información</h5>
                            </div>
                            <div class="card-body">
                                <div id="role-info">
                                    <p class="text-muted">Selecciona un rol para ver y editar sus permisos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Matrix -->
                <div class="row" id="permissions-container" style="display: none;">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Matriz de Permisos</h5>
                            </div>
                            <div class="card-body">
                                <div id="permissions-matrix">
                                    <!-- Matrix will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: New Role -->
            <div class="modal fade" id="modalNewRole" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Nuevo Rol</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formNewRole">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Rol *</label>
                                    <input type="text" class="form-control" id="role-name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" id="role-description" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success" id="btn-save-role">
                                <i class="fas fa-save me-1"></i>Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('app-container').innerHTML = html;
        this.init();
    },

    /**
     * Initialize the view
     */
    async init() {
        await this.loadRoles();
        await this.loadModules();
        this.setupEventListeners();
    },

    /**
     * Load all roles
     */
    async loadRoles() {
        try {
            const response = await fetch(`${Config.API_URL}/permissions/roles`, {
                headers: Config.getHeaders()
            });

            const data = await response.json();

            if (data.success) {
                this.roles = data.data;
                this.canModifyRoles = data.can_modify_roles;
                this.isPaeAdmin = !data.can_modify_roles;
                this.renderRoleButtons();

                // Show/hide role management buttons
                if (this.canModifyRoles) {
                    document.getElementById('role-actions').style.display = 'block';
                }
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading roles:', error);
            Swal.fire('Error', 'Error al cargar roles', 'error');
        }
    },

    /**
     * Load all modules
     */
    async loadModules() {
        try {
            const response = await fetch(`${Config.API_URL}/permissions/modules`, {
                headers: Config.getHeaders()
            });

            const data = await response.json();

            if (data.success) {
                this.modules = data.data;
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading modules:', error);
            Swal.fire('Error', 'Error al cargar módulos', 'error');
        }
    },

    /**
     * Render role buttons
     */
    renderRoleButtons() {
        const container = document.getElementById('role-buttons-container');
        container.innerHTML = this.roles.map(role => `
            <button class="btn btn-outline-primary w-100 mb-2 role-btn" data-role-id="${role.id}">
                <i class="fas fa-user-tag me-2"></i>${role.name}
            </button>
        `).join('');
    },

    /**
     * Load permissions for a specific role
     */
    async loadPermissions(roleId) {
        try {
            const response = await fetch(`${Config.API_URL}/permissions/matrix/${roleId}`, {
                headers: Config.getHeaders()
            });

            const data = await response.json();

            if (data.success) {
                this.permissions = {};
                data.data.forEach(perm => {
                    this.permissions[perm.module_id] = {
                        can_create: parseInt(perm.can_create),
                        can_read: parseInt(perm.can_read),
                        can_update: parseInt(perm.can_update),
                        can_delete: parseInt(perm.can_delete)
                    };
                });
                this.renderPermissionsMatrix();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error loading permissions:', error);
            Swal.fire('Error', 'Error al cargar permisos', 'error');
        }
    },

    /**
     * Render permissions matrix
     */
    renderPermissionsMatrix() {
        const container = document.getElementById('permissions-matrix');

        let html = '<div class="table-responsive">';

        this.modules.forEach(group => {
            html += `
                <h5 class="mt-4 mb-3">
                    <i class="${group.icon} me-2"></i>${group.name}
                </h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Módulo</th>
                            <th class="text-center" style="width: 15%">
                                <i class="fas fa-plus-circle text-success"></i> Crear
                            </th>
                            <th class="text-center" style="width: 15%">
                                <i class="fas fa-eye text-info"></i> Ver
                            </th>
                            <th class="text-center" style="width: 15%">
                                <i class="fas fa-edit text-warning"></i> Editar
                            </th>
                            <th class="text-center" style="width: 15%">
                                <i class="fas fa-trash text-danger"></i> Eliminar
                            </th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            group.modules.forEach(module => {
                const perms = this.permissions[module.id] || {
                    can_create: 0,
                    can_read: 0,
                    can_update: 0,
                    can_delete: 0
                };

                html += `
                    <tr>
                        <td>
                            <strong>${module.name}</strong><br>
                            <small class="text-muted">${module.description}</small>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input perm-checkbox" 
                                   data-module-id="${module.id}" 
                                   data-permission="can_create"
                                   ${perms.can_create ? 'checked' : ''}>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input perm-checkbox" 
                                   data-module-id="${module.id}" 
                                   data-permission="can_read"
                                   ${perms.can_read ? 'checked' : ''}>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input perm-checkbox" 
                                   data-module-id="${module.id}" 
                                   data-permission="can_update"
                                   ${perms.can_update ? 'checked' : ''}>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input perm-checkbox" 
                                   data-module-id="${module.id}" 
                                   data-permission="can_delete"
                                   ${perms.can_delete ? 'checked' : ''}>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;
        });

        html += '</div>';
        container.innerHTML = html;

        // Add event listeners to checkboxes
        document.querySelectorAll('.perm-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updatePermission(
                    e.target.dataset.moduleId,
                    e.target.dataset.permission,
                    e.target.checked
                );
            });
        });

        document.getElementById('permissions-container').style.display = 'block';
    },

    /**
     * Update a single permission
     */
    async updatePermission(moduleId, permission, value) {
        try {
            // Update local state
            if (!this.permissions[moduleId]) {
                this.permissions[moduleId] = {
                    can_create: 0,
                    can_read: 0,
                    can_update: 0,
                    can_delete: 0
                };
            }
            this.permissions[moduleId][permission] = value ? 1 : 0;

            // Send to backend
            const response = await fetch(`${Config.API_URL}/permissions/update`, {
                method: 'PUT',
                headers: Config.getHeaders(),
                body: JSON.stringify({
                    role_id: this.selectedRole,
                    module_id: moduleId,
                    permissions: this.permissions[moduleId]
                })
            });

            const data = await response.json();

            if (!data.success) {
                Swal.fire('Error', data.message, 'error');
                // Revert checkbox
                const checkbox = document.querySelector(
                    `[data-module-id="${moduleId}"][data-permission="${permission}"]`
                );
                if (checkbox) {
                    checkbox.checked = !value;
                }
            }
        } catch (error) {
            console.error('Error updating permission:', error);
            Swal.fire('Error', 'Error al actualizar permiso', 'error');
        }
    },

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Role selection
        document.addEventListener('click', (e) => {
            if (e.target.closest('.role-btn')) {
                const roleId = e.target.closest('.role-btn').dataset.roleId;
                this.selectRole(roleId);
            }
        });

        // New role button
        const btnNewRole = document.getElementById('btn-new-role');
        if (btnNewRole) {
            btnNewRole.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('modalNewRole'));
                modal.show();
            });
        }

        // Save role button
        const btnSaveRole = document.getElementById('btn-save-role');
        if (btnSaveRole) {
            btnSaveRole.addEventListener('click', () => this.createRole());
        }

        // Delete role button
        const btnDeleteRole = document.getElementById('btn-delete-role');
        if (btnDeleteRole) {
            btnDeleteRole.addEventListener('click', () => this.deleteRole());
        }
    },

    /**
     * Select a role
     */
    selectRole(roleId) {
        this.selectedRole = roleId;

        // Update UI
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-role-id="${roleId}"]`).classList.add('active');

        // Update role info
        const role = this.roles.find(r => r.id == roleId);
        document.getElementById('role-info').innerHTML = `
            <h5>${role.name}</h5>
            <p class="text-muted">${role.description || 'Sin descripción'}</p>
        `;

        // Enable delete button (except for SUPER_ADMIN)
        const btnDelete = document.getElementById('btn-delete-role');
        if (btnDelete && roleId != 1) {
            btnDelete.disabled = false;
        }

        // Load permissions
        this.loadPermissions(roleId);
    },

    /**
     * Create a new role
     */
    async createRole() {
        const name = document.getElementById('role-name').value.trim();
        const description = document.getElementById('role-description').value.trim();

        if (!name) {
            Swal.fire('Error', 'El nombre del rol es requerido', 'error');
            return;
        }

        try {
            const response = await fetch(`${Config.API_URL}/permissions/roles`, {
                method: 'POST',
                headers: Config.getHeaders(),
                body: JSON.stringify({ name, description })
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire('Éxito', 'Rol creado exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalNewRole')).hide();
                document.getElementById('formNewRole').reset();
                await this.loadRoles();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error creating role:', error);
            Swal.fire('Error', 'Error al crear rol', 'error');
        }
    },

    /**
     * Delete a role
     */
    async deleteRole() {
        if (!this.selectedRole || this.selectedRole == 1) {
            return;
        }

        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`${Config.API_URL}/permissions/roles/${this.selectedRole}`, {
                method: 'DELETE',
                headers: Config.getHeaders()
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire('Éxito', 'Rol eliminado exitosamente', 'success');
                this.selectedRole = null;
                document.getElementById('permissions-container').style.display = 'none';
                document.getElementById('role-info').innerHTML = '<p class="text-muted">Selecciona un rol para ver y editar sus permisos</p>';
                await this.loadRoles();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error deleting role:', error);
            Swal.fire('Error', 'Error al eliminar rol', 'error');
        }
    }
};

// Auto-execute
RolesPermissionsView.render();
