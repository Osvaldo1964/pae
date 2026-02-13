/**
 * Presupuesto View - Finance Module
 * Financial Planning and Budget Tracking
 */

window.PresupuestoView = {
    items: [],
    branches: [],

    async init() {
        console.log('Initializing Presupuesto Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [items, branches] = await Promise.all([
                Helper.fetchAPI('/presupuesto'),
                Helper.fetchAPI('/presupuesto/branches')
            ]);
            this.items = Array.isArray(items) ? items : [];
            this.branches = Array.isArray(branches) ? branches : [];
        } catch (error) {
            console.error('Error loading budget data:', error);
        }
    },

    render() {
        const totalBudget = this.items.reduce((acc, item) => acc + parseFloat(item.valor_total_oficial), 0);

        const html = `
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="text-primary-custom fw-bold mb-0">Planeación Presupuestal</h2>
                        <p class="text-muted">Definición y seguimiento de rubros presupuestales</p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="bg-white border rounded px-3 py-1 shadow-sm d-flex align-items-center">
                            <small class="text-muted text-uppercase fw-bold me-2">Presupuesto Total:</small>
                            <span class="fs-5 fw-bold text-primary">${Helper.formatCurrency(totalBudget)}</span>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="PresupuestoView.openModal()">
                            <i class="fas fa-plus me-2"></i>Nuevo Rubro
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive p-3">
                            <table id="presupuestoTable" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light text-secondary text-uppercase small fw-bold">
                                    <tr>
                                        <th class="ps-4">Código</th>
                                        <th>Nombre del Rubro</th>
                                        <th class="text-end">Vlr. Unitario</th>
                                        <th class="text-center">Cant / Tiempo</th>
                                        <th class="text-end pe-4">Total Oficial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${this.renderTableBody()}
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
            Helper.initDataTable('#presupuestoTable', {
                order: [[0, 'asc']],
                pageLength: 10
            });
        }
    },

    renderTableBody() {
        return this.items.map(item => `
            <tr>
                <td class="ps-4 fw-bold text-dark text-nowrap">${item.codigo}</td>
                <td>
                    <div class="fw-bold text-primary-custom">${item.nombre}</div>
                    <small class="text-muted d-block" style="max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        ${item.descripcion || 'Sin descripción'}
                    </small>
                </td>
                <td class="text-end fw-bold text-success">${Helper.formatCurrency(item.valor_unitario_oficial)}</td>
                <td class="text-center">
                    <span class="badge bg-light text-dark border">${item.cantidad_global} ${item.unidad_medida || 'Und'}</span>
                    <div class="small text-muted mt-1">${item.tiempo_global} Meses</div>
                </td>
                <td class="text-end pe-4">
                    <h5 class="mb-0 fw-bold">${Helper.formatCurrency(item.valor_total_oficial)}</h5>
                </td>
            </tr>
        `).join('');
    },

    async openModal() {
        const branchesHtml = this.branches.map(b => `
            <tr data-branch-id="${b.id}">
                <td style="max-width: 300px;">
                    <div class="fw-bold small text-truncate">${b.name}</div>
                    <div class="text-muted x-small" style="font-size: 0.7rem;">${b.school_name}</div>
                </td>
                <td><input type="number" class="form-control form-control-sm dist-cant" value="0" step="any" oninput="PresupuestoView.calcRow(this)"></td>
                <td><input type="number" class="form-control form-control-sm dist-meses" value="0" step="any" oninput="PresupuestoView.calcRow(this)"></td>
                <td><input type="number" class="form-control form-control-sm dist-vlr" value="0" step="any" oninput="PresupuestoView.calcRow(this)"></td>
                <td class="text-end"><span class="fw-bold dist-total text-secondary">$ 0</span></td>
            </tr>
        `).join('');

        const parentsHtml = this.items.map(i => `<option value="${i.id_item}">${i.codigo} - ${i.nombre}</option>`).join('');

        const { value: formValues } = await Swal.fire({
            title: '<strong>Nuevo Rubro Presupuestal</strong>',
            width: '1000px',
            html: `
                <div class="text-start px-2 py-3" style="max-height: 80vh; overflow-y: auto;">
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="fas fa-info-circle me-2"></i> 
                        Defina los valores globales y luego distribuya el presupuesto entre las sedes.
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 fw-bold text-uppercase small">
                        <i class="fas fa-folder-open me-2"></i>Información General
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">CÓDIGO (JERARQUÍA) <span class="text-danger">*</span></label>
                            <input id="bud-codigo" class="form-control" placeholder="Ej: 2.1.3">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">NOMBRE DEL RUBRO <span class="text-danger">*</span></label>
                            <input id="bud-nombre" class="form-control" placeholder="Nombre descriptivo">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">RUBRO PADRE</label>
                            <select id="bud-padre" class="form-select">
                                <option value="">Ninguno (Rubro Principal)</option>
                                ${parentsHtml}
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">DESCRIPCIÓN</label>
                            <textarea id="bud-descripcion" class="form-control" rows="2" placeholder="Detalle técnico del rubro"></textarea>
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 fw-bold text-uppercase small">
                        <i class="fas fa-calculator me-2"></i>Cálculo Global contratado
                    </h6>
                    <div class="row g-3 mb-4 bg-light p-3 rounded border">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">UNIDAD</label>
                            <input id="bud-unidad" class="form-control" placeholder="Mes/Glb/Hect">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">CANTIDAD</label>
                            <input id="bud-cant" type="number" class="form-control" value="0" step="any" oninput="PresupuestoView.calcGlobal()">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">MESES / TIEMPO</label>
                            <input id="bud-meses" type="number" class="form-control" value="0" step="any" oninput="PresupuestoView.calcGlobal()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">VALOR UNITARIO</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input id="bud-vlr" type="number" class="form-control" value="0" step="any" oninput="PresupuestoView.calcGlobal()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-success">TOTAL GLOBAL</label>
                            <div id="bud-total-global" class="fs-4 fw-bold text-success" data-val="0">$ 0</div>
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 fw-bold text-uppercase small">
                        <i class="fas fa-sitemap me-2"></i>Distribución por Sedes (Sedes / Colegios)
                    </h6>
                    <div class="table-responsive border rounded bg-white shadow-sm">
                        <table class="table table-sm table-hover align-middle mb-0" id="distribucionTable">
                            <thead class="bg-light small fw-bold text-secondary">
                                <tr>
                                    <th class="ps-3 py-2">CENTRO / SEDE</th>
                                    <th style="width: 100px;">CANTIDAD</th>
                                    <th style="width: 80px;">MESES</th>
                                    <th style="width: 160px;">VLR. UNITARIO</th>
                                    <th class="text-end pe-3" style="width: 150px;">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${branchesHtml}
                            </tbody>
                            <tfoot class="bg-light fw-bold border-top">
                                <tr>
                                    <td colspan="4" class="text-end text-uppercase small">Total Asignado:</td>
                                    <td class="text-end pe-3"><span id="sum-asignado" class="fs-6" data-val="0">$ 0</span></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end text-uppercase small">Diferencia (Sobrante/Faltante):</td>
                                    <td class="text-end pe-3"><span id="diff-asignado" class="text-success" data-val="0">$ 0</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Guardar Presupuesto',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#16a085',
            padding: '1em',
            preConfirm: () => {
                const codigo = document.getElementById('bud-codigo').value;
                const nombre = document.getElementById('bud-nombre').value;
                if (!codigo || !nombre) {
                    Swal.showValidationMessage('Código y Nombre son obligatorios');
                    return false;
                }

                const totalGlobal = parseFloat(document.getElementById('bud-total-global').dataset.val) || 0;
                const totalAsignado = parseFloat(document.getElementById('sum-asignado').dataset.val) || 0;

                if (Math.abs(totalGlobal - totalAsignado) > 0.01) {
                    Swal.showValidationMessage('La distribución no coincide con el total global');
                    return false;
                }

                // Collect distribution
                const distribucion = [];
                document.querySelectorAll('#distribucionTable tbody tr').forEach(tr => {
                    const branchId = tr.dataset.branchId;
                    const cant = parseFloat(tr.querySelector('.dist-cant').value) || 0;
                    const meses = parseFloat(tr.querySelector('.dist-meses').value) || 0;
                    const vlr = parseFloat(tr.querySelector('.dist-vlr').value) || 0;
                    const total = cant * meses * vlr;

                    if (total > 0) {
                        distribucion.push({
                            branch_id: branchId,
                            cantidad: cant,
                            meses: meses,
                            valor_unitario: vlr,
                            total: total
                        });
                    }
                });

                return {
                    codigo,
                    nombre,
                    padre_id: document.getElementById('bud-padre').value,
                    descripcion: document.getElementById('bud-descripcion').value,
                    unidad_medida: document.getElementById('bud-unidad').value,
                    cantidad_global: parseFloat(document.getElementById('bud-cant').value) || 0,
                    tiempo_global: parseFloat(document.getElementById('bud-meses').value) || 0,
                    valor_unitario_oficial: parseFloat(document.getElementById('bud-vlr').value) || 0,
                    valor_total_oficial: totalGlobal,
                    distribucion
                }
            }
        });

        if (formValues) {
            Helper.loading(true, 'Guardando rubro y asignaciones...');
            try {
                const res = await Helper.fetchAPI('/presupuesto', {
                    method: 'POST',
                    body: JSON.stringify(formValues)
                });
                Helper.loading(false);
                if (res.success) {
                    Swal.fire('¡Éxito!', 'Presupuesto cargado correctamente.', 'success');
                    this.init();
                } else {
                    Swal.fire('Error', res.message || 'Error desconocido', 'error');
                }
            } catch (error) {
                Helper.loading(false);
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            }
        }
    },

    calcGlobal() {
        const cant = parseFloat(document.getElementById('bud-cant').value) || 0;
        const meses = parseFloat(document.getElementById('bud-meses').value) || 0;
        const vlr = parseFloat(document.getElementById('bud-vlr').value) || 0;
        const total = cant * meses * vlr;

        const el = document.getElementById('bud-total-global');
        el.innerText = Helper.formatCurrency(total);
        el.dataset.val = total;
        this.updateDifference();
    },

    calcRow(input) {
        const tr = input.closest('tr');
        const cant = parseFloat(tr.querySelector('.dist-cant').value) || 0;
        const meses = parseFloat(tr.querySelector('.dist-meses').value) || 0;
        const vlr = parseFloat(tr.querySelector('.dist-vlr').value) || 0;
        const total = cant * meses * vlr;

        const span = tr.querySelector('.dist-total');
        span.innerText = Helper.formatCurrency(total);
        span.classList.toggle('text-secondary', total === 0);
        span.classList.toggle('text-primary', total > 0);
        this.updateDifference();
    },

    updateDifference() {
        let sum = 0;
        document.querySelectorAll('#distribucionTable tbody tr').forEach(tr => {
            const cant = parseFloat(tr.querySelector('.dist-cant').value) || 0;
            const meses = parseFloat(tr.querySelector('.dist-meses').value) || 0;
            const vlr = parseFloat(tr.querySelector('.dist-vlr').value) || 0;
            sum += cant * meses * vlr;
        });

        const sumEl = document.getElementById('sum-asignado');
        sumEl.innerText = Helper.formatCurrency(sum);
        sumEl.dataset.val = sum;

        const totalGlobal = parseFloat(document.getElementById('bud-total-global').dataset.val) || 0;
        const diff = totalGlobal - sum;

        const diffEl = document.getElementById('diff-asignado');
        diffEl.innerText = Helper.formatCurrency(diff);
        diffEl.dataset.val = diff;

        if (Math.abs(diff) < 0.01) {
            diffEl.className = 'text-success';
        } else {
            diffEl.className = 'text-danger';
        }
    }
};

// Initialize
PresupuestoView.init();
