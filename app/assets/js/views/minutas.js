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
                Helper.fetchAPI('/menu-cycles?_=' + new Date().getTime()),
                Helper.fetchAPI('/recipes')
            ]);
            this.templates = tempRes.success ? tempRes.data : [];
            this.cycles = cycleRes.success ? cycleRes.data : [];
            this.recipes = recipeRes.success ? recipeRes.data : [];
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

    async viewCycle(id) {
        try {
            // 1. Obtener datos del ciclo y sus menús (días)
            // Necesitamos un endpoint que retorne detalle de ciclo + todos sus días + items asignados
            // Por ahora usaremos fetchAPI a una ruta que construiremos o asumiremos
            // Si no existe endpoint específico, quizás debamos crearlo o usar uno genérico
            // Supongamos GET /api/menu-cycles/{id} retorna { cycle: ..., menus: [ {date:..., items:[...]} ] }

            // Nota: El endpoint show actual no parece estar definido en controller listado antes,
            // pero podemos verificarlo o crearlo.
            // MenuCycleController no tenía show() en el listado inicial, solo index, store, generate, delete.
            // Asumiremos que necesitamos implementar show() en backend primero.

            // Workaround temporal: Si no implementé show(), no puedo obtener los días.
            // Voy a implementar primero el cambio en backend para devolver los datos completos.

            const res = await Helper.fetchAPI(`/menu-cycles/${id}`);
            if (!res.success) {
                Helper.alert('error', 'No se pudo cargar el detalle del ciclo');
                return;
            }
            const cycleData = res.data;

            this.openCycleDetailModal(cycleData);

        } catch (error) {
            console.error(error);
            Helper.alert('error', 'Error al cargar detalles del ciclo');
        }
    },

    openCycleDetailModal(cycle) {
        const modalId = 'cycleDetailModal';
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = modalId;

        const breakfasts = this.recipes.filter(r => r.meal_type === 'DESAYUNO');
        const lunches = this.recipes.filter(r => r.meal_type === 'ALMUERZO');

        let rows = '';
        if (cycle.menus && cycle.menus.length > 0) {
            rows = cycle.menus.map(menu => {
                // Encontrar items actuales
                const bItem = menu.items.find(i => i.meal_type === 'DESAYUNO');
                const lItem = menu.items.find(i => i.meal_type === 'ALMUERZO');
                const bId = bItem ? bItem.recipe_id : '';
                const lId = lItem ? lItem.recipe_id : '';

                return `
                <tr data-menu-id="${menu.id}">
                    <td class="align-middle">
                        <div class="fw-bold text-dark">${menu.day_number}</div>
                    </td>
                    <td class="align-middle">
                        <div class="small fw-bold">${menu.day_name || ''}</div>
                        <div class="text-xs text-muted">${menu.date}</div>
                    </td>
                    <td>
                        <select class="form-select form-select-sm border-0 border-bottom bg-transparent menu-select" data-type="DESAYUNO">
                            <option value="">-- Sin Asignar --</option>
                            ${breakfasts.map(r => `<option value="${r.id}" ${r.id == bId ? 'selected' : ''}>${r.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm border-0 border-bottom bg-transparent menu-select" data-type="ALMUERZO">
                            <option value="">-- Sin Asignar --</option>
                            ${lunches.map(r => `<option value="${r.id}" ${r.id == lId ? 'selected' : ''}>${r.name}</option>`).join('')}
                        </select>
                    </td>
                </tr>
                `;
            }).join('');
        } else {
            rows = '<tr><td colspan="4" class="text-center text-muted">No hay días generados</td></tr>';
        }

        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white py-3">
                        <div>
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Detalle del Ciclo
                            </h5>
                            <small class="opacity-75">${cycle.name} (${cycle.start_date} - ${cycle.end_date})</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                         <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light sticky-top shadow-sm" style="top: 0; z-index: 5;">
                                    <tr class="small text-uppercase text-muted fw-bold">
                                        <th class="ps-3 border-0" style="width: 50px;">#</th>
                                        <th class="border-0" style="width: 150px;">Fecha</th>
                                        <th class="border-0">Desayuno</th>
                                        <th class="border-0">Almuerzo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    ${rows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <span class="text-muted small me-auto"><i class="fas fa-info-circle me-1"></i> Los cambios se guardan globalmente al pulsar Guardar.</span>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary px-5 fw-bold shadow-sm" onclick="MinutasView.saveCycleDetails(${cycle.id})">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
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

    async saveCycleDetails(cycleId) {
        // Recolectar datos
        const changes = [];
        document.querySelectorAll('#cycleDetailModal tr[data-menu-id]').forEach(tr => {
            const menuId = tr.dataset.menuId;
            const selects = tr.querySelectorAll('select');
            const items = [];
            selects.forEach(sel => {
                if (sel.value) {
                    items.push({
                        recipe_id: sel.value,
                        meal_type: sel.dataset.type
                    });
                }
            });
            changes.push({
                menu_id: menuId,
                items: items
            });
        });

        try {
            const res = await Helper.fetchAPI(`/menu-cycles/${cycleId}/items`, {
                method: 'PUT',
                body: JSON.stringify({ days: changes })
            });

            if (res.success) {
                Helper.alert('success', 'Programación del ciclo actualizada correctamente');
                bootstrap.Modal.getInstance(document.getElementById('cycleDetailModal')).hide();
                // Opcional: Recargar lista
            } else {
                Helper.alert('error', res.message);
            }

        } catch (error) {
            Helper.alert('error', 'Error al guardar cambios');
        }
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
                    Helper.alert('error', res.message || 'Error desconocido al eliminar ciclo');
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
            <div class="modal-dialog modal-lg">
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
                                    <label class="form-label small fw-bold text-muted">RANGO DE FECHAS</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="${new Date().toISOString().split('T')[0]}" required>
                                        <span class="input-group-text">a</span>
                                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="exclude_weekends" id="exclude_weekends" checked>
                                        <label class="form-check-label small" for="exclude_weekends">
                                            Omitir Sábados y Domingos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">MODO DE GENERACIÓN</label>
                                    <div class="d-flex flex-column gap-2 border p-2 rounded">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mode" id="mode_rotative" value="ROTATIVE" checked onchange="MinutasView.toggleTemplateSelect(true)">
                                            <label class="form-check-label small" for="mode_rotative">
                                                <strong>Rotativo (Secuencial)</strong><br>
                                                <span class="text-muted text-xs">Asigna días de la plantilla en orden (1, 2, 3...) y repite.</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mode" id="mode_random" value="RANDOM" onchange="MinutasView.toggleTemplateSelect(true)">
                                            <label class="form-check-label small" for="mode_random">
                                                <strong>Aleatorio</strong><br>
                                                <span class="text-muted text-xs">Mezcla los menús de la plantilla para más variedad.</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mode" id="mode_manual" value="MANUAL" onchange="MinutasView.toggleTemplateSelect(false)">
                                            <label class="form-check-label small" for="mode_manual">
                                                <strong>Manual (En Blanco)</strong><br>
                                                <span class="text-muted text-xs">Genera los días vacíos para elegir plato por plato.</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="template-select-container">
                                <label class="form-label small fw-bold text-muted">PLANTILLA BASE</label>
                                <select name="template_id" id="template_id" class="form-select" required>
                                    <option value="">-- Seleccionar Plantilla --</option>
                                    ${this.templates.map(t => `<option value="${t.id}">${t.name}</option>`).join('')}
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" onclick="MinutasView.generateCycle()">
                            <i class="fas fa-magic me-1"></i> Generar Ciclo
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalDiv);

        // Set default end date (start date + 20 days approx)
        const today = new Date();
        today.setDate(today.getDate() + 28); // +4 weeks
        document.getElementById('end_date').value = today.toISOString().split('T')[0];

        const modal = new bootstrap.Modal(modalDiv);
        modal.show();
        modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
    },

    toggleTemplateSelect(show) {
        const container = document.getElementById('template-select-container');
        const select = document.getElementById('template_id');
        if (show) {
            container.style.display = 'block';
            select.setAttribute('required', 'required');
        } else {
            container.style.display = 'none';
            select.removeAttribute('required');
            select.value = "";
        }
    },

    async generateCycle() {
        const form = document.querySelector('#new-cycle-form');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            mode: formData.get('mode'),
            template_id: formData.get('template_id'),
            exclude_weekends: formData.get('exclude_weekends') === 'on'
        };

        try {
            Helper.alert('info', 'Generando ciclo programado...');
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
