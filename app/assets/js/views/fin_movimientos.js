/**
 * Movimientos View - Finance Module
 * Daily Record of Income and Expenses
 */

window.MovimientosView = {
    movimientos: [],
    budget: [],
    terceros: [],

    async init() {
        console.log('Initializing Movimientos Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [movimientos, budget, terceros] = await Promise.all([
                Helper.fetchAPI('/movimientos'),
                Helper.fetchAPI('/movimientos/budget'),
                Helper.fetchAPI('/terceros')
            ]);
            this.movimientos = Array.isArray(movimientos) ? movimientos : [];
            this.budget = Array.isArray(budget) ? budget : [];
            this.terceros = Array.isArray(terceros) ? terceros : [];
        } catch (error) {
            console.error('Error loading movements data:', error);
        }
    },

    render() {
        const totalGastado = this.movimientos.reduce((acc, mov) => acc + parseFloat(mov.valor), 0);
        const hoy = new Date().toISOString().split('T')[0];
        const gastadoHoy = this.movimientos
            .filter(m => m.fecha === hoy)
            .reduce((acc, mov) => acc + parseFloat(mov.valor), 0);

        const html = `
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="text-primary-custom fw-bold mb-0">Movimientos Financieros</h2>
                        <p class="text-muted">Registro diario de egresos y ejecución presupuestal</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="MovimientosView.openModal()">
                        <i class="fas fa-plus me-2"></i>Nuevo Movimiento
                    </button>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 h-100 border-start border-danger border-4">
                            <h6 class="small text-uppercase mb-1 text-muted">Total Ejecutado (Histórico)</h6>
                            <h3 class="mb-0 fw-bold text-danger">${Helper.formatCurrency(totalGastado)}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 h-100 border-start border-warning border-4">
                            <h6 class="small text-uppercase mb-1 text-muted">Ejecutado Hoy</h6>
                            <h3 class="mb-0 fw-bold text-warning">${Helper.formatCurrency(gastadoHoy)}</h3>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive p-3">
                            <table id="movimientosTable" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light text-secondary text-uppercase small fw-bold">
                                    <tr>
                                        <th class="ps-4">Fecha / Doc</th>
                                        <th>Rubro / Centro</th>
                                        <th>Tercero / Beneficiario</th>
                                        <th class="text-end">Valor</th>
                                        <th class="text-center">Soporte</th>
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
            Helper.initDataTable('#movimientosTable', { order: [[0, 'desc']] });
        }
    },

    renderTableBody() {
        return this.movimientos.map(m => `
            <tr>
                <td class="ps-4">
                    <div class="fw-bold">${Helper.formatDate(m.fecha)}</div>
                    <small class="text-muted"><i class="fas fa-file-invoice me-1"></i>${m.numero_documento || 'S/N'}</small>
                </td>
                <td>
                    <div class="small fw-bold">${m.item_codigo} - ${m.item_nombre}</div>
                    <div class="x-small text-muted" style="font-size: 0.75rem;">${m.branch_name}</div>
                </td>
                <td>
                    <div class="fw-bold text-primary-custom text-uppercase">${m.tercero_nombre}</div>
                    <div class="x-small text-muted">${m.tipo_movimiento}</div>
                </td>
                <td class="text-end">
                    <span class="fw-bold text-danger">${Helper.formatCurrency(m.valor)}</span>
                </td>
                <td class="text-center">
                    ${m.soporte_url ?
                `<a href="${Config.BASE_URL}${m.soporte_url}" target="_blank" class="btn btn-sm btn-light text-info shadow-sm">
                            <i class="fas fa-eye me-1"></i>Ver
                         </a>` :
                `<span class="text-muted small">Sin soporte</span>`}
                </td>
            </tr>
        `).join('');
    },

    async openModal() {
        const budgetOptions = this.budget.map(b =>
            `<option value="${b.id_asignacion}">${b.codigo} - ${b.item_nombre} (${b.branch_name}) | Saldo: ${Helper.formatCurrency(b.saldo_disponible)}</option>`
        ).join('');

        const tercerOptions = this.terceros.map(t =>
            `<option value="${t.id_tercero}">${t.identificacion} - ${t.nombres}</option>`
        ).join('');

        const today = new Date().toISOString().split('T')[0];

        const { value: formValues } = await Swal.fire({
            title: '<strong>Nuevo Movimiento</strong>',
            width: '800px',
            html: `
                <div class="text-start px-2 py-3">
                    <p class="small text-info mb-4 border-bottom pb-2">Todos los campos son obligatorios.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase">Fecha</label>
                            <input id="mov-fecha" type="date" class="form-control" value="${today}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase">Tipo Movimiento</label>
                            <select id="mov-tipo" class="form-select">
                                <option value="Pago">Pago</option>
                                <option value="Compra">Compra</option>
                                <option value="Nomina">Nomina</option>
                                <option value="Servicio">Servicio</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase">Valor ($)</label>
                            <input id="mov-valor" type="number" step="any" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Rubro / Centro de Costo (Saldo Disponible)</label>
                            <select id="mov-asignacion" class="form-select select2-basic">
                                <option value="">Seleccione Rubro/Centro</option>
                                ${budgetOptions}
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-uppercase">Tercero / Beneficiario</label>
                            <select id="mov-tercero" class="form-select select2-basic">
                                <option value="">Seleccione Tercero</option>
                                ${tercerOptions}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase">No. Documento / Factura</label>
                            <input id="mov-documento" class="form-control" placeholder="Nro de soporte">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Soporte (PDF/Imagen) - <small class="text-muted text-lowercase font-italic">Opcional</small></label>
                            <input id="mov-soporte" type="file" class="form-control" accept="image/*,application/pdf">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Detalle / Observación</label>
                            <textarea id="mov-detalle" class="form-control" rows="3" placeholder="Descripción detallada del egreso..."></textarea>
                        </div>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Guardar',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#16a085',
            preConfirm: () => {
                const asignacion_id = document.getElementById('mov-asignacion').value;
                const tercero_id = document.getElementById('mov-tercero').value;
                const valor = parseFloat(document.getElementById('mov-valor').value) || 0;
                const fecha = document.getElementById('mov-fecha').value;

                if (!asignacion_id || !tercero_id || valor <= 0 || !fecha) {
                    Swal.showValidationMessage('Complete todos los campos obligatorios y asegure que el valor sea mayor a 0');
                    return false;
                }

                // Check if valor exceeds available balance
                const selectedBudget = this.budget.find(b => b.id_asignacion == asignacion_id);
                if (selectedBudget && valor > selectedBudget.saldo_disponible) {
                    Swal.showValidationMessage(`Saldo insuficiente. Disponible: ${Helper.formatCurrency(selectedBudget.saldo_disponible)}`);
                    return false;
                }

                const formData = new FormData();
                formData.append('asignacion_id', asignacion_id);
                formData.append('tercero_id', tercero_id);
                formData.append('valor', valor);
                formData.append('fecha', fecha);
                formData.append('tipo_movimiento', document.getElementById('mov-tipo').value);
                formData.append('numero_documento', document.getElementById('mov-documento').value);
                formData.append('detalle', document.getElementById('mov-detalle').value);

                const fileInput = document.getElementById('mov-soporte');
                if (fileInput.files[0]) {
                    formData.append('soporte', fileInput.files[0]);
                }

                return formData;
            }
        });

        if (formValues) {
            Helper.loading(true, 'Registrando movimiento...');
            try {
                const res = await Helper.fetchAPI('/movimientos', {
                    method: 'POST',
                    body: formValues
                });
                Helper.loading(false);
                if (res.success) {
                    Swal.fire('¡Éxito!', res.message, 'success');
                    await this.init();
                } else {
                    Swal.fire('Error', res.message || 'Error al guardar', 'error');
                }
            } catch (error) {
                Helper.loading(false);
                Swal.fire('Error', 'Error de conexión', 'error');
            }
        }
    }
};

// Initialize
MovimientosView.init();
