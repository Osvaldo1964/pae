/**
 * PAE Programs Management View
 * Allows Super Admin to manage PAE programs (CRUD)
 * Including logo uploads and program details
 */

const PaeProgramsView = {
    programs: [],

    /**
     * Render the view
     */
    render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2><i class="fas fa-building me-2"></i>Programas PAE</h2>
                                <p class="text-muted">Gestión de entidades y operadores del programa</p>
                            </div>
                            <button class="btn btn-success" onclick="PaeProgramsView.openModal()">
                                <i class="fas fa-plus me-2"></i>Nuevo Programa
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="paeTable" class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Programa</th>
                                                <th>Entidad</th>
                                                <th>Operador</th>
                                                <th>Ubicación</th>
                                                <th>Logos</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pae-table-body">
                                            <!-- Data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: Create/Edit PAE -->
            <div class="modal fade" id="modalPae" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalPaeTitle">
                                <i class="fas fa-building me-2"></i>Nuevo Programa PAE
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formPae" enctype="multipart/form-data">
                                <input type="hidden" id="pae-id">
                                
                                <!-- Información del Programa -->
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Información del Programa
                                </h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre del Programa *</label>
                                        <input type="text" class="form-control" id="pae-name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email de Contacto</label>
                                        <input type="email" class="form-control" id="pae-email">
                                    </div>
                                </div>

                                <!-- Datos de la Entidad -->
                                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-landmark me-2"></i>Datos de la Entidad Territorial
                                </h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre Entidad *</label>
                                        <input type="text" class="form-control" id="entity-name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">NIT Entidad *</label>
                                        <input type="text" class="form-control" id="entity-nit" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Logo Entidad</label>
                                        <input type="file" class="form-control" id="entity-logo" accept="image/*">
                                        <small class="text-muted" id="entity-logo-current"></small>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Departamento</label>
                                        <input type="text" class="form-control" id="entity-department">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Ciudad</label>
                                        <input type="text" class="form-control" id="entity-city">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="entity-address">
                                    </div>
                                </div>

                                <!-- Datos del Operador -->
                                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-briefcase me-2"></i>Datos del Operador
                                </h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Razón Social Operador *</label>
                                        <input type="text" class="form-control" id="operator-name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">NIT Operador *</label>
                                        <input type="text" class="form-control" id="operator-nit" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Logo Operador</label>
                                        <input type="file" class="form-control" id="operator-logo" accept="image/*">
                                        <small class="text-muted" id="operator-logo-current"></small>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="operator-address">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="operator-phone">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="operator-email">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="PaeProgramsView.save()">
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
        await this.loadPrograms();
    },

    /**
     * Load all PAE programs
     */
    async loadPrograms() {
        console.log("PaeProgramsView: Loading programs...");
        try {
            const response = await fetch(`${Config.API_URL}/tenant/list`, {
                headers: Config.getHeaders()
            });

            if (!response.ok) {
                console.error("PaeProgramsView: Server error", response.status);
                throw new Error(`Server returned ${response.status}`);
            }

            const data = await response.json();
            console.log("PaeProgramsView: Programs loaded", data);

            if (Array.isArray(data)) {
                this.programs = data;
                this.renderTable();
            } else {
                Swal.fire('Error', 'Error al cargar programas', 'error');
            }
        } catch (error) {
            console.error('Error loading programs:', error);
            Swal.fire('Error', 'Error al cargar programas', 'error');
        }
    },

    /**
     * Render programs table
     */
    renderTable() {
        const tbody = document.getElementById('pae-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (this.programs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No se encontraron programas registrados</td></tr>';
            return;
        }

        this.programs.forEach(pae => {
            const entityUrl = pae.entity_logo_path ? `/pae/${pae.entity_logo_path.replace(/^assets\//, '')}` : '/pae/app/assets/img/default_entity.png';
            const entityLogo = `<img src="${entityUrl}" alt="Entidad" style="height: 30px;" onerror="this.onerror=null; this.src='/pae/app/assets/img/default_entity.png'">`;

            const operatorUrl = pae.operator_logo_path ? `/pae/${pae.operator_logo_path.replace(/^assets\//, '')}` : '/pae/app/assets/img/default_operator.png';
            const operatorLogo = `<img src="${operatorUrl}" alt="Operador" style="height: 30px;" onerror="this.onerror=null; this.src='/pae/app/assets/img/default_operator.png'">`;

            tbody.innerHTML += `
                <tr>
                    <td>
                        <strong>${pae.name}</strong><br>
                        <small class="text-muted">ID: ${pae.id}</small>
                    </td>
                    <td>
                        ${pae.entity_name}<br>
                        <small class="text-muted">NIT: ${pae.nit || '-'}</small>
                    </td>
                    <td>
                        ${pae.operator_name || '-'}<br>
                        <small class="text-muted">NIT: ${pae.operator_nit || '-'}</small>
                    </td>
                    <td>
                        ${pae.city || '-'}, ${pae.department || '-'}
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            ${entityLogo}
                            ${operatorLogo}
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-primary" onclick='PaeProgramsView.openModal(${JSON.stringify(pae)})'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="PaeProgramsView.delete(${pae.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        // Initialize DataTable
        if ($.fn.DataTable.isDataTable('#paeTable')) {
            $('#paeTable').DataTable().destroy();
        }
        Helper.initDataTable('#paeTable');
    },

    /**
     * Open modal for create/edit
     */
    openModal(pae = null) {
        const modal = new bootstrap.Modal(document.getElementById('modalPae'));
        const isEdit = !!pae;

        // Reset form
        document.getElementById('formPae').reset();
        document.getElementById('entity-logo-current').textContent = '';
        document.getElementById('operator-logo-current').textContent = '';

        if (isEdit) {
            document.getElementById('modalPaeTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Programa PAE';
            document.getElementById('pae-id').value = pae.id;
            document.getElementById('pae-name').value = pae.name || '';
            document.getElementById('pae-email').value = pae.email || '';
            document.getElementById('entity-name').value = pae.entity_name || '';
            document.getElementById('entity-nit').value = pae.nit || '';
            document.getElementById('entity-department').value = pae.department || '';
            document.getElementById('entity-city').value = pae.city || '';
            document.getElementById('entity-address').value = pae.address || '';
            document.getElementById('operator-name').value = pae.operator_name || '';
            document.getElementById('operator-nit').value = pae.operator_nit || '';
            document.getElementById('operator-address').value = pae.operator_address || '';
            document.getElementById('operator-phone').value = pae.operator_phone || '';
            document.getElementById('operator-email').value = pae.operator_email || '';

            if (pae.entity_logo_path) {
                document.getElementById('entity-logo-current').textContent = `Actual: ${pae.entity_logo_path.split('/').pop()}`;
            }
            if (pae.operator_logo_path) {
                document.getElementById('operator-logo-current').textContent = `Actual: ${pae.operator_logo_path.split('/').pop()}`;
            }
        } else {
            document.getElementById('modalPaeTitle').innerHTML = '<i class="fas fa-plus me-2"></i>Nuevo Programa PAE';
        }

        modal.show();
    },

    /**
     * Save PAE program
     */
    async save() {
        const paeId = document.getElementById('pae-id').value;
        const isEdit = !!paeId;

        // Validation
        if (!document.getElementById('pae-name').value ||
            !document.getElementById('entity-name').value ||
            !document.getElementById('entity-nit').value ||
            !document.getElementById('operator-name').value ||
            !document.getElementById('operator-nit').value) {
            Swal.fire('Error', 'Complete los campos obligatorios', 'warning');
            return;
        }

        // Create FormData
        const formData = new FormData();
        formData.append('name', document.getElementById('pae-name').value);
        formData.append('email', document.getElementById('pae-email').value);
        formData.append('entity_name', document.getElementById('entity-name').value);
        formData.append('nit', document.getElementById('entity-nit').value);
        formData.append('department', document.getElementById('entity-department').value);
        formData.append('city', document.getElementById('entity-city').value);
        formData.append('address', document.getElementById('entity-address').value);
        formData.append('operator_name', document.getElementById('operator-name').value);
        formData.append('operator_nit', document.getElementById('operator-nit').value);
        formData.append('operator_address', document.getElementById('operator-address').value);
        formData.append('operator_phone', document.getElementById('operator-phone').value);
        formData.append('operator_email', document.getElementById('operator-email').value);

        // Add logos if selected
        const entityLogo = document.getElementById('entity-logo').files[0];
        const operatorLogo = document.getElementById('operator-logo').files[0];
        if (entityLogo) formData.append('entity_logo', entityLogo);
        if (operatorLogo) formData.append('operator_logo', operatorLogo);

        try {
            const url = isEdit ? `${Config.API_URL}/tenant/update/${paeId}` : `${Config.API_URL}/tenant/register`;
            const method = 'POST'; // Use POST for both create and update to support FormData/Files in PHP

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Authorization': `Bearer ${Config.getToken()}`
                },
                body: formData
            });

            const data = await response.json();

            if (data.success || response.ok) {
                Swal.fire('Éxito', isEdit ? 'Programa actualizado' : 'Programa creado', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalPae')).hide();
                await this.loadPrograms();
            } else {
                Swal.fire('Error', data.message || 'Error al guardar', 'error');
            }
        } catch (error) {
            console.error('Error saving PAE:', error);
            Swal.fire('Error', 'Error al guardar programa', 'error');
        }
    },

    /**
     * Delete PAE program
     */
    async delete(id) {
        const result = await Swal.fire({
            title: '¿Eliminar programa?',
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
            const response = await fetch(`${Config.API_URL}/tenant/delete/${id}`, {
                method: 'DELETE',
                headers: Config.getHeaders()
            });

            const data = await response.json();

            if (data.success || response.ok) {
                Swal.fire('Eliminado', 'Programa eliminado exitosamente', 'success');
                await this.loadPrograms();
            } else {
                Swal.fire('Error', data.message || 'Error al eliminar', 'error');
            }
        } catch (error) {
            console.error('Error deleting PAE:', error);
            Swal.fire('Error', 'Error al eliminar programa', 'error');
        }
    }
};

// Auto-execute
PaeProgramsView.render();
