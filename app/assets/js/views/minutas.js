/**
 * Minutas View - Menu Planning Module
 * Resolution 0003 of 2026 Compliance
 */

window.MinutasView = {
    cycles: [],
    selectedCycle: null,
    days: [],
    allItems: [], // Lista completa de ítems para el selector

    async init() {
        console.log('Initializing Minutas Module...');
        await this.loadCycles();
        await this.loadItems();
        this.render();
        this.attachEvents();
    },

    async loadItems() {
        try {
            const response = await Helper.fetchAPI('/items');
            if (response.success) {
                this.allItems = response.data;
            }
        } catch (error) {
            console.error('Error loading items:', error);
        }
    },

    async loadCycles() {
        try {
            const response = await Helper.fetchAPI('/menu-cycles');
            if (response.success) {
                this.cycles = response.data;
                if (this.cycles.length > 0) {
                    this.selectedCycle = this.cycles[0];
                    await this.loadCycleDays(this.selectedCycle.id);
                }
            }
        } catch (error) {
            console.error('Error loading cycles:', error);
        }
    },

    async loadCycleDays(cycleId) {
        console.log('Loading days for cycle:', cycleId);
        try {
            const response = await Helper.fetchAPI(`/menu-cycles/${cycleId}`);
            console.log('Cycle days response:', response);
            if (response.success) {
                this.days = response.data || [];
            } else {
                this.days = [];
                Helper.alert('warning', 'No se pudieron cargar los días: ' + (response.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error loading cycle days:', error);
            this.days = [];
        }
    },

    render() {
        const html = `
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1"><i class="fas fa-calendar-alt me-2 text-primary"></i>Planeación de Minutas</h2>
                        <p class="text-muted mb-0">Gestión de ciclos de menús y derivación etárea</p>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select" id="cycle-selector" style="min-width: 250px;">
                            ${this.cycles.map(c => `
                                <option value="${c.id}" ${this.selectedCycle?.id == c.id ? 'selected' : ''}>
                                    ${c.name} (${c.status})
                                </option>
                            `).join('')}
                        </select>
                        <button class="btn btn-primary" onclick="MinutasView.openNewCycleModal()">
                            <i class="fas fa-plus me-2"></i>Nuevo Ciclo
                        </button>
                    </div>
                </div>

                <div class="row g-4" id="cycle-grid-container">
                    ${this.renderCycleGrid()}
                </div>
            </div>

            <!-- Modal Detalle Menú -->
            <div class="modal fade" id="menuDetailModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-utensils me-2"></i>Detalle de Minuta</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0" id="menu-detail-content">
                            <!-- Loading spinner -->
                            <div class="text-center p-5">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('app').innerHTML = html;
    },

    renderCycleGrid() {
        if (this.days.length === 0) {
            return `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4>No hay días programados para este ciclo</h4>
                </div>
            `;
        }

        return this.days.map(day => `
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 hover-lift">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                        <span class="fw-bold">Día ${day.day}</span>
                        <span class="badge bg-secondary">Ciclo 20 Días</span>
                    </div>
                    <div class="card-body p-3">
                        ${day.meals.map(meal => `
                            <div class="meal-item p-2 mb-2 rounded border-start border-4 ${meal.meal_type === 'DESAYUNO' ? 'border-warning' : 'border-success'}" 
                                 role="button" onclick="MinutasView.showMenuDetail(${meal.id})">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted fw-bold">${meal.meal_type}</small>
                                    <i class="fas fa-chevron-right text-muted x-small"></i>
                                </div>
                                <div class="text-truncate" title="${meal.name}">${meal.name}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `).join('');
    },

    async showMenuDetail(menuId) {
        const modal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
        modal.show();

        try {
            const response = await Helper.fetchAPI(`/menus/${menuId}`);
            if (response.success) {
                this.renderMenuDetail(response.data);
            }
        } catch (error) {
            Helper.alert('error', 'No se pudo cargar el detalle del menú');
        }
    },

    renderMenuDetail(menu) {
        const content = `
            <div class="p-4">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h3 class="text-primary mb-1">${menu.name}</h3>
                        <p class="text-muted"><i class="fas fa-calendar-day me-2"></i>Día ${menu.day_number} | ${menu.meal_type}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-info text-dark p-2">
                            ${menu.age_group || 'TODOS'}
                        </span>
                    </div>
                </div>

                <div class="card bg-light border-0 mb-4">
                    <div class="card-body py-3">
                        <h6 class="card-title text-muted text-uppercase small fw-bold mb-3">Composición de Insumos (Explosión de Víveres)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead class="border-bottom">
                                    <tr>
                                        <th>Ingrediente</th>
                                        <th class="text-center">Cant. Patrón</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${menu.items.map(i => `
                                        <tr>
                                            <td>
                                                <div class="fw-bold">${i.item_name}</div>
                                                <small class="text-muted">${i.food_group}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-white text-dark border">${i.standard_quantity} ${i.unit}</span>
                                            </td>
                                            <td class="small text-muted">${i.preparation_method || '-'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="small text-muted text-uppercase fw-bold mb-2">Información Nutricional</h6>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Calorías:</span>
                                <strong>${parseFloat(menu.total_calories || 0).toFixed(2)} kcal</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Proteínas:</span>
                                <strong>${parseFloat(menu.total_proteins || 0).toFixed(2)} g</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="small text-muted text-uppercase fw-bold mb-2">Validación Resolución 0003</h6>
                            ${(() => {
                const hasProtein = menu.items.some(i => i.food_group.toLowerCase().includes('proteic'));
                const hasCereal = menu.items.some(i => i.food_group.toLowerCase().includes('cereal') || i.food_group.toLowerCase().includes('tubérculo'));
                const hasDairy = menu.items.some(i => i.food_group.toLowerCase().includes('lácteo') || i.food_group.toLowerCase().includes('lacteo'));

                return `
                                    <div class="${hasProtein ? 'text-success' : 'text-danger'} small mb-1">
                                        <i class="fas ${hasProtein ? 'fa-check-circle' : 'fa-times-circle'} me-1"></i> 
                                        ${hasProtein ? 'Contiene Proteína' : 'Falta Proteína'}
                                    </div>
                                    <div class="${hasCereal ? 'text-success' : 'text-danger'} small mb-1">
                                        <i class="fas ${hasCereal ? 'fa-check-circle' : 'fa-times-circle'} me-1"></i> 
                                        ${hasCereal ? 'Contiene Cereal/Tubérculo' : 'Falta Cereal/Tubérculo'}
                                    </div>
                                    <div class="${hasDairy ? 'text-success' : 'text-danger'} small">
                                        <i class="fas ${hasDairy ? 'fa-check-circle' : 'fa-times-circle'} me-1"></i> 
                                        ${hasDairy ? 'Contiene Lácteo' : 'Falta Lácteo'}
                                    </div>
                                `;
            })()}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-outline-primary" onclick="MinutasView.editMenu(${menu.id})">
                    <i class="fas fa-edit me-1"></i> Editar Planeación
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        `;
        document.getElementById('menu-detail-content').innerHTML = content;
    },

    attachEvents() {
        // Usar delegación de eventos en el contenedor principal para que no se pierdan al renderizar
        $(document).off('change', '#cycle-selector').on('change', '#cycle-selector', async (e) => {
            const cycleId = e.target.value;
            this.selectedCycle = this.cycles.find(c => c.id == cycleId);

            // Mostrar loading en el grid
            document.getElementById('cycle-grid-container').innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Cargando días del ciclo...</p>
                </div>
            `;

            await this.loadCycleDays(cycleId);
            document.getElementById('cycle-grid-container').innerHTML = this.renderCycleGrid();
        });
    },

    editMenu(id) {
        // Cerrar el modal de detalle primero
        bootstrap.Modal.getInstance(document.getElementById('menuDetailModal')).hide();

        // Cargar datos actuales
        Helper.fetchAPI(`/menus/${id}`).then(response => {
            if (response.success) {
                this.renderEditModal(response.data);
            }
        });
    },

    renderEditModal(menu) {
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = 'editMenuModal';
        modalDiv.innerHTML = `
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Planeación - Día ${menu.day_number}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="edit-menu-form">
                            <input type="hidden" name="id" value="${menu.id}">
                            <input type="hidden" name="day_number" value="${menu.day_number}">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Nombre del Plato</label>
                                    <input type="text" class="form-control" name="name" value="${menu.name}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">Tipo de Comida</label>
                                    <select class="form-select" name="meal_type">
                                        <option value="DESAYUNO" ${menu.meal_type === 'DESAYUNO' ? 'selected' : ''}>DESAYUNO</option>
                                        <option value="ALMUERZO" ${menu.meal_type === 'ALMUERZO' ? 'selected' : ''}>ALMUERZO</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">Grupo de Edad</label>
                                    <select class="form-select" name="age_group">
                                        <option value="PREESCOLAR" ${menu.age_group === 'PREESCOLAR' ? 'selected' : ''}>PREESCOLAR</option>
                                        <option value="PRIMARIA" ${menu.age_group === 'PRIMARIA' ? 'selected' : ''}>PRIMARIA</option>
                                        <option value="BACHILLERATO" ${menu.age_group === 'BACHILLERATO' ? 'selected' : ''}>BACHILLERATO</option>
                                        <option value="TODOS" ${menu.age_group === 'TODOS' || !menu.age_group ? 'selected' : ''}>TODOS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card border">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                    <h6 class="mb-0 fw-bold small text-uppercase">Ingredientes e Insumos</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="MinutasView.addIngredientRow()">
                                        <i class="fas fa-plus me-1"></i>Añadir Ingrediente
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-hover mb-0" id="edit-ingredients-table">
                                        <thead class="bg-light small">
                                            <tr>
                                                <th class="ps-3" style="width: 40%;">Ítem / Ingrediente</th>
                                                <th class="text-center">Cant. Patrón</th>
                                                <th>Unidad</th>
                                                <th>Observaciones Preparación</th>
                                                <th class="text-center">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ingredients-tbody">
                                            ${menu.items.map((i, index) => this.renderIngredientRow(i, index)).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary px-4" onclick="MinutasView.saveMenuChanges(${menu.id})">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modalDiv.addEventListener('hidden.bs.modal', function () {
            modalDiv.remove();
        });
        modal.show();
    },

    renderIngredientRow(item = null, index = 0) {
        const id = index || Date.now();
        return `
            <tr id="row-${id}">
                <td class="ps-3">
                    <select class="form-select form-select-sm select-item" name="item_id" onchange="MinutasView.updateUnitLabel(this, ${id})">
                        <option value="">Seleccione un ítem...</option>
                        ${this.allItems.map(ai => `
                            <option value="${ai.id}" 
                                    data-unit="${ai.unit_abbr}"
                                    ${item?.item_id == ai.id ? 'selected' : ''}>
                                ${ai.name} (${ai.code})
                            </option>
                        `).join('')}
                    </select>
                </td>
                <td class="text-center" style="width: 15%;">
                    <input type="number" step="0.01" class="form-control form-control-sm text-center" name="quantity" value="${item?.standard_quantity || 0}">
                </td>
                <td class="align-middle border-start border-end">
                    <span class="badge bg-light text-dark unit-label">${item?.unit || '-'}</span>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="preparation" value="${item?.preparation_method || ''}" placeholder="Ej: Cocido, Salteado...">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
    },

    addIngredientRow() {
        const tbody = document.getElementById('ingredients-tbody');
        const tempId = Date.now();
        const rowHtml = this.renderIngredientRow(null, tempId);
        tbody.insertAdjacentHTML('beforeend', rowHtml);
    },

    updateUnitLabel(select, rowId) {
        const option = select.options[select.selectedIndex];
        const unit = option.dataset.unit || '-';
        document.querySelector(`#row-${rowId} .unit-label`).textContent = unit;
    },

    async saveMenuChanges(menuId) {
        const form = document.getElementById('edit-menu-form');
        const formData = new FormData(form);

        const basicData = {
            name: formData.get('name'),
            meal_type: formData.get('meal_type'),
            age_group: formData.get('age_group'),
            day_number: formData.get('day_number') || 1
        };

        const ingredientsRows = document.querySelectorAll('#ingredients-tbody tr');
        const ingredients = [];
        ingredientsRows.forEach(row => {
            const itemId = row.querySelector('[name="item_id"]').value;
            const quantity = row.querySelector('[name="quantity"]').value;
            const preparation = row.querySelector('[name="preparation"]').value;

            if (itemId && quantity > 0) {
                ingredients.push({
                    item_id: itemId,
                    quantity: quantity,
                    preparation: preparation
                });
            }
        });

        try {
            // 1. Guardar datos básicos
            const basicResponse = await Helper.fetchAPI(`/menus/${menuId}`, {
                method: 'PUT',
                body: JSON.stringify(basicData)
            });

            // 2. Guardar ingredientes
            const itemsResponse = await Helper.fetchAPI(`/menus/${menuId}/items`, {
                method: 'POST',
                body: JSON.stringify({ items: ingredients })
            });

            if (basicResponse.success && itemsResponse.success) {
                bootstrap.Modal.getInstance(document.getElementById('editMenuModal')).hide();
                Helper.alert('success', 'Minuta actualizada correctamente');

                // Recargar datos
                await this.loadCycleDays(this.selectedCycle.id);
                const gridContainer = document.getElementById('cycle-grid-container');
                if (gridContainer) {
                    gridContainer.innerHTML = this.renderCycleGrid();
                }
            } else {
                Helper.alert('error', 'Error al actualizar algunos datos');
            }
        } catch (error) {
            console.error('Error saving menu:', error);
            Helper.alert('error', 'Error al guardar los cambios');
        }
    },

    openNewCycleModal() {
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = 'newCycleModal';
        modalDiv.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Crear Nuevo Ciclo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="new-cycle-form">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre del Ciclo</label>
                                <input type="text" class="form-control" name="name" placeholder="Ej: CICLO 2 - MARZO 2026" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Descripción</label>
                                <textarea class="form-control" name="description" rows="2" placeholder="Opcional..."></textarea>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small">Fecha Inicio</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small">Fecha Fin</label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                            </div>
                            <div class="alert alert-info small py-2 mb-0">
                                <i class="fas fa-magic me-1"></i> Se generarán automáticamente 20 días con Desayuno y Almuerzo.
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="MinutasView.saveNewCycle()">
                            <i class="fas fa-save me-1"></i> Crear Ciclo
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();

        modalDiv.addEventListener('hidden.bs.modal', function () {
            modalDiv.remove();
        });
    },

    async saveNewCycle() {
        const form = document.getElementById('new-cycle-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            description: formData.get('description'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date')
        };

        try {
            const response = await Helper.fetchAPI('/menu-cycles', {
                method: 'POST',
                body: JSON.stringify(data)
            });

            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('newCycleModal')).hide();
                Helper.alert('success', 'Ciclo creado y 20 días generados correctamente');

                // Recargar ciclos y seleccionar el nuevo
                await this.loadCycles();
                if (response.data && response.data.id) {
                    this.selectedCycle = this.cycles.find(c => c.id == response.data.id);
                    await this.loadCycleDays(this.selectedCycle.id);
                }
                this.render();
            } else {
                Helper.alert('error', response.message);
            }
        } catch (error) {
            console.error('Error creating cycle:', error);
            Helper.alert('error', 'No se pudo crear el ciclo');
        }
    }
};

// Initialize when view is loaded
if (typeof MinutasView !== 'undefined') {
    MinutasView.init();
}
