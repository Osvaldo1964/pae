/**
 * Traslados View - Finance Module
 * Management of transfers between accounts or items
 */

window.TrasladosView = {
    traslados: [],
    budget: [],

    async init() {
        console.log('Initializing Traslados Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [traslados, budget] = await Promise.all([
                Helper.fetchAPI('/traslados'),
                Helper.fetchAPI('/movimientos/budget') // Reusing active budget endpoint
            ]);
            this.traslados = Array.isArray(traslados) ? traslados : [];
            this.budget = Array.isArray(budget) ? budget : [];
        } catch (error) {
            console.error('Error loading transfer data:', error);
        }
    },

    render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="text-primary-custom fw-bold mb-0">Traslados Internos</h2>
                        <p class="text-muted">Gestión de transferencias entre cuentas o rubros presupuestales</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="TrasladosView.openModal()">
                        <i class="fas fa-exchange-alt me-2"></i>Nuevo Traslado
                    </button>
                </div>

                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive p-3">
                            <table id="trasladosTable" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light text-secondary text-uppercase small fw-bold">
                                    <tr>
                                        <th class="ps-4">Fecha</th>
                                        <th>Origen (Se Debita)</th>
                                        <th class="text-center"><i class="fas fa-arrow-right text-muted"></i></th>
                                        <th>Destino (Se Acredita)</th>
                                        <th class="text-end pe-4">Valor Trasladado</th>
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
            Helper.initDataTable('#trasladosTable', { order: [[0, 'desc']] });
        }
    },

    renderTableBody() {
        return this.traslados.map(t => `
            <tr>
                <td class="ps-4 fw-bold">${Helper.formatDate(t.fecha)}</td>
                <td>
                    <div class="small fw-bold text-danger">${t.cod_origen} - ${t.nom_origen}</div>
                    <div class="x-small text-muted">${t.branch_origen}</div>
                </td>
                <td class="text-center text-muted">
                    <i class="fas fa-long-arrow-alt-right fa-lg"></i>
                </td>
                <td>
                    <div class="small fw-bold text-success">${t.cod_destino} - ${t.nom_destino}</div>
                    <div class="x-small text-muted">${t.branch_destino}</div>
                </td>
                <td class="text-end pe-4 fw-bold text-dark">
                    ${Helper.formatCurrency(t.valor)}
                    <div class="btn-group ms-2">
                        <button class="btn btn-sm btn-light border-0" title="${t.justificacion}">
                            <i class="fas fa-comment-dots text-info"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

    async openModal() {
        const budgetOptions = this.budget.map(b =>
            `<option value="${b.id_asignacion}">${b.codigo} - ${b.item_nombre} (${b.branch_name}) | Saldo: ${Helper.formatCurrency(b.saldo_disponible)}</option>`
        ).join('');

        const today = new Date().toISOString().split('T')[0];

        const { value: formValues } = await Swal.fire({
            title: '<strong>Nuevo Traslado Presupuestal</strong>',
            width: '800px',
            html: `
                <div class="text-start px-2 py-3">
                    <div class="alert alert-warning border-0 small mb-4">
                        <i class="fas fa-info-circle me-1"></i> Todos los campos son obligatorios. El traslado moverá saldo del Origen al Destino.
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Fecha del Traslado</label>
                            <input id="tras-fecha" type="date" class="form-control" value="${today}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Valor a Trasladar ($)</label>
                            <input id="tras-valor" type="number" step="any" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-12 mt-4">
                            <label class="form-label small fw-bold text-uppercase text-danger"><i class="fas fa-minus-circle me-1"></i>Origen de Fondos (Se Debita)</label>
                            <select id="tras-origen" class="form-select select2-basic">
                                <option value="">Seleccione Rubro/Centro</option>
                                ${budgetOptions}
                            </select>
                            <small class="text-muted d-block mt-1">Seleccione el rubro del cual saldrá el dinero.</small>
                        </div>

                        <div class="col-12 mt-4">
                            <label class="form-label small fw-bold text-uppercase text-success"><i class="fas fa-plus-circle me-1"></i>Destino de Fondos (Se Acredita)</label>
                            <select id="tras-destino" class="form-select select2-basic">
                                <option value="">Seleccione Rubro/Centro</option>
                                ${budgetOptions}
                            </select>
                            <small class="text-muted d-block mt-1">Seleccione el rubro al cual ingresará el dinero.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Justificación / Detalle</label>
                            <textarea id="tras-detalle" class="form-control" rows="3" placeholder="Razón del traslado..."></textarea>
                        </div>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Guardar Traslado',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#16a085',
            preConfirm: () => {
                const origen_id = document.getElementById('tras-origen').value;
                const destino_id = document.getElementById('tras-destino').value;
                const valor = parseFloat(document.getElementById('tras-valor').value) || 0;
                const fecha = document.getElementById('tras-fecha').value;
                const justificacion = document.getElementById('tras-detalle').value;

                if (!origen_id || !destino_id || valor <= 0 || !fecha || !justificacion) {
                    Swal.showValidationMessage('Complete todos los campos obligatorios');
                    return false;
                }

                if (origen_id === destino_id) {
                    Swal.showValidationMessage('El origen y el destino no pueden ser iguales');
                    return false;
                }

                // Check balance
                const sourceBudget = this.budget.find(b => b.id_asignacion == origen_id);
                if (sourceBudget && valor > sourceBudget.saldo_disponible) {
                    Swal.showValidationMessage(`Saldo insuficiente en origen. Disponible: ${Helper.formatCurrency(sourceBudget.saldo_disponible)}`);
                    return false;
                }

                return {
                    origen_id,
                    destino_id,
                    valor,
                    fecha,
                    justificacion
                }
            }
        });

        if (formValues) {
            Helper.loading(true, 'Ejecutando traslado presupuestal...');
            try {
                const res = await Helper.fetchAPI('/traslados', {
                    method: 'POST',
                    body: JSON.stringify(formValues)
                });
                Helper.loading(false);
                if (res.success) {
                    Swal.fire('¡Éxito!', res.message, 'success');
                    await this.init();
                } else {
                    Swal.fire('Error', res.message || 'Error al procesar traslado', 'error');
                }
            } catch (error) {
                Helper.loading(false);
                Swal.fire('Error', 'Error de conexión', 'error');
            }
        }
    }
};

// Initialize
TrasladosView.init();
