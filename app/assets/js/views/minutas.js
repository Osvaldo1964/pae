/**
 * Minutas View - Menu Cycles & Templates Module
 */

window.MinutasView = {
    templates: [],
    cycles: [],
    recipes: [],
    rationTypes: [],

    async init() {
        console.log('Initializing Minutas Module v1.4.4...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [tempRes, cycleRes, recipeRes, rationRes] = await Promise.all([
                Helper.fetchAPI('/cycle-templates'),
                Helper.fetchAPI('/menu-cycles'),
                Helper.fetchAPI('/recipes'),
                Helper.fetchAPI('/ration-types')
            ]);

            this.templates = tempRes.success ? (tempRes.data || []) : (Array.isArray(tempRes) ? tempRes : []);
            this.cycles = cycleRes.success ? (cycleRes.data || []) : (Array.isArray(cycleRes) ? cycleRes : []);
            this.recipes = recipeRes.success ? (recipeRes.data || []) : (Array.isArray(recipeRes) ? recipeRes : []);
            this.rationTypes = rationRes.success ? (rationRes.data || []) : [];
        } catch (error) {
            console.error('Error loading minutas data:', error);
            Helper.alert('error', 'Error al cargar los datos de minutas');
        }
    },

    render() {
        const html = `
            <div class="container-fluid py-4 text-dark">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div>
                        <h2 class="mb-1 text-primary-custom fw-bold"><i class="fas fa-calendar-alt me-2"></i>Gestión de Minutas</h2>
                        <p class="text-muted mb-0">Planeación de ciclos y plantillas maestras flexibles</p>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm d-inline-flex border" id="minutasTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-4 py-2 fw-bold" id="active-cycles-tab" data-bs-toggle="pill" data-bs-target="#active-cycles" type="button">
                            <i class="fas fa-calendar-check me-2"></i>Ciclos de Menú
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4 py-2 fw-bold" id="templates-tab" data-bs-toggle="pill" data-bs-target="#templates" type="button">
                            <i class="fas fa-layer-group me-2"></i>Plantillas Maestras
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="minutasTabsContent">
                    <!-- Tab 1: Active Cycles -->
                    <div class="tab-pane fade show active" id="active-cycles">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Ciclos Programados</h5>
                            <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" onclick="MinutasView.openNewCycleModal()">
                                <i class="fas fa-plus me-1"></i> Nuevo Ciclo
                            </button>
                        </div>
                        <div class="row g-4" id="cycles-list-container">
                            ${this.renderCycles()}
                        </div>
                    </div>

                    <!-- Tab 2: Master Templates -->
                    <div class="tab-pane fade" id="templates">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Catálogo de Plantillas Standard</h5>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="MinutasView.openNewTemplateModal()">
                                <i class="fas fa-plus me-1"></i> Crear Plantilla Flexible
                            </button>
                        </div>
                        <div class="row g-4" id="templates-list-container">
                            ${this.renderTemplates()}
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .nav-pills .nav-link { color: #5D6D7E; transition: all 0.3s ease; }
                .nav-pills .nav-link.active { background-color: var(--primary-custom); color: white; box-shadow: 0 4px 10px rgba(27, 79, 114, 0.2); }
                .card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; transition: all 0.3s; }
                .badge-status { font-size: 0.65rem; padding: 4px 10px; border-radius: 50px; text-transform: uppercase; }
            </style>
        `;

        document.getElementById('app').innerHTML = html;
    },

    renderCycles() {
        if (this.cycles.length === 0) {
            return `
                <div class="col-12">
                    <div class="text-center p-5 bg-white rounded shadow-sm border border-dashed">
                        <i class="fas fa-calendar-day fa-3x text-muted opacity-25 mb-3"></i>
                        <h6 class="text-muted fw-bold">No hay ciclos activos para el periodo actual</h6>
                        <button class="btn btn-sm btn-link text-primary mt-2" onclick="MinutasView.openNewCycleModal()">Generar mi primer ciclo ahora</button>
                    </div>
                </div>
            `;
        }
        return this.cycles.map(c => `
            <div class="col-md-4">
                <div class="card card-hover h-100 border-0 shadow-sm border-start ${c.status === 'BORRADOR' ? 'border-secondary' : 'border-success'} border-4">
                    <div class="card-body pb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge-status ${this.getStatusClass(c.status)}">${c.status}</span>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>${c.total_days} días</small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">${c.name}</h6>
                        <div class="text-xs text-muted mb-3">
                            <i class="fas fa-calendar me-1"></i> ${c.start_date} al ${c.end_date}
                        </div>
                        
                        ${c.status === 'BORRADOR' ? `
                            <button class="btn btn-success btn-xs px-3 fw-bold rounded-pill shadow-sm mt-1 w-100 py-1" 
                                    onclick="MinutasView.approveCycle(${c.id})">
                                <i class="fas fa-check-circle me-1"></i> Aprobar y Congelar
                            </button>
                        ` : `
                            <div class="bg-primary-light text-primary py-1 px-2 mt-1 mb-0 small border-0 text-center rounded-pill fw-bold" style="font-size: 0.7rem;">
                                <i class="fas fa-lock me-1"></i> DEMANDA CONGELADA (${c.projection_count || 0} ÍTEMS)
                            </div>
                        `}

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <span class="text-info small fw-bold" style="cursor: pointer;" onclick="MinutasView.viewCycle(${c.id})">
                                <i class="fas fa-eye me-1"></i> Ver Plan
                            </span>
                            <div class="btn-group">
                                <button class="btn btn-link text-primary btn-sm p-1" onclick="MinutasView.viewCycle(${c.id})" title="Ver Programación">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button class="btn btn-link text-secondary btn-sm p-1" onclick="MinutasView.printCycle(${c.id})" title="Imprimir Plan">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button class="btn btn-link text-success btn-sm p-1" onclick="MinutasView.downloadNeedsReport(${c.id})" title="Reporte de Compras (Excel)">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                                <button class="btn btn-link text-danger btn-sm p-1" 
                                        onclick="MinutasView.deleteCycle(${c.id}, '${c.status}')"
                                        ${c.status !== 'BORRADOR' ? 'disabled' : ''}
                                        title="${c.status !== 'BORRADOR' ? 'No se puede eliminar un ciclo activo' : 'Eliminar Ciclo'}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    },

    async viewCycle(id) {
        // Find name safely
        const cycle = this.cycles.find(c => c.id == id);
        const name = cycle ? cycle.name : 'Detalle del Ciclo';
        try {
            Helper.alert('info', 'Cargando programación...');
            const res = await Helper.fetchAPI(`/menu-cycles/${id}`);
            if (res.success) {
                this.showCycleDetailModal(id, res.data, name);
            } else {
                Helper.alert('error', 'No se pudo cargar la programación');
            }
        } catch (error) {
            Helper.alert('error', 'Error al cargar el ciclo');
        }
    },

    showCycleDetailModal(id, days, cycleName) {
        const modalId = 'cycleDetailModal';
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = modalId;

        let daysHtml = '';
        days.forEach(d => {
            const meals = d.meals.map(m => `
                <div class="mb-2 p-2 bg-light rounded border-start border-primary border-3 text-start">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold small text-primary">${m.meal_type}</span>
                    </div>
                    <div class="small fw-bold text-dark">${m.name}</div>
                </div>
            `).join('');

            daysHtml += `
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 border-top border-primary border-3">
                        <div class="card-header bg-white border-0 pb-0 text-center">
                            <h6 class="fw-bold mb-0 text-muted small">Día ${d.day}</h6>
                        </div>
                        <div class="card-body py-2">
                            ${meals}
                        </div>
                    </div>
                </div>
            `;
        });

        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white shadow-sm">
                        <h5 class="modal-title fw-bold"><i class="fas fa-calendar-alt me-2"></i>Programación: ${cycleName}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body bg-light p-4" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row g-3">
                            ${daysHtml}
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top shadow-sm">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" onclick="MinutasView.printCycle(${id})">
                            <i class="fas fa-print me-1"></i> Imprimir Plan
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();
        modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
    },

    printCycle(id) {
        window.open(`${Config.API_URL}/menu-cycles/print/${id}`, '_blank');
    },

    async deleteCycle(id, status) {
        if (status !== 'BORRADOR') {
            Helper.alert('warning', 'Solo se pueden eliminar ciclos en estado BORRADOR.');
            return;
        }

        const confirm = await Swal.fire({
            title: '¿Eliminar ciclo?',
            text: "Se borrarán todos sus días y menús asociados. Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (confirm.isConfirmed) {
            try {
                const res = await Helper.fetchAPI(`/menu-cycles/${id}`, { method: 'DELETE' });
                if (res.success) {
                    Helper.alert('success', 'Ciclo eliminado correctamente');
                    MinutasView.init();
                } else {
                    Helper.alert('error', res.message);
                }
            } catch (error) {
                Helper.alert('error', 'Error al eliminar el ciclo');
            }
        }
    },

    async approveCycle(id) {
        const confirm = await Swal.fire({
            title: '¿Aprobar Ciclo?',
            text: "Al aprobarlo se calculará la demanda total por sede basada en los beneficiarios actuales y se congelará para este ciclo. Esta acción NO se puede deshacer.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, aprobar y congelar',
            cancelButtonText: 'Cancelar'
        });

        if (confirm.isConfirmed) {
            try {
                Swal.fire({
                    title: 'Calculando Demanda...',
                    text: 'Realizando explosión de víveres para todas las sedes.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const res = await Helper.fetchAPI(`/menu-cycles/approve/${id}`, { method: 'POST' });

                if (res.success) {
                    Helper.alert('success', res.message);
                    this.init();
                } else {
                    Helper.alert('error', res.message);
                }
            } catch (error) {
                Helper.alert('error', 'Error durante la aprobación del ciclo');
            }
        }
    },

    renderTemplates() {
        if (this.templates.length === 0) {
            return `
                <div class="col-12">
                    <div class="text-center p-4 bg-light rounded border border-dashed">
                        <p class="text-muted small mb-0">Diseña plantillas estándar para no tener que elegir las recetas cada mes.</p>
                    </div>
                </div>
            `;
        }
        return this.templates.map(t => `
            <div class="col-md-4">
                <div class="card card-hover h-100 border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0">${t.name}</h6>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v fa-xs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item py-1 small" href="javascript:void(0)" onclick="MinutasView.editTemplate(${t.id})"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-1 small text-danger" href="javascript:void(0)" onclick="MinutasView.deleteTemplate(${t.id})"><i class="fas fa-trash me-2"></i>Eliminar</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">Plantilla maestra para periodos de alimentación regular.</p>
                        <button class="btn btn-primary btn-xs w-100 fw-bold shadow-sm" onclick="MinutasView.applyTemplate(${t.id})">
                            <i class="fas fa-magic me-1"></i> Aplicar a Nuevo Ciclo
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    },

    getStatusClass(status) {
        const maps = { 'BORRADOR': 'bg-secondary', 'ACTIVO': 'bg-success', 'FINALIZADO': 'bg-dark' };
        return maps[status] || 'bg-light';
    },

    async editTemplate(id) {
        try {
            const res = await Helper.fetchAPI(`/cycle-templates/${id}`);
            if (res.success) {
                this.openTemplateModal(res.data);
            }
        } catch (error) {
            Helper.alert('error', 'No se pudieron cargar los datos de la plantilla');
        }
    },

    openNewTemplateModal() {
        this.openTemplateModal();
    },

    openTemplateModal(template = null) {
        const isEdit = !!template;
        const modalId = 'templateEditorModal';
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = modalId;

        // Generate days based on input or default to 20
        const numDays = template ? Math.max(20, template.days.reduce((max, d) => Math.max(max, d.day_number), 0)) : 20;

        let rowsHtml = '';
        for (let i = 1; i <= numDays; i++) {
            let cellsHtml = '';
            this.rationTypes.forEach(rt => {
                const existing = template ? template.days.find(d => d.day_number == i && d.ration_type_id == rt.id) : null;
                cellsHtml += `
                    <td>
                        <select class="form-select form-select-sm" data-day="${i}" data-ration-id="${rt.id}" data-type="${rt.name}">
                            ${this.getRecipeOptions(existing ? existing.recipe_id : null)}
                        </select>
                    </td>
                `;
            });

            rowsHtml += `
                <tr id="template-day-row-${i}">
                    <td class="fw-bold text-center bg-light" style="width: 60px;">${i}</td>
                    ${cellsHtml}
                </tr>
            `;
        }

        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header ${isEdit ? 'bg-warning' : 'bg-primary'} text-white shadow-sm">
                        <h5 class="modal-title fw-bold">
                            <i class="fas ${isEdit ? 'fa-edit' : 'fa-plus-circle'} me-2"></i>
                            ${isEdit ? 'Editar Plantilla' : 'Nueva Plantilla Maestra'}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <div class="mb-4 bg-white p-3 rounded shadow-sm border">
                            <label class="form-label small fw-bold text-muted">NOMBRE DE LA PLANTILLA</label>
                            <input type="text" id="template-name" class="form-control fw-bold" 
                                   placeholder="Ej: Menú Estándar Primaria 2026" 
                                   value="${template ? template.name : ''}" 
                                   data-id="${template ? template.id : ''}">
                        </div>

                        <div class="table-responsive bg-white rounded shadow-sm border" style="max-height: 50vh;">
                            <table class="table table-bordered table-sm mb-0 align-middle">
                                <thead class="bg-light sticky-top">
                                    <tr class="text-center text-muted small text-uppercase">
                                        <th class="py-2">Día</th>
                                        ${this.rationTypes.map(rt => `<th class="py-2">${rt.name}</th>`).join('')}
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rowsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top shadow-sm justify-content-between">
                        <div>
                            <button type="button" class="btn btn-outline-info fw-bold" onclick="MinutasView.addDayToTemplate()">
                                <i class="fas fa-plus me-1"></i> Añadir un día
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary px-5 fw-bold shadow" onclick="MinutasView.saveTemplate()">
                                <i class="fas fa-save me-2"></i>${isEdit ? 'Actualizar Plantilla' : 'Guardar Plantilla'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();
        modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
    },

    getRecipeOptions(selectedId = null) {
        return `<option value="">-- Seleccionar --</option>` +
            this.recipes.map(r => `<option value="${r.id}" ${r.id == selectedId ? 'selected' : ''}>${r.name} (${r.kcal || 0} kcal)</option>`).join('');
    },

    addDayToTemplate() {
        const tbody = document.querySelector('#templateEditorModal tbody');
        const nextDay = tbody.children.length + 1;

        let cellsHtml = '';
        this.rationTypes.forEach(rt => {
            cellsHtml += `
                <td>
                    <select class="form-select form-select-sm" data-day="${nextDay}" data-ration-id="${rt.id}" data-type="${rt.name}">
                        ${this.getRecipeOptions()}
                    </select>
                </td>
            `;
        });

        const tr = document.createElement('tr');
        tr.id = `template-day-row-${nextDay}`;
        tr.innerHTML = `
            <td class="fw-bold text-center bg-light" style="width: 60px;">${nextDay}</td>
            ${cellsHtml}
        `;
        tbody.appendChild(tr);

        // Scroll to bottom
        const container = document.querySelector('#templateEditorModal .table-responsive');
        container.scrollTop = container.scrollHeight;
    },

    async saveTemplate() {
        const nameInput = document.getElementById('template-name');
        const templateId = nameInput.dataset.id;
        if (!nameInput.value.trim()) {
            Helper.alert('warning', 'Por favor asigne un nombre a la plantilla');
            nameInput.focus();
            return;
        }

        const days = [];
        document.querySelectorAll('#templateEditorModal select').forEach(select => {
            if (select.value) {
                days.push({
                    day_number: select.dataset.day,
                    ration_type_id: select.dataset.rationId,
                    meal_type: select.dataset.type, // Legacy Support
                    recipe_id: select.value
                });
            }
        });

        if (days.length === 0) {
            Helper.alert('warning', 'Añada al menos una receta a la plantilla');
            return;
        }

        const data = {
            name: nameInput.value.trim(),
            days: days
        };

        try {
            const method = templateId ? 'PUT' : 'POST';
            const url = templateId ? `/cycle-templates/${templateId}` : '/cycle-templates';
            const res = await Helper.fetchAPI(url, { method, body: JSON.stringify(data) });

            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('templateEditorModal')).hide();
                Helper.alert('success', templateId ? 'Plantilla actualizada' : 'Plantilla guardada');
                MinutasView.init();
            } else {
                Helper.alert('error', res.message);
            }
        } catch (error) {
            Helper.alert('error', 'Error al guardar la plantilla');
        }
    },

    async deleteTemplate(id) {
        const confirm = await Swal.fire({
            title: '¿Eliminar plantilla?',
            text: "Esta acción no afectará a los ciclos ya generados con ella.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (confirm.isConfirmed) {
            try {
                const res = await Helper.fetchAPI(`/cycle-templates/${id}`, { method: 'DELETE' });
                if (res.success) {
                    Helper.alert('success', 'Plantilla eliminada');
                    MinutasView.init();
                } else {
                    Helper.alert('error', res.message);
                }
            } catch (error) {
                Helper.alert('error', 'Error al eliminar la plantilla');
            }
        }
    },

    applyTemplate(id) {
        this.openNewCycleModal(id);
    },

    openNewCycleModal(preSelectedTemplateId = null) {
        const modalId = 'newCycleModal';
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = modalId;

        // Default dates
        const today = new Date().toISOString().split('T')[0];
        const nextMonth = new Date(Date.now() + 27 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

        modalDiv.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="fas fa-calendar-plus me-2"></i>Programar Nuevo Ciclo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="new-cycle-form">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NOMBRE DEL CICLO / PERIODO</label>
                                <input type="text" name="name" class="form-control" placeholder="Ej: Ciclo Febrero Primaria - 2026" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">FECHA DE INICIO</label>
                                    <input type="date" name="start_date" class="form-control" value="${today}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">FECHA DE FIN</label>
                                    <input type="date" name="end_date" class="form-control" value="${nextMonth}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">PLANTILLA BASE</label>
                                <select name="template_id" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    ${this.templates.map(t => `<option value="${t.id}" ${t.id == preSelectedTemplateId ? 'selected' : ''}>${t.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="bg-light p-3 rounded border">
                                <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i> El sistema generará menús para cada día hábil entre las fechas seleccionadas.</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" onclick="MinutasView.generateCycle()">
                            <i class="fas fa-magic me-1"></i> Generar Ciclo Completo
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();
        modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
    },

    async generateCycle() {
        const form = document.querySelector('#new-cycle-form');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            template_id: formData.get('template_id')
        };

        try {
            Helper.alert('info', 'Generando programación de menús...');
            const res = await Helper.fetchAPI('/menu-cycles/generate', {
                method: 'POST',
                body: JSON.stringify(data)
            });

            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('newCycleModal')).hide();
                Helper.alert('success', 'Ciclo generado exitosamente');
                MinutasView.init();
            } else {
                Helper.alert('error', res.message);
            }
        } catch (error) {
            Helper.alert('error', 'Error al generar el ciclo');
        }
    },

    async downloadNeedsReport(id) {
        const result = await Swal.fire({
            title: 'Reporte de Insumos',
            text: 'Seleccione el formato:',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="fas fa-file-excel"></i> Excel',
            confirmButtonColor: '#218838',
            denyButtonText: '<i class="fas fa-print"></i> PDF / Imprimir',
            denyButtonColor: '#17a2b8',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed && !result.isDenied) return;

        try {
            Helper.alert('info', 'Cargando datos...');
            const res = await Helper.fetchAPI(`/reports/needs/${id}`);

            if (!res.success) {
                Helper.alert('error', res.message || 'Error al generar reporte');
                return;
            }

            if (result.isConfirmed) {
                this._generateExcel(res);
            } else if (result.isDenied) {
                this._generatePrintView(res);
            }

            Swal.close();

        } catch (error) {
            console.error(error);
            Helper.alert('error', 'Error al descargar el reporte');
        }
    },

    _generateExcel(res) {
        let header = '<tr><th style="background:#f0f0f0;">INSUMO</th><th style="background:#f0f0f0;">UNIDAD</th>';
        const branchIds = Object.keys(res.branches || {});

        branchIds.forEach(bid => {
            header += `<th style="background:#f0f0f0;">${res.branches[bid]}</th>`;
        });
        header += '<th style="background:#e0e0e0; font-weight:bold;">TOTAL NECESIDAD</th></tr>';

        let body = '';
        const formatNumber = (num) => {
            if (num === 0 || num === null) return '0';
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
        };

        res.data.forEach(item => {
            let row = `<tr><td>${item.name}</td><td>${item.unit}</td>`;
            branchIds.forEach(bid => {
                const qty = formatNumber(item.branches[bid] || 0);
                row += `<td>${qty}</td>`;
            });
            const total = formatNumber(item.grand_total || 0);
            row += `<td style="background:#f9f9f9; font-weight:bold;">${total}</td></tr>`;
            body += row;
        });

        const html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8"></head>
            <body>
                <h3>EXPLOSION DE INSUMOS - ${res.cycle.name}</h3>
                <p>Periodo: ${res.cycle.start_date} al ${res.cycle.end_date}</p>
                <table border="1" cellspacing="0" cellpadding="5">${header}${body}</table>
            </body>
            </html>
        `;

        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Explosion_Insumos_${res.cycle.start_date}.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        Helper.alert('success', 'Excel generado correctamente');
    },

    _generatePrintView(res) {
        let header = '<tr><th>INSUMO</th><th>UNIDAD</th>';
        const branchIds = Object.keys(res.branches || {});

        branchIds.forEach(bid => {
            header += `<th>${res.branches[bid]}</th>`;
        });
        header += '<th class="fw-bold bg-light">TOTAL</th></tr>';

        let body = '';
        const formatNumber = (num) => {
            if (num === 0 || num === null) return '-';
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
        };

        res.data.forEach(item => {
            let row = `<tr><td>${item.name}</td><td class="text-center">${item.unit}</td>`;
            branchIds.forEach(bid => {
                const qty = formatNumber(item.branches[bid] || 0);
                row += `<td class="text-end">${qty}</td>`;
            });
            const total = formatNumber(item.grand_total || 0);
            row += `<td class="text-end fw-bold bg-light">${total}</td></tr>`;
            body += row;
        });

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Explosión de Insumos - ${res.cycle.name}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: sans-serif; padding: 20px; font-size: 11px; }
                    h2, h4 { text-align: center; margin-bottom: 5px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #ddd; padding: 4px; }
                    th { background-color: #f8f9fa; text-align: center; font-weight: bold; }
                    .header-info { margin-bottom: 20px; text-align: center; }
                    @media print {
                        .no-print { display: none !important; }
                        table { font-size: 9px; }
                        @page { size: landscape; margin: 10mm; }
                    }
                </style>
            </head>
            <body>
                <div class="no-print mb-3 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Use la opción "Guardar como PDF" en el destino de impresión.
                    </div>
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimir / Guardar PDF</button>
                    <button class="btn btn-secondary" onclick="window.close()">Cerrar</button>
                </div>
                <div class="header-info">
                    <h2>EXPLOSIÓN DE INSUMOS</h2>
                    <h4>Ciclo: ${res.cycle.name}</h4>
                    <p>Periodo: ${res.cycle.start_date} al ${res.cycle.end_date}</p>
                </div>
                <table class="table table-bordered table-sm table-striped">
                    <thead>${header}</thead>
                    <tbody>${body}</tbody>
                </table>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
};

// Initialize
if (typeof MinutasView !== 'undefined') {
    MinutasView.init();
}
