/**
 * Recetario View - Master Recipe Book Module
 */

window.RecetarioView = {
    recipes: [],
    items: [],

    async init() {
        console.log('Initializing Recetario Module...');
        await this.loadData();
        this.render();
    },

    async loadData() {
        try {
            const [recipeRes, itemRes] = await Promise.all([
                Helper.fetchAPI('/recipes'),
                Helper.fetchAPI('/items')
            ]);
            this.recipes = recipeRes.success ? recipeRes.data : [];
            this.items = itemRes.success ? itemRes.data : [];
        } catch (error) {
            console.error('Error loading recipes data:', error);
        }
    },

    render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div>
                        <h2 class="mb-1 text-primary-custom fw-bold"><i class="fas fa-utensils me-2"></i>Recetario Maestro</h2>
                        <p class="text-muted mb-0">Estandarización de minutas y platos base para planeación rápida</p>
                    </div>
                    <button class="btn btn-primary fw-bold px-4" onclick="RecetarioView.openRecipeModal()">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Receta Maestra
                    </button>
                </div>

                <div class="recipe-container custom-scrollbar px-2" style="max-height: calc(100vh - 200px); overflow-y: auto; overflow-x: hidden;">
                    <div class="row g-3">
                        ${this.renderRecipeCards()}
                    </div>
                </div>
            </div>
            <style>
                .recipe-container { scroll-behavior: smooth; }
                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #bbb; }
            </style>
        `;

        document.getElementById('app').innerHTML = html;
    },

    renderRecipeCards() {
        if (this.recipes.length === 0) {
            return `
                <div class="col-12">
                    <div class="text-center p-5 bg-white rounded shadow-sm border">
                        <i class="fas fa-concierge-bell fa-4x text-muted opacity-25 mb-3"></i>
                        <h5 class="fw-bold">No hay recetas configuradas</h5>
                        <p class="text-muted">Crea platos maestros para que la planeación de ciclos sea automática y sin errores.</p>
                        <button class="btn btn-outline-primary mt-2" onclick="RecetarioView.openRecipeModal()">Crear mi primera receta</button>
                    </div>
                </div>
            `;
        }

        return this.recipes.map(r => `
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm recipe-card position-relative overflow-hidden">
                    <div class="meal-type-badge ${r.meal_type === 'ALMUERZO' ? 'bg-primary' : 'bg-info'} text-white px-2 py-0 small fw-bold" style="font-size: 0.6rem;">
                        ${r.meal_type}
                    </div>
                    <div class="card-body pt-4 p-2">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="card-title fw-bold text-dark mb-0 text-truncate" style="font-size: 0.85rem;" title="${r.name}">${r.name}</h6>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="RecetarioView.editRecipe(${r.id})"><i class="fas fa-edit me-2 text-primary"></i>Editar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="RecetarioView.deleteRecipe(${r.id})"><i class="fas fa-trash me-2"></i>Eliminar</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-2 line-clamp-2" style="font-size: 0.75rem; min-height: 2.2em;">${r.description || 'Sin descripción'}</p>
                        
                        <div class="row g-0 text-center bg-light rounded p-2 mb-3 border">
                            <div class="col-4 border-end">
                                <div class="text-xs text-uppercase fw-bold text-muted" style="font-size: 0.6rem;">Calorías</div>
                                <div class="fw-bold mb-0 text-primary small">${Math.round(r.total_calories)} <small style="font-size: 0.5rem;">kcal</small></div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="text-xs text-uppercase fw-bold text-muted" style="font-size: 0.6rem;">Proteínas</div>
                                <div class="fw-bold mb-0 text-success small">${parseFloat(r.total_proteins).toFixed(1)} <small style="font-size: 0.5rem;">g</small></div>
                            </div>
                            <div class="col-4">
                                <div class="text-xs text-uppercase fw-bold text-muted" style="font-size: 0.6rem;">Carbos</div>
                                <div class="fw-bold mb-0 text-info small">${parseFloat(r.total_carbohydrates).toFixed(1)} <small style="font-size: 0.5rem;">g</small></div>
                            </div>
                        </div>

                        <button class="btn btn-outline-primary btn-xs w-100 fw-bold rounded-pill" style="font-size: 0.7rem; padding: 0.25rem;" onclick="RecetarioView.viewRecipe(${r.id})">
                            <i class="fas fa-eye me-1"></i> Ver Composición
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    },

    async viewRecipe(id) {
        try {
            const res = await Helper.fetchAPI(`/recipes/${id}`);
            if (res.success) {
                const r = res.data;
                const modalDiv = document.createElement('div');
                modalDiv.className = 'modal fade';
                modalDiv.id = 'viewRecipeModal';
                modalDiv.innerHTML = `
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-list me-2"></i>Composición: ${r.name}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="p-3 bg-light border-bottom d-flex justify-content-around text-center">
                                    <div><small class="text-muted d-block">Calorías</small><strong>${Math.round(r.total_calories)} kcal</strong></div>
                                    <div><small class="text-muted d-block">Proteínas</small><strong>${r.total_proteins}g</strong></div>
                                    <div><small class="text-muted d-block">Carbohidratos</small><strong>${r.total_carbohydrates}g</strong></div>
                                </div>
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light small fw-bold text-muted">
                                        <tr>
                                            <th class="ps-3 text-uppercase">Ingrediente</th>
                                            <th class="text-center text-uppercase">Cantidad</th>
                                            <th class="pe-3 text-uppercase">Notas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${r.items.map(i => `
                                            <tr>
                                                <td class="ps-3 fw-medium">${i.item_name}</td>
                                                <td class="text-center"><span class="badge bg-secondary-light text-primary">${i.quantity} ${i.unit}</span></td>
                                                <td class="pe-3 small text-muted">${i.preparation_method || '-'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="RecetarioView.editRecipe(${r.id})">
                                    <i class="fas fa-edit me-1"></i> Editar Receta
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modalDiv);
                const modal = new bootstrap.Modal(modalDiv);
                modal.show();
                modalDiv.addEventListener('hidden.bs.modal', () => modalDiv.remove());
            }
        } catch (error) {
            Helper.alert('error', 'No se pudo cargar el detalle de la receta');
        }
    },

    async editRecipe(id) {
        try {
            const res = await Helper.fetchAPI(`/recipes/${id}`);
            if (res.success) {
                // Cerrar modal de visualización si está abierto
                const viewModal = document.getElementById('viewRecipeModal');
                if (viewModal) {
                    const inst = bootstrap.Modal.getInstance(viewModal);
                    if (inst) inst.hide();
                }
                this.openRecipeModal(res.data);
            }
        } catch (error) {
            Helper.alert('error', 'Error al cargar los datos para editar');
        }
    },

    async deleteRecipe(id) {
        const confirm = await Swal.fire({
            title: '¿Eliminar receta?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (confirm.isConfirmed) {
            try {
                const res = await Helper.fetchAPI(`/recipes/${id}`, { method: 'DELETE' });
                if (res.success) {
                    Helper.alert('success', 'Receta eliminada correctamente');
                    this.init();
                } else {
                    Helper.alert('error', res.message);
                }
            } catch (error) {
                Helper.alert('error', 'Error al eliminar la receta');
            }
        }
    },

    openRecipeModal(recipe = null) {
        const isEdit = !!recipe;
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = 'recipeModal';
        modalDiv.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">
                            <i class="fas ${isEdit ? 'fa-edit' : 'fa-plus-circle'} me-2"></i>
                            ${isEdit ? 'Editar Receta Maestra' : 'Nueva Receta Maestra'}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="recipe-form" data-id="${isEdit ? recipe.id : ''}">
                            <div class="row g-3 mb-4">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Nombre del Plato</label>
                                    <input type="text" class="form-control" name="name" value="${isEdit ? recipe.name : ''}" placeholder="Ej: Arroz con Pollo..." required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Tipo de Comida</label>
                                    <select class="form-select" name="meal_type" required>
                                        <option value="DESAYUNO" ${isEdit && recipe.meal_type === 'DESAYUNO' ? 'selected' : ''}>DESAYUNO</option>
                                        <option value="ALMUERZO" ${(!isEdit || recipe.meal_type === 'ALMUERZO') ? 'selected' : ''}>ALMUERZO</option>
                                        <option value="MEDIA MAÑANA" ${isEdit && recipe.meal_type === 'MEDIA MAÑANA' ? 'selected' : ''}>MEDIA MAÑANA</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Descripción / Observaciones</label>
                                    <textarea class="form-control" name="description" rows="2" placeholder="Notas sobre la preparación...">${isEdit ? recipe.description || '' : ''}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold text-primary small text-uppercase"><i class="fas fa-flask me-1"></i>Composición Patrón (Fórmula)</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="RecetarioView.addIngredientRow()">
                                    <i class="fas fa-plus me-1"></i> Añadir Insumo
                                </button>
                            </div>
                            <div class="table-responsive border rounded bg-light">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="ps-3">Ingrediente</th>
                                            <th style="width: 20%">Cant. Patrón (g/ml)</th>
                                            <th style="width: 25%">Prep. / Notas</th>
                                            <th style="width: 5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="recipe-items-body">
                                        <!-- Dynamic rows -->
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary fw-bold px-4" onclick="RecetarioView.saveRecipe()">
                            <i class="fas fa-save me-1"></i> ${isEdit ? 'Actualizar Receta' : 'Guardar Receta'}
                        </button>
                    </div>
                </div>
            </div>
            <style>
                .meal-type-badge { position: absolute; top: 0; left: 0; border-bottom-right-radius: 15px; }
                .recipe-card:hover { transform: translateY(-5px); transition: all 0.3s; }
                .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
            </style>
        `;
        document.body.appendChild(modalDiv);
        const modal = new bootstrap.Modal(modalDiv);
        modal.show();

        if (isEdit && recipe.items && recipe.items.length > 0) {
            recipe.items.forEach(item => this.addIngredientRow(item));
        } else {
            this.addIngredientRow();
        }

        modalDiv.addEventListener('hidden.bs.modal', function () { modalDiv.remove(); });
    },

    addIngredientRow(data = null) {
        const tbody = document.getElementById('recipe-items-body');
        const tr = document.createElement('tr');
        tr.className = 'ingredient-row bg-white';
        tr.innerHTML = `
            <td class="ps-2">
                <select class="form-select form-select-sm border-0" name="item_id" required>
                    <option value="">Buscar ingrediente...</option>
                    ${this.items.map(i => `<option value="${i.id}" ${data && data.item_id == i.id ? 'selected' : ''}>${i.name} (${i.code})</option>`).join('')}
                </select>
            </td>
            <td><input type="number" step="0.01" class="form-control form-control-sm text-end border-0" name="quantity" value="${data ? data.quantity : ''}" placeholder="0.00" required></td>
            <td><input type="text" class="form-control form-control-sm border-0" name="preparation" value="${data ? data.preparation_method || '' : ''}" placeholder="Ej: Crudo"></td>
            <td class="text-center"><button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove()"><i class="fas fa-minus-circle"></i></button></td>
        `;
        tbody.appendChild(tr);
    },

    async saveRecipe() {
        const form = document.getElementById('recipe-form');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const recipeId = form.dataset.id;
        const formData = new FormData(form);
        const items = [];
        document.querySelectorAll('.ingredient-row').forEach(row => {
            const id = row.querySelector('[name="item_id"]').value;
            const qty = row.querySelector('[name="quantity"]').value;
            if (id && qty > 0) items.push({
                item_id: id,
                quantity: qty,
                preparation: row.querySelector('[name="preparation"]').value
            });
        });

        if (items.length === 0) { Helper.alert('warning', 'Debe añadir al menos un ingrediente'); return; }

        const data = {
            name: formData.get('name'),
            meal_type: formData.get('meal_type'),
            description: formData.get('description'),
            items: items
        };

        try {
            const method = recipeId ? 'PUT' : 'POST';
            const url = recipeId ? `/recipes/${recipeId}` : '/recipes';

            const response = await Helper.fetchAPI(url, {
                method: method,
                body: JSON.stringify(data)
            });

            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('recipeModal')).hide();
                Helper.alert('success', recipeId ? 'Receta actualizada' : 'Receta guardada');
                await this.init();
            } else {
                Helper.alert('error', response.message);
            }
        } catch (error) {
            Helper.alert('error', 'Error al procesar la receta');
        }
    }
};

// Initialize
if (typeof RecetarioView !== 'undefined') {
    RecetarioView.init();
}
