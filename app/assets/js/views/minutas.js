/**
 * Minutas View - Menu Cycles & Templates Module
 */

window.MinutasView = {
    templates: [],
    cycles: [],
    recipes: [],

    async init() {
        console.log('Initializing Minutas Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [tempRes, cycleRes, recipeRes] = await Promise.all([
                Helper.fetchAPI('/cycle-templates'),
                Helper.fetchAPI('/menu-cycles'),
                Helper.fetchAPI('/recipes')
            ]);
            this.templates = tempRes.success ? tempRes.data : [];
            this.cycles = cycleRes.success ? cycleRes.data : [];
            this.recipes = recipeRes || []; // Recipes returns array directly
        } catch (error) {
            console.error('Error loading minutas data:', error);
        }
    },

    render() {
        const html = `
            <div class="container-fluid py-4 text-dark">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div>
                        <h2 class="mb-1 text-primary-custom fw-bold"><i class="fas fa-calendar-alt me-2"></i>Gestión de Minutas</h2>
                        <p class="text-muted mb-0">Planeación de ciclos de 20 días y plantillas reutilizables</p>
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
                                <i class="fas fa-plus me-1"></i> Crear Plantilla 20 Días
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
                <div class="card card-hover h-100 border-0 shadow-sm border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge-status ${this.getStatusClass(c.status)}">${c.status}</span>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>${c.total_days} días</small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">${c.name}</h6>
                        <div class="text-xs text-muted mb-3">
                            <i class="fas fa-calendar me-1"></i> ${c.start_date} al ${c.end_date}
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="text-success small fw-bold">
                                <i class="fas fa-check-circle me-1"></i> Nutrición Validada
                            </span>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-xs px-2" onclick="MinutasView.viewCycle(${c.id})"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-outline-secondary btn-xs px-2"><i class="fas fa-print"></i></button>
                                <button class="btn btn-outline-danger btn-xs px-2" 
                                        onclick="MinutasView.deleteCycle(${c.id}, '${c.status}', ${c.is_validated})"
                                        title="${c.status !== 'BORRADOR' || c.is_validated ? 'No se puede eliminar (Ya activo o validado)' : 'Eliminar Ciclo'}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    },

    viewCycle(id) {
        // Redirigir o abrir detalle del ciclo
        console.log('Viewing cycle:', id);
        // Por ahora abriremos una alerta o modal de detalle
        Helper.alert('info', 'Módulo de ejecución diaria para el ciclo #' + id + ' en desarrollo.');
    },

    async deleteCycle(id, status, isValidated) {

        const confirm = await Swal.fire({
            title: '¿Eliminar ciclo?',
            text: "Se borrarán todos los días y entregas programadas de este ciclo.",
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
                        <p class="text-muted small mb-3">Plantilla estándar de 20 días para regímenes regulares.</p>
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

        const breakfasts = this.recipes.filter(r => r.meal_type === 'DESAYUNO');
        const lunches = this.recipes.filter(r => r.meal_type === 'ALMUERZO');

        let daysHtml = '';
        for (let i = 1; i <= 20; i++) {
            let bId = '', lId = '';
            if (isEdit && template.days) {
                const b = template.days.find(d => d.day_number == i && d.meal_type === 'DESAYUNO');
                const l = template.days.find(d => d.day_number == i && d.meal_type === 'ALMUERZO');
                if (b) bId = b.recipe_id;
                if (l) lId = l.recipe_id;
            }

            daysHtml += `
                <tr class="align-middle">
                    <td class="text-center fw-bold text-muted bg-light" style="width: 80px;">Día ${i}</td>
                    <td>
                        <select class="form-select form-select-sm border-0 border-bottom bg-transparent" data-day="${i}" data-type="DESAYUNO">
                            <option value="">-- Seleccionar Desayuno --</option>
                            ${breakfasts.map(r => `<option value="${r.id}" ${r.id == bId ? 'selected' : ''}>${r.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm border-0 border-bottom bg-transparent" data-day="${i}" data-type="ALMUERZO">
                            <option value="">-- Seleccionar Almuerzo --</option>
                            ${lunches.map(r => `<option value="${r.id}" ${r.id == lId ? 'selected' : ''}>${r.name}</option>`).join('')}
                        </select>
                    </td>
                </tr>
            `;
        }

        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white py-3">
                        <div>
                            <h5 class="modal-title fw-bold mb-0">
                                <i class="fas ${isEdit ? 'fa-edit' : 'fa-layer-group'} me-2"></i>
                                ${isEdit ? 'Editar Plantilla: ' + template.name : 'Nueva Plantilla de Ciclo (20 Días)'}
                            </h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="p-4 border-bottom bg-light sticky-top" style="z-index: 10;">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nombre de la Plantilla</label>
                            <input type="text" id="template-name" class="form-control form-control-lg border-2 shadow-sm" 
                                   value="${isEdit ? template.name : ''}"
                                   placeholder="Ej: Ciclo Regular - 2026" required data-id="${isEdit ? template.id : ''}">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-white sticky-top shadow-sm" style="top: 0; z-index: 5;">
                                    <tr class="small text-uppercase text-muted fw-bold">
                                        <th class="ps-3 border-0">Jornada</th>
                                        <th class="border-0">Receta Desayuno</th>
                                        <th class="border-0 text-primary"><i class="fas fa-utensils me-1"></i>Receta Almuerzo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${daysHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary px-5 fw-bold shadow" onclick="MinutasView.saveTemplate()">
                            <i class="fas fa-save me-2"></i>${isEdit ? 'Actualizar Plantilla' : 'Guardar Plantilla'}
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
                    meal_type: select.dataset.type,
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

    openNewCycleModal() {
        const modalId = 'newCycleModal';
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = modalId;

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
                                    <input type="date" name="start_date" class="form-control" value="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">PLANTILLA BASE</label>
                                    <select name="template_id" class="form-select" required>
                                        <option value="">-- Seleccionar --</option>
                                        ${this.templates.map(t => `<option value="${t.id}">${t.name}</option>`).join('')}
                                    </select>
                                </div>
                            </div>
                            <div class="bg-light p-3 rounded border">
                                <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i> El sistema generará automáticamente 20 días hábiles omitiendo fines de semana.</small>
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
            template_id: formData.get('template_id')
        };

        try {
            Helper.alert('info', 'Generando programación de 20 días...');
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
    }
};

// Initialize
if (typeof MinutasView !== 'undefined') {
    MinutasView.init();
}
