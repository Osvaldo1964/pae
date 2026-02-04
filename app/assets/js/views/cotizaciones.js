/**
 * Cotizaciones View - Módulo de Inventarios
 */

window.CotizacionesView = {
    quotes: [],
    suppliers: [],
    items: [],

    async init() {
        console.log('Initializing Cotizaciones Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [quoteRes, supplierRes, itemRes] = await Promise.all([
                Helper.fetchAPI('/quotes'),
                Helper.fetchAPI('/proveedores'),
                Helper.fetchAPI('/items')
            ]);
            this.quotes = quoteRes.success ? quoteRes.data : [];
            this.suppliers = supplierRes.success ? supplierRes.data : [];
            this.items = itemRes.success ? itemRes.data : [];
        } catch (error) {
            console.error('Error loading quotes data:', error);
        }
    },

    render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1 text-primary fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i>Cotizaciones</h2>
                        <p class="text-muted mb-0">Gestión de precios y propuestas de proveedores</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="CotizacionesView.openModal()">
                        <i class="fas fa-plus me-1"></i> Nueva Cotización
                    </button>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="quotes-table">
                                <thead class="bg-light">
                                    <tr class="text-muted small text-uppercase fw-bold">
                                        <th class="ps-4">No. Cotización</th>
                                        <th>Proveedor</th>
                                        <th>Fecha</th>
                                        <th>Vencimiento</th>
                                        <th>Total</th>
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
        } else {
            console.error('App container not found');
        }
    },

    renderTableRows() {
        if (!this.quotes || this.quotes.length === 0) {
            return ''; // Let DataTables handle the empty state
        }

        return this.quotes.map(q => `
            <tr>
                <td class="ps-4 fw-bold text-dark">${q.quote_number || 'N/A'}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded-circle text-primary d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px">
                            <i class="fas fa-building small"></i>
                        </div>
                        <span class="fw-medium">${q.supplier_name}</span>
                    </div>
                </td>
                <td>${q.quote_date}</td>
                <td>${q.valid_until || '<span class="text-muted">-</span>'}</td>
                <td class="fw-bold text-primary">${Helper.formatCurrency(q.total_amount)}</td>
                <td>
                    <span class="badge rounded-pill ${this.getStatusBadgeClass(q.status)}">${q.status}</span>
                </td>
                <td class="text-end pe-4">
                    <div class="btn-group shadow-sm rounded-3">
                        <button class="btn btn-white btn-sm" onclick="CotizacionesView.openModal(${q.id})" title="Ver/Editar"><i class="fas fa-edit text-primary"></i></button>
                        <button class="btn btn-white btn-sm" onclick="CotizacionesView.deleteQuote(${q.id})" title="Eliminar"><i class="fas fa-trash text-danger"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

    getStatusBadgeClass(status) {
        switch (status) {
            case 'APROBADA': return 'bg-success-soft text-success';
            case 'RECHAZADA': return 'bg-danger-soft text-danger';
            case 'ENVIADA': return 'bg-info-soft text-info';
            default: return 'bg-secondary-soft text-secondary';
        }
    },

    initTable() {
        if ($.fn.DataTable.isDataTable('#quotes-table')) {
            $('#quotes-table').DataTable().destroy();
        }
        $('#quotes-table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                emptyTable: "No se encontraron cotizaciones registradas"
            },
            pageLength: 10,
            ordering: true,
            info: true,
            columnDefs: [{ targets: 6, orderable: false }]
        });
    },

    async openModal(id = null) {
        const isEdit = !!id;
        const quote = isEdit ? this.quotes.find(q => q.id === id) : null;
        let quoteItems = [];

        if (isEdit) {
            try {
                const res = await Helper.fetchAPI(`/quotes/${id}/details`);
                quoteItems = res.success ? res.data : [];
            } catch (error) {
                console.error('Error fetching quote details:', error);
            }
        }

        const modalId = 'quoteModal';
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = modalId;

        const suppliersHtml = this.suppliers.map(s => `<option value="${s.id}" ${quote && quote.supplier_id == s.id ? 'selected' : ''}>${s.name}</option>`).join('');

        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="fas ${isEdit ? 'fa-edit' : 'fa-plus-circle'} me-2"></i>
                            ${isEdit ? 'Editar Cotización' : 'Nueva Cotización'}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <form id="quote-form">
                            <div class="card border-0 shadow-sm mb-4 rounded-3">
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Proveedor</label>
                                            <select class="form-control form-select-lg border-2" id="q-supplier" required>
                                                <option value="">-- Seleccionar Proveedor --</option>
                                                ${suppliersHtml}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-muted text-uppercase">No. Cotización</label>
                                            <input type="text" class="form-control form-control-lg border-2" id="q-number" value="${quote ? quote.quote_number : ''}" placeholder="Ej: COT-2026-001">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Fecha</label>
                                            <input type="date" class="form-control form-control-lg border-2" id="q-date" value="${quote ? quote.quote_date : new Date().toISOString().split('T')[0]}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold text-muted text-uppercase">Vence</label>
                                            <input type="date" class="form-control form-control-lg border-2" id="q-expiry" value="${quote ? quote.valid_until : ''}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-primary"></i>Ítems de la Cotización</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="CotizacionesView.addItemRow()">
                                        <i class="fas fa-plus me-1"></i> Agregar Ítem
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="quote-items-table">
                                            <thead class="bg-light small text-uppercase">
                                                <tr>
                                                    <th class="ps-4">Ítem / Ingrediente</th>
                                                    <th style="width: 150px;">Cantidad</th>
                                                    <th style="width: 200px;">Precio Unitario</th>
                                                    <th style="width: 200px;">Subtotal</th>
                                                    <th class="text-end pe-4" style="width: 50px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="quote-items-body">
                                                <!-- Row will be inserted here -->
                                            </tbody>
                                            <tfoot class="bg-light fw-bold">
                                                <tr>
                                                    <td colspan="3" class="text-end py-3">TOTAL COTIZACIÓN:</td>
                                                    <td id="q-total-display" class="py-3 text-primary fs-5">$ 0.00</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-white py-3">
                        <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary px-5 rounded-pill shadow" onclick="CotizacionesView.save(${quote ? quote.id : null})">
                            <i class="fas fa-save me-2"></i>${isEdit ? 'Actualizar Cotización' : 'Guardar Cotización'}
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();

        if (isEdit && quoteItems.length > 0) {
            quoteItems.forEach(item => this.addItemRow(item));
        } else {
            this.addItemRow(); // At least one empty row
        }

        modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
    },

    addItemRow(data = null) {
        const body = document.getElementById('quote-items-body');
        const row = document.createElement('tr');

        const itemsHtml = this.items.map(i => `<option value="${i.id}" ${data && data.item_id == i.id ? 'selected' : ''}>${i.name} (${i.unit})</option>`).join('');

        row.innerHTML = `
            <td class="ps-4">
                <select class="form-select border-0 bg-transparent row-item" required onchange="CotizacionesView.calculateRow(this)">
                    <option value="">-- Seleccionar Ítem --</option>
                    ${itemsHtml}
                </select>
            </td>
            <td>
                <input type="number" class="form-control border-0 bg-transparent row-qty" value="${data ? data.quantity : '1'}" min="0.001" step="0.001" required oninput="CotizacionesView.calculateRow(this)">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-0">$</span>
                    <input type="number" class="form-control border-0 bg-transparent row-price" value="${data ? data.unit_price : '0'}" min="0" step="0.01" required oninput="CotizacionesView.calculateRow(this)">
                </div>
            </td>
            <td class="fw-bold text-dark row-subtotal">$ 0.00</td>
            <td class="text-end pe-4">
                <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove(); CotizacionesView.calculateTotal();">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        body.appendChild(row);
        this.calculateRow(row.querySelector('.row-item'));
    },

    calculateRow(element) {
        const row = element.closest('tr');
        const qty = parseFloat(row.querySelector('.row-qty').value) || 0;
        const price = parseFloat(row.querySelector('.row-price').value) || 0;
        const subtotal = qty * price;
        row.querySelector('.row-subtotal').innerText = Helper.formatCurrency(subtotal);
        row.dataset.subtotal = subtotal;
        this.calculateTotal();
    },

    calculateTotal() {
        let total = 0;
        document.querySelectorAll('#quote-items-body tr').forEach(row => {
            total += parseFloat(row.dataset.subtotal) || 0;
        });
        document.getElementById('q-total-display').innerText = Helper.formatCurrency(total);
        this.total = total;
    },

    async save(id = null) {
        const form = document.getElementById('quote-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const items = [];
        document.querySelectorAll('#quote-items-body tr').forEach(row => {
            const itemId = row.querySelector('.row-item').value;
            if (itemId) {
                items.push({
                    item_id: itemId,
                    quantity: row.querySelector('.row-qty').value,
                    unit_price: row.querySelector('.row-price').value,
                    subtotal: row.dataset.subtotal
                });
            }
        });

        if (items.length === 0) {
            Helper.alert('warning', 'Debe agregar al menos un ítem.');
            return;
        }

        const data = {
            supplier_id: document.getElementById('q-supplier').value,
            quote_number: document.getElementById('q-number').value,
            quote_date: document.getElementById('q-date').value,
            valid_until: document.getElementById('q-expiry').value,
            total_amount: this.total,
            items: items
        };

        try {
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/quotes/${id}` : '/quotes';
            const res = await Helper.fetchAPI(url, { method, body: JSON.stringify(data) });

            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('quoteModal')).hide();
                Helper.alert('success', 'Cotización guardada exitosamente');
                this.init();
            } else {
                Helper.alert('error', res.message);
            }
        } catch (error) {
            Helper.alert('error', 'Error al guardar la cotización');
        }
    },

    async deleteQuote(id) {
        const confirm = await Helper.confirm('¿Eliminar cotización?', 'Esta acción no se puede deshacer.');
        if (confirm.isConfirmed) {
            try {
                const res = await Helper.fetchAPI(`/quotes/${id}`, { method: 'DELETE' });
                if (res.success) {
                    Helper.alert('success', 'Cotización eliminada');
                    this.init();
                } else {
                    Helper.alert('error', res.message);
                }
            } catch (error) {
                Helper.alert('error', 'Error al eliminar la cotización');
            }
        }
    }
};

// Auto-initialize if loaded directly
if (typeof CotizacionesView !== 'undefined' && document.getElementById('app')) {
    CotizacionesView.init();
}
