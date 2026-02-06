/**
 * Almacen View - Warehouse & Inventory Module
 * Refined and Functional Version
 */

window.AlmacenView = {
    inventory: [],
    movements: [],
    suppliers: [],

    async init() {
        console.log('Initializing Almacen Module...');
        await this.loadData();
        this.render();
        this.attachEvents();
    },

    async loadData() {
        try {
            const [invRes, movRes, supRes] = await Promise.all([
                Helper.fetchAPI('/inventory'),
                Helper.fetchAPI('/movements'),
                Helper.fetchAPI('/proveedores')
            ]);

            this.inventory = invRes.success ? (invRes.data || []) : [];
            this.movements = movRes.success ? (movRes.data || []) : [];
            this.suppliers = supRes || []; // Suppliers returns array directly
        } catch (error) {
            console.error('Error loading warehouse data:', error);
        }
    },

    render() {
        const lowStockCount = this.inventory.filter(i => parseFloat(i.stock) <= parseFloat(i.minimum_stock)).length;

        const html = `
            <div class="container-fluid py-4">
                <!-- Dashboard de Inventario -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm bg-primary text-white p-3 h-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="small text-uppercase mb-1 opacity-75">Total Ítems</h6>
                                    <h3 class="mb-0">${this.inventory.length}</h3>
                                </div>
                                <div class="icon big opacity-50 text-white">
                                    <i class="fas fa-boxes fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm ${lowStockCount > 0 ? 'bg-danger' : 'bg-success'} text-white p-3 h-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="small text-uppercase mb-1 opacity-75">Stock Crítico</h6>
                                    <h3 class="mb-0">${lowStockCount}</h3>
                                </div>
                                <div class="icon big opacity-50 text-white">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm bg-info text-white p-3 h-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="small text-uppercase mb-1 opacity-75">Entradas (Mes)</h6>
                                    <h3 class="mb-0">${this.movements.filter(m => m.movement_type === 'ENTRADA').length}</h3>
                                </div>
                                <div class="icon big opacity-50 text-white">
                                    <i class="fas fa-file-import fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm bg-dark text-white p-3 h-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="small text-uppercase mb-1 opacity-75">Valor Inventario</h6>
                                    <h3 class="mb-0">Calculando...</h3>
                                </div>
                                <div class="icon big opacity-50 text-white">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs y Acciones -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <ul class="nav nav-tabs card-header-tabs border-0" id="warehouseTabs">
                            <li class="nav-item">
                                <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#tab-stock">Existencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold" data-bs-toggle="tab" href="#tab-movements">Movimientos</a>
                            </li>
                        </ul>
                        <div class="actions">
                            <button class="btn btn-success btn-sm me-2" onclick="AlmacenView.openMovementModal('ENTRADA')">
                                <i class="fas fa-plus me-1"></i> Nueva Entrada
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="AlmacenView.openMovementModal('SALIDA')">
                                <i class="fas fa-minus me-1"></i> Registrar Salida
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <!-- TAB EXISTENCIAS -->
                            <div class="tab-pane fade show active" id="tab-stock">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="stockTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Item</th>
                                                <th>Grupo</th>
                                                <th class="text-center">Stock Actual</th>
                                                <th>Unidad</th>
                                                <th>Mínimo</th>
                                                <th>Estado</th>
                                                <th class="text-end pe-4">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${this.renderStockTable()}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- TAB MOVIMIENTOS -->
                            <div class="tab-pane fade" id="tab-movements">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Fecha</th>
                                                <th>Tipo</th>
                                                <th>Referencia</th>
                                                <th>Proveedor</th>
                                                <th>Usuario</th>
                                                <th class="text-end pe-4">Detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${this.renderMovementsTable()}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const container = document.getElementById('app-container');
        if (container) {
            container.innerHTML = html;
        } else {
            console.error('App container not found');
        }
        this.initDataTable();
    },

    renderStockTable() {
        return this.inventory.map(item => {
            const isLow = parseFloat(item.stock) <= parseFloat(item.minimum_stock);
            return `
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-primary">${item.name}</div>
                        <small class="text-muted">${item.code}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">${item.food_group}</span></td>
                    <td class="text-center fw-bold fs-5">${Helper.formatNumber(item.stock, 3)}</td>
                    <td>${item.unit}</td>
                    <td class="text-muted">${Helper.formatNumber(item.minimum_stock, 3)}</td>
                    <td>
                        <span class="badge ${isLow ? 'bg-danger' : 'bg-success'} rounded-pill">
                            ${isLow ? 'ALERTA STOCK' : 'OK'}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary" title="Ver Historial" onclick="AlmacenView.viewKardex(${item.item_id})">
                                <i class="fas fa-history"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar Stock" onclick="AlmacenView.editItemStock(${item.item_id})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    },

    renderMovementsTable() {
        if (this.movements.length === 0) return '<tr><td colspan="6" class="text-center py-4 text-muted">No hay movimientos registrados</td></tr>';

        return this.movements.map(m => `
            <tr>
                <td class="ps-4">${m.movement_date}</td>
                <td>
                    <span class="badge ${m.movement_type === 'ENTRADA' ? 'bg-success' : 'bg-danger'}">
                        ${m.movement_type}
                    </span>
                </td>
                <td><small class="fw-bold">${m.reference_number || '-'}</small></td>
                <td>${m.supplier_name || 'N/A'}</td>
                <td>${m.user_name}</td>
                <td class="text-end pe-4">
                    <button class="btn btn-sm btn-light border" onclick="AlmacenView.viewMovementDetail(${m.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    },

    initDataTable() {
        // En un app real usaríamos DataTables.net aquí.
        console.log('DataTable initialized');
    },

    attachEvents() {
        // Eventos delegados si es necesario
    },

    openMovementModal(type) {
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = 'movementModal';
        modalDiv.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header ${type === 'ENTRADA' ? 'bg-success' : 'bg-danger'} text-white">
                        <h5 class="modal-title">
                            <i class="fas ${type === 'ENTRADA' ? 'fa-sign-in-alt' : 'fa-sign-out-alt'} me-2"></i>
                            Registrar ${type} de Inventario
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="movement-form">
                            <input type="hidden" name="type" value="${type}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Fecha</label>
                                    <input type="date" class="form-control" name="date" value="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Número de Referencia (Factura/Remisión)</label>
                                    <input type="text" class="form-control" name="reference" placeholder="F-12345">
                                </div>
                                ${type === 'ENTRADA' ? `
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">Proveedor</label>
                                    <select class="form-select" name="supplier_id">
                                        <option value="">Seleccione Proveedor...</option>
                                        ${this.suppliers.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                                    </select>
                                </div>
                                ` : ''}
                                
                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">Artículos a Mover</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="AlmacenView.addItemRow()">
                                            <i class="fas fa-plus me-1"></i> Añadir Ítem
                                        </button>
                                    </div>
                                    <div class="table-responsive border rounded">
                                        <table class="table table-sm mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Ítem</th>
                                                    <th style="width: 20%" class="text-end">Cantidad</th>
                                                    <th style="width: 25%">Observación/Lote</th>
                                                    <th style="width: 5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="movement-items-body">
                                                <!-- Filas de ítems dinámicas -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label small fw-bold">Notas Generales</label>
                                    <textarea class="form-control" name="notes" rows="2"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn ${type === 'ENTRADA' ? 'btn-success' : 'btn-danger'}" onclick="AlmacenView.saveMovement()">
                            Confirmar Movimiento
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();

        // Agregar la primera fila automáticamente
        this.addItemRow();

        modalDiv.addEventListener('hidden.bs.modal', function () {
            modalDiv.remove();
        });
    },

    addItemRow() {
        const tbody = document.getElementById('movement-items-body');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>
                <select class="form-select form-select-sm" name="item_id" required>
                    <option value="">Buscar ítem...</option>
                    ${this.inventory.map(i => `<option value="${i.item_id}">${i.name} (${i.code})</option>`).join('')}
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm text-end" name="quantity" 
                       value="1.000" onfocus="AlmacenView.unformatInput(this)" 
                       onblur="AlmacenView.formatInput(this, 3)" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" name="batch" placeholder="Lote/Venc">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    },

    async saveMovement() {
        const form = document.getElementById('movement-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const type = formData.get('type');

        const items = [];
        document.querySelectorAll('.item-row').forEach(row => {
            const itemId = row.querySelector('[name="item_id"]').value;
            const quantity = row.querySelector('[name="quantity"]').value;
            const batch = row.querySelector('[name="batch"]').value;

            if (itemId && quantity > 0) {
                items.push({
                    item_id: itemId,
                    quantity: quantity,
                    batch: batch
                });
            }
        });

        if (items.length === 0) {
            Helper.alert('error', 'Debe añadir al menos un ítem con cantidad mayor a cero');
            return;
        }

        const data = {
            type: type,
            date: formData.get('date'),
            reference: formData.get('reference'),
            supplier_id: formData.get('supplier_id'),
            notes: formData.get('notes'),
            items: items.map(item => ({
                item_id: item.item_id,
                quantity: item.quantity.replace(/,/g, ''), // Remove commas
                batch: item.batch
            }))
        };

        try {
            const response = await Helper.fetchAPI('/movements', {
                method: 'POST',
                body: JSON.stringify(data)
            });

            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('movementModal')).hide();
                Helper.alert('success', 'Movimiento registrado correctamente');
                await this.init(); // Recargar todo
            } else {
                Helper.alert('error', response.message);
            }
        } catch (error) {
            console.error('Error saving movement:', error);
            Helper.alert('error', 'Error al procesar el movimiento');
        }
    },

    unformatInput(input) {
        let val = input.value;
        val = val.replace(/,/g, '');
        input.value = val;
        input.select();
    },

    formatInput(input, decimals = 3) {
        let val = input.value;
        val = val.replace(/[^0-9.]/g, '');
        if (val === '') return;

        const num = parseFloat(val);
        if (!isNaN(num)) {
            input.value = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(num);
        }
    }
};

// Initialize
if (typeof AlmacenView !== 'undefined') {
    AlmacenView.init();
}
