/**
 * Salidas View - Módulo de Inventarios
 * Maneja las salidas de almacén hacia las sedes educativas basadas en proyecciones.
 */

window.SalidasView = {
    remissions: [],
    branches: [],
    items: [],
    cycles: [],

    async init() {
        console.log('Initializing Salidas Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [remRes, branchRes, itemRes, cycleRes] = await Promise.all([
                Helper.fetchAPI('/remissions'),
                Helper.fetchAPI('/branches'),
                Helper.fetchAPI('/items'),
                Helper.fetchAPI('/menu-cycles')
            ]);

            this.remissions = remRes.success ? (remRes.data || []).filter(r => r.type === 'SALIDA_SEDE') : (Array.isArray(remRes) ? remRes.filter(r => r.type === 'SALIDA_SEDE') : []);
            this.branches = branchRes.success ? (branchRes.data || []) : (Array.isArray(branchRes) ? branchRes : []);
            this.items = itemRes.success ? (itemRes.data || []) : (Array.isArray(itemRes) ? itemRes : []);
            this.cycles = cycleRes.success ? (cycleRes.data || []) : (Array.isArray(cycleRes) ? cycleRes : []);
        } catch (error) {
            console.error('Error loading remissions data:', error);
            Helper.alert('error', 'Error al cargar los datos de salidas');
        }
    },

    render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1 text-primary fw-bold"><i class="fas fa-truck-loading me-2"></i>Salidas de Almacén</h2>
                        <p class="text-muted mb-0">Control de entregas y despachos a sedes educativas</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="SalidasView.openModal()">
                        <i class="fas fa-plus me-1"></i> Nueva Salida
                    </button>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="salidas-table">
                                <thead class="bg-light">
                                    <tr class="text-muted small text-uppercase fw-bold">
                                        <th class="ps-4">No. Salida</th>
                                        <th>Sede Destino</th>
                                        <th>Fecha</th>
                                        <th>Conductor</th>
                                        <th>Placa</th>
                                        <th>Estado</th>
                                        <th class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${this.renderTableRows()}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const container = document.getElementById('app-container');
        if (container) {
            container.innerHTML = html;
            this.initTable();
        }
    },

    renderTableRows() {
        if (!this.remissions || this.remissions.length === 0) return '';

        return this.remissions.map(r => `
            <tr>
                <td class="ps-4 fw-bold text-dark">${r.remission_number || 'N/A'}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded-circle text-primary d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px">
                            <i class="fas fa-school small"></i>
                        </div>
                        <span class="fw-medium">${r.branch_name}</span>
                    </div>
                </td>
                <td>${r.remission_date}</td>
                <td>${r.carrier_name || '<span class="text-muted">-</span>'}</td>
                <td><span class="badge bg-light text-dark border">${r.vehicle_plate || '-'}</span></td>
                <td>
                    <span class="badge rounded-pill ${this.getStatusBadgeClass(r.status)}">${r.status}</span>
                </td>
                <td class="text-end pe-4">
                    <div class="btn-group shadow-sm rounded-3">
                        <button class="btn btn-white btn-sm" onclick="SalidasView.openModal(${r.id})" title="Ver/Editar"><i class="fas fa-edit text-primary"></i></button>
                        <button class="btn btn-white btn-sm" onclick="SalidasView.deleteRemission(${r.id})" title="Eliminar"><i class="fas fa-trash text-danger"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

    getStatusBadgeClass(status) {
        switch (status) {
            case 'ENTREGADA': return 'bg-success-soft text-success';
            case 'CON_NOVEDAD': return 'bg-danger-soft text-danger';
            case 'CAMINO': return 'bg-info-soft text-info';
            default: return 'bg-secondary-soft text-secondary';
        }
    },

    initTable() {
        if ($.fn.DataTable.isDataTable('#salidas-table')) {
            $('#salidas-table').DataTable().destroy();
        }
        $('#salidas-table').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' },
            pageLength: 10,
            ordering: true,
            columnDefs: [{ targets: 6, orderable: false }]
        });
    },

    async openModal(id = null) {
        const isEdit = !!id;
        const remission = isEdit ? this.remissions.find(r => r.id === id) : null;
        let remissionItems = [];

        if (isEdit) {
            try {
                const res = await Helper.fetchAPI(`/remissions/${id}/details`);
                remissionItems = res.success ? res.data : [];
            } catch (error) {
                console.error('Error fetching remission details:', error);
            }
        }

        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = 'salidaModal';

        const branchesHtml = this.branches.map(b => `<option value="${b.id}" ${remission && remission.branch_id == b.id ? 'selected' : ''}>${b.name}</option>`).join('');

        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="fas ${isEdit ? 'fa-edit' : 'fa-plus-circle'} me-2"></i>
                            ${isEdit ? 'Editar Salida' : 'Nueva Salida de Almacén'}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <form id="salida-form">
                            <div class="card border-0 shadow-sm mb-4 rounded-3">
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Ciclo de Menú</label>
                                            <select class="form-control form-select-lg border-2" id="msg-cycle" required onchange="SalidasView.handleHeaderChange()">
                                                <option value="">-- Seleccionar Ciclo --</option>
                                                ${this.cycles.map(c => `<option value="${c.id}" ${remission && remission.cycle_id == c.id ? 'selected' : ''}>${c.name}</option>`).join('')}
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Sede Destino</label>
                                            <select class="form-control form-select-lg border-2" id="msg-branch" required onchange="SalidasView.handleHeaderChange()">
                                                <option value="">-- Seleccionar Sede --</option>
                                                ${branchesHtml}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-muted text-uppercase">No. Salida</label>
                                            <input type="text" class="form-control form-control-lg border-2" id="msg-number" value="${remission ? remission.remission_number : ''}" placeholder="SAL-XXX" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Fecha Despacho</label>
                                            <input type="date" class="form-control form-control-lg border-2" id="msg-date" value="${remission ? remission.remission_date : new Date().toISOString().split('T')[0]}" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Conductor</label>
                                            <input type="text" class="form-control border-2" id="msg-carrier" value="${remission ? remission.carrier_name : ''}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Placa Vehículo</label>
                                            <input type="text" class="form-control border-2" id="msg-plate" value="${remission ? remission.vehicle_plate : ''}" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Estado</label>
                                            <select class="form-control border-2" id="msg-status">
                                                <option value="CAMINO" ${remission && remission.status === 'CAMINO' ? 'selected' : ''}>EN CAMINO</option>
                                                <option value="ENTREGADA" ${remission && remission.status === 'ENTREGADA' ? 'selected' : ''}>ENTREGADA</option>
                                                <option value="CON_NOVEDAD" ${remission && remission.status === 'CON_NOVEDAD' ? 'selected' : ''}>CON NOVEDAD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-boxes me-2 text-primary"></i>Ítems a Despachar</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="SalidasView.addItemRow()">
                                        <i class="fas fa-plus me-1"></i> Agregar Ítem
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light small text-uppercase">
                                                <tr>
                                                    <th class="ps-4">Ítem / Alimento</th>
                                                    <th style="width: 150px;">Proyectado</th>
                                                    <th style="width: 200px;">Lote (Opcional)</th>
                                                    <th style="width: 150px;">Cantidad</th>
                                                    <th class="text-end pe-4" style="width: 50px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="salida-items-body">
                                                <!-- Rows here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-white py-3">
                        <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary px-5 rounded-pill shadow" onclick="SalidasView.save(${remission ? remission.id : null})">
                            <i class="fas fa-save me-2"></i>${isEdit ? 'Actualizar Salida' : 'Guardar Salida'}
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();

        if (isEdit && remissionItems.length > 0) {
            remissionItems.forEach(item => this.addItemRow(item));
        } else {
            this.addItemRow();
        }

        modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
    },

    async handleHeaderChange() {
        const cycleId = document.getElementById('msg-cycle').value;
        const branchId = document.getElementById('msg-branch').value;

        if (cycleId && branchId) {
            const confirm = await Swal.fire({
                title: '¿Cargar proyecciones?',
                text: "Se cargarán los ítems proyectados para esta sede en el ciclo seleccionado.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, cargar'
            });

            if (confirm.isConfirmed) {
                try {
                    const res = await Helper.fetchAPI(`/inventory/branch-projections/${cycleId}/${branchId}`);
                    if (res.success && res.data.length > 0) {
                        document.getElementById('salida-items-body').innerHTML = '';
                        res.data.forEach(item => {
                            const pend = item.projected_qty - item.delivered_qty;
                            if (pend > 0) {
                                this.addItemRow({
                                    item_id: item.item_id,
                                    quantity: pend,
                                    projected_info: `${item.delivered_qty} / ${item.projected_qty}`
                                });
                            }
                        });
                    }
                } catch (error) {
                    Helper.alert('error', 'Error al cargar proyecciones');
                }
            }
        }
    },

    addItemRow(data = null) {
        const body = document.getElementById('salida-items-body');
        const row = document.createElement('tr');

        const itemsHtml = this.items.map(i => `<option value="${i.id}" ${data && data.item_id == i.id ? 'selected' : ''}>${i.name} (${i.unit})</option>`).join('');

        row.innerHTML = `
            <td class="ps-4">
                <select class="form-select border-0 bg-transparent row-item" required>
                    <option value="">-- Seleccionar Ítem --</option>
                    ${itemsHtml}
                </select>
            </td>
            <td class="small text-muted">
                ${data && data.projected_info ? data.projected_info : '-'}
            </td>
            <td>
                <input type="text" class="form-control border-0 bg-transparent row-batch" value="${data ? (data.batch_number || '') : ''}" placeholder="Lote...">
            </td>
            <td>
                <input type="number" class="form-control border-0 bg-transparent row-qty" value="${data ? data.quantity : '1'}" min="0.001" step="0.001" required>
            </td>
            <td class="text-end pe-4">
                <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        body.appendChild(row);
    },

    async save(id = null) {
        const form = document.getElementById('salida-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const items = [];
        document.querySelectorAll('#salida-items-body tr').forEach(row => {
            const itemId = row.querySelector('.row-item').value;
            if (itemId) {
                items.push({
                    item_id: itemId,
                    batch: row.querySelector('.row-batch').value,
                    quantity: row.querySelector('.row-qty').value
                });
            }
        });

        if (items.length === 0) {
            Helper.alert('warning', 'Debe agregar al menos un ítem.');
            return;
        }

        const data = {
            type: 'SALIDA_SEDE',
            cycle_id: document.getElementById('msg-cycle').value,
            branch_id: document.getElementById('msg-branch').value,
            remission_number: document.getElementById('msg-number').value,
            remission_date: document.getElementById('msg-date').value,
            carrier_name: document.getElementById('msg-carrier').value,
            vehicle_plate: document.getElementById('msg-plate').value,
            status: document.getElementById('msg-status').value,
            notes: document.getElementById('msg-notes') ? document.getElementById('msg-notes').value : '',
            items: items
        };

        try {
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/remissions/${id}` : '/remissions';
            const res = await Helper.fetchAPI(url, { method, body: JSON.stringify(data) });

            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('salidaModal')).hide();
                Helper.alert('success', 'Salida de Almacén guardada');
                this.init();
            } else {
                Helper.alert('error', res.message);
            }
        } catch (error) {
            Helper.alert('error', 'Error al guardar');
        }
    }
};

// Initialize
if (typeof SalidasView !== 'undefined') {
    SalidasView.init();
}
