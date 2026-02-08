window.RationTypesView = {
    rationTypes: [],

    init: async () => {
        const appContainer = document.getElementById('app');
        appContainer.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom fw-bold">Tipos de Ración</h2>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="RationTypesView.openModal()">
                    <i class="fas fa-plus me-2"></i>Nuevo Tipo de Ración
                </button>
            </div>
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive p-3">
                        <table id="rationTable" class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary text-uppercase small fw-bold">
                                <tr>
                                    <th class="ps-4">Nombre del momento de entrega</th>
                                    <th>Creado el</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="ration-table-body">
                                <tr><td colspan="3" class="text-center py-4">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        await RationTypesView.loadData();
    },

    loadData: async () => {
        const res = await Helper.fetchAPI('/ration-types');
        const tbody = document.getElementById('ration-table-body');

        if (res.success) {
            RationTypesView.rationTypes = res.data || [];
            tbody.innerHTML = '';

            if (RationTypesView.rationTypes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-muted">No hay raciones configuradas.</td></tr>';
                return;
            }

            RationTypesView.rationTypes.forEach(rt => {
                tbody.innerHTML += `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${rt.name}</div>
                        </td>
                        <td><small class="text-muted">${rt.created_at || 'N/A'}</small></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-2" onclick='RationTypesView.openModal(${JSON.stringify(rt)})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger" onclick="RationTypesView.delete(${rt.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            Helper.initDataTable('#rationTable');
        } else {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-danger">${res.message || 'Error al cargar'}</td></tr>`;
        }
    },

    openModal: async (rt = null) => {
        const isEdit = !!rt;
        const { value: name } = await Swal.fire({
            title: isEdit ? 'Editar Tipo de Ración' : 'Nuevo Tipo de Ración',
            input: 'text',
            inputLabel: 'Nombre (Ej: Desayuno, Almuerzo, Refrigerio)',
            inputValue: rt ? rt.name : '',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return 'El nombre es obligatorio';
            }
        });

        if (name) {
            const method = isEdit ? 'PUT' : 'POST';
            const url = isEdit ? `/ration-types/${rt.id}` : '/ration-types';

            const res = await Helper.fetchAPI(url, {
                method: method,
                body: JSON.stringify({ name: name.toUpperCase() })
            });

            if (res.success || res.message) {
                Swal.fire('Éxito', 'Tipo de ración guardado correctamente', 'success');
                RationTypesView.init();
            } else {
                Swal.fire('Error', res.message || 'No se pudo guardar', 'error');
            }
        }
    },

    delete: async (id) => {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta ración podría estar vinculada a menús y recetas actuales.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            const res = await Helper.fetchAPI(`/ration-types/${id}`, { method: 'DELETE' });
            if (res.success || res.message) {
                Swal.fire('Eliminado', 'El tipo de ración ha sido eliminado', 'success');
                RationTypesView.init();
            } else {
                Swal.fire('Error', res.message || 'No se pudo eliminar', 'error');
            }
        }
    }
};

if (typeof RationTypesView !== 'undefined') {
    RationTypesView.init();
}
