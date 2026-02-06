/**
 * Beneficiaries (Students) View
 * Manage student registration for the PAE program
 */

var BeneficiariesView = {
    beneficiaries: [],
    schools: [],
    branches: [],
    documentTypes: [],
    ethnicGroups: [],
    filteredBranches: [],
    selectedSchoolId: null,

    /**
     * Render the view
     */
    async render() {
        const html = `
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2><i class="fas fa-user-graduate me-2"></i>Beneficiarios (Estudiantes)</h2>
                                <p class="text-muted">Gestión de matrícula y caracterización de estudiantes focalizados</p>
                            </div>
                            <div>
                                <button class="btn btn-outline-secondary me-2" onclick="PrintListView.openModal()">
                                    <i class="fas fa-print me-2"></i>Imprimir Listas
                                </button>
                                <button class="btn btn-primary" onclick="BeneficiariesView.openModal()">
                                    <i class="fas fa-plus me-2"></i>Nuevo Beneficiario
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-3 text-secondary"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchBeneficiary" placeholder="Buscar por documento o nombre...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterSchool" onchange="BeneficiariesView.filterTable()">
                                    <option value="">Todos los Colegios</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterGrade" onchange="BeneficiariesView.filterTable()">
                                    <option value="">Todos los Grados</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-outline-secondary w-100" onclick="BeneficiariesView.loadBeneficiaries()">
                                    <i class="fas fa-sync-alt me-2"></i>Refrescar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="beneficiariesTable" class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Identificación</th>
                                        <th>Nombre Completo</th>
                                        <th>Institución / Sede</th>
                                        <th>Grado / Grupo</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="beneficiaries-table-body">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: Beneficiary Form -->
            <div class="modal fade" id="modalBeneficiary" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalBeneficiaryTitle">Registro de Estudiante</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <!-- Custom Tabs -->
                            <ul class="nav nav-tabs nav-fill bg-light px-3 pt-3" id="beneficiaryTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-personal" type="button">
                                        <i class="fas fa-id-card me-2"></i>Identificación
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-enrollment" type="button">
                                        <i class="fas fa-graduation-cap me-2"></i>Matrícula
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-contact" type="button">
                                        <i class="fas fa-home me-2"></i>Contacto
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-health" type="button">
                                        <i class="fas fa-heartbeat me-2"></i>Salud y Otros
                                    </button>
                                </li>
                            </ul>
                            
                            <form id="formBeneficiary" class="p-4">
                                <input type="hidden" id="beneficiary-id">
                                <div class="tab-content">
                                    <!-- Tab 1: Personal Data -->
                                    <div class="tab-pane fade show active" id="tab-personal">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Tipo de Documento *</label>
                                                <select class="form-select" id="doc-type" required></select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Número de Documento *</label>
                                                <input type="text" class="form-control" id="doc-number" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">SIMAT ID (Opcional)</label>
                                                <input type="text" class="form-control" id="simat-id">
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <label class="form-label">Primer Apellido *</label>
                                                <input type="text" class="form-control" id="last-name1" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Segundo Apellido</label>
                                                <input type="text" class="form-control" id="last-name2">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Primer Nombre *</label>
                                                <input type="text" class="form-control" id="first-name" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Segundo Nombre</label>
                                                <input type="text" class="form-control" id="second-name">
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">Fecha de Nacimiento *</label>
                                                <input type="date" class="form-control" id="birth-date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Género *</label>
                                                <select class="form-select" id="gender" required>
                                                    <option value="MASCULINO">Masculino</option>
                                                    <option value="FEMENINO">Femenino</option>
                                                    <option value="OTRO">Otro</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Etnia *</label>
                                                <select class="form-select" id="ethnic-group" required></select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">SISBEN (Categoría)</label>
                                                <input type="text" class="form-control" id="sisben" placeholder="Ej: A1, B2">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" id="is-overage">
                                                    <label class="form-check-label fw-bold text-danger" for="is-overage">
                                                        ¿Es Estudiante en Extraedad? (Adulto)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" id="data-authorization" required>
                                                    <label class="form-check-label fw-bold text-primary" for="data-authorization">
                                                        Autorización de Tratamiento de Datos Personales (Habeas Data) *
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tab 2: Enrollment -->
                                    <div class="tab-pane fade" id="tab-enrollment">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Institución Educativa *</label>
                                                <select class="form-select" id="school-id" onchange="BeneficiariesView.onSchoolChange(this.value)" required></select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Sede Educativa *</label>
                                                <select class="form-select" id="branch-id" required disabled>
                                                    <option value="">Seleccione primero un colegio</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Grado *</label>
                                                <select class="form-select" id="grade" required>
                                                    <option value="TRANSICION">Transición / Grado 0</option>
                                                    <option value="1">Primero</option>
                                                    <option value="2">Segundo</option>
                                                    <option value="3">Tercero</option>
                                                    <option value="4">Cuarto</option>
                                                    <option value="5">Quinto</option>
                                                    <option value="6">Sexto</option>
                                                    <option value="7">Séptimo</option>
                                                    <option value="8">Octavo</option>
                                                    <option value="9">Noveno</option>
                                                    <option value="10">Décimo</option>
                                                    <option value="11">Undécimo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Grupo / Curso</label>
                                                <input type="text" class="form-control" id="group" placeholder="Ej: 01, A">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Jornada *</label>
                                                <select class="form-select" id="shift" required>
                                                    <option value="MAÑANA">Mañana</option>
                                                    <option value="TARDE">Tarde</option>
                                                    <option value="UNICA">Única</option>
                                                    <option value="NOCTURNA">Nocturna</option>
                                                    <option value="COMPLETA">Completa</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Fecha de Matrícula</label>
                                                <input type="date" class="form-control" id="enrollment-date">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Modalidad PAE *</label>
                                                <select class="form-select" id="modality" required>
                                                    <option value="RACION PREPARADA EN SITIO">Preparada en Sitio</option>
                                                    <option value="RACION INDUSTRIALIZADA">Industrializada</option>
                                                    <option value="BONO ALIMENTARIO">Bono Alimentario</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Tipo de Ración *</label>
                                                <select class="form-select" id="ration-type" required>
                                                    <option value="COMPLEMENTO MAÑANA">Complemento AM</option>
                                                    <option value="COMPLEMENTO TARDE">Complemento PM</option>
                                                    <option value="ALMUERZO">Almuerzo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Estado *</label>
                                                <select class="form-select" id="status" required>
                                                    <option value="ACTIVO">Activo</option>
                                                    <option value="INACTIVO">Inactivo</option>
                                                    <option value="DESERTADO">Desertado</option>
                                                    <option value="TRASLADADO">Trasladado</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12" id="age-group-suggestion-container" style="display: none;">
                                                <div class="alert alert-info d-flex align-items-center mb-0">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <div>
                                                        Grupo Etario sugerido según edad: <strong id="suggested-age-group">-</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tab 3: Contact -->
                                    <div class="tab-pane fade" id="tab-contact">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label">Dirección de Residencia</label>
                                                <input type="text" class="form-control" id="address">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Teléfono de Contacto</label>
                                                <input type="text" class="form-control" id="phone">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="email">
                                            </div>
                                            <div class="col-12"><hr></div>
                                            <h6 class="mb-0">Información del Acudiente</h6>
                                            <div class="col-md-8">
                                                <label class="form-label">Nombre del Acudiente</label>
                                                <input type="text" class="form-control" id="guardian-name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Parentesco</label>
                                                <input type="text" class="form-control" id="guardian-relationship" placeholder="Ej: Madre, Padre, Tío">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Teléfono Acudiente</label>
                                                <input type="text" class="form-control" id="guardian-phone">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tab 4: Health and Others -->
                                    <div class="tab-pane fade" id="tab-health">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tipo de Discapacidad</label>
                                                <select class="form-select" id="disability">
                                                    <option value="NINGUNA">Ninguna</option>
                                                    <option value="FISICA">Física</option>
                                                    <option value="AUDITIVA">Auditiva</option>
                                                    <option value="VISUAL">Visual</option>
                                                    <option value="INTELECTUAL">Intelectual</option>
                                                    <option value="MULTIPLE">Múltiple</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" id="is-victim">
                                                    <label class="form-check-label" for="is-victim">Población Víctima</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" id="is-migrant">
                                                    <label class="form-check-label" for="is-migrant">Población Migrante</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label text-danger fw-bold">Restricciones Médicas / Alergias</label>
                                                <textarea class="form-control" id="medical-restrictions" rows="2" placeholder="Especifique alergias o condiciones médicas..."></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Observaciones Generales</label>
                                                <textarea class="form-control" id="observations" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary px-4" onclick="BeneficiariesView.save()">
                                <i class="fas fa-save me-2"></i>Guardar Beneficiario
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('app-container').innerHTML = html;
        this.addEventListeners();
        await this.init();
    },

    addEventListeners() {
        const birthDateInput = document.getElementById('birth-date');
        if (birthDateInput) {
            birthDateInput.addEventListener('change', () => this.calculateSuggestedAgeGroup());
        }
    },

    calculateSuggestedAgeGroup() {
        const birthDate = document.getElementById('birth-date').value;
        if (!birthDate) return;

        const age = Helper.calculateAge(birthDate); // Assuming Helper has this, if not I'll add it or do it here
        const container = document.getElementById('age-group-suggestion-container');
        const span = document.getElementById('suggested-age-group');

        let group = '';
        if (age >= 3 && age <= 5) group = 'PREESCOLAR';
        else if (age >= 6 && age <= 7) group = 'PRIMARIA A';
        else if (age >= 8 && age <= 12) group = 'PRIMARIA B';
        else if (age >= 13 && age <= 17) group = 'SECUNDARIA';
        else if (age > 17) group = 'EXTRAEDAD / ADULTO';

        if (group) {
            span.innerText = group;
            container.style.display = 'block';
            if (age > 17) document.getElementById('is-overage').checked = true;
        } else {
            container.style.display = 'none';
        }
    },

    async init() {
        await Promise.all([
            this.loadBeneficiaries(),
            this.loadMasterData()
        ]);
        this.renderFilters();
    },

    async loadMasterData() {
        try {
            const [schools, docTypes, ethnicGroups] = await Promise.all([
                Helper.fetchAPI('/schools'),
                Helper.fetchAPI('/beneficiarios/document_types'),
                Helper.fetchAPI('/beneficiarios/ethnic_groups')
            ]);

            this.schools = schools || [];
            this.documentTypes = docTypes || [];
            this.ethnicGroups = ethnicGroups || [];

            this.populateSelect('school-id', this.schools, 'Seleccione Institución');
            this.populateSelect('doc-type', this.documentTypes, 'Seleccione Tipo');
            this.populateSelect('ethnic-group', this.ethnicGroups, 'Seleccione Etnia');

            // Filters
            this.populateSelect('filterSchool', this.schools, 'Todos los Colegios');
        } catch (err) {
            console.error("Error loading master data:", err);
        }
    },

    populateSelect(elementId, data, emptyText) {
        const select = document.getElementById(elementId);
        if (!select) return;

        let html = `<option value="">${emptyText}</option>`;
        data.forEach(item => {
            html += `<option value="${item.id}">${item.name}</option>`;
        });
        select.innerHTML = html;
    },

    async onSchoolChange(schoolId) {
        const branchSelect = document.getElementById('branch-id');
        if (!schoolId) {
            branchSelect.innerHTML = '<option value="">Seleccione primero un colegio</option>';
            branchSelect.disabled = true;
            return;
        }

        try {
            const branches = await Helper.fetchAPI(`/branches?school_id=${schoolId}`);
            this.filteredBranches = branches || [];
            this.populateSelect('branch-id', this.filteredBranches, 'Seleccione Sede');
            branchSelect.disabled = false;
        } catch (err) {
            console.error("Error loading branches:", err);
            Helper.alert('error', 'No se pudieron cargar las sedes');
        }
    },

    async loadBeneficiaries() {
        try {
            const data = await Helper.fetchAPI('/beneficiarios');
            this.beneficiaries = Array.isArray(data) ? data : [];
            this.renderTable();
        } catch (err) {
            console.error(err);
        }
    },

    renderTable() {
        const tbody = document.getElementById('beneficiaries-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';
        this.beneficiaries.forEach(b => {
            const statusClass = b.status === 'ACTIVO' ? 'bg-success' : 'bg-danger';
            tbody.innerHTML += `
                <tr>
                    <td>
                        <small class="text-muted d-block">${b.document_type_name}</small>
                        <span class="fw-bold">${b.document_number}</span>
                    </td>
                    <td>
                        <div class="fw-bold text-primary">${b.last_name1} ${b.last_name2}</div>
                        <div>${b.first_name} ${b.second_name || ''}</div>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 250px;">
                            <i class="fas fa-university me-1 text-muted small"></i><strong>${b.school_name}</strong><br>
                            <span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>${b.branch_name}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">${b.grade}Â°</span>
                        <span class="badge bg-light text-dark border">${b.group_name || 'N/A'}</span>
                        <br><small class="text-muted">${b.shift}</small>
                    </td>
                    <td><span class="badge ${statusClass}">${b.status}</span></td>
                    <td class="text-end">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-info" title="Generar Carnet" onclick="BeneficiariesView.generateCarnet(${b.id})">
                                <i class="fas fa-id-badge"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Editar" onclick="BeneficiariesView.openModal(${b.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="BeneficiariesView.delete(${b.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });


        // Initialize DataTable if not already or destroy and re-init
        if ($.fn.DataTable.isDataTable('#beneficiariesTable')) {
            $('#beneficiariesTable').DataTable().destroy();
        }
        // Custom options: Hide default search box (we use custom filters)
        Helper.initDataTable('#beneficiariesTable', {
            dom: '<"row"<"col-sm-12"t>><"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            searching: true // Keep search enabled for our custom filters
        });
    },

    openModal(beneficiaryOrId = null) {
        let b = (typeof beneficiaryOrId === 'number')
            ? this.beneficiaries.find(x => x.id == beneficiaryOrId)
            : beneficiaryOrId;

        const isEdit = !!b;
        document.getElementById('formBeneficiary').reset();
        document.getElementById('beneficiary-id').value = isEdit ? b.id : '';
        document.getElementById('modalBeneficiaryTitle').innerText = isEdit ? 'Editar Beneficiario' : 'Nuevo Beneficiario';

        // Reset tabs to first
        const firstTab = document.querySelector('#beneficiaryTabs button[data-bs-target="#tab-personal"]');
        bootstrap.Tab.getOrCreateInstance(firstTab).show();

        if (isEdit) {
            document.getElementById('doc-type').value = b.document_type_id;
            document.getElementById('doc-number').value = b.document_number;
            document.getElementById('simat-id').value = b.simat_id || '';
            document.getElementById('first-name').value = b.first_name;
            document.getElementById('second-name').value = b.second_name || '';
            document.getElementById('last-name1').value = b.last_name1;
            document.getElementById('last-name2').value = b.last_name2 || '';
            document.getElementById('birth-date').value = b.birth_date;
            document.getElementById('gender').value = b.gender;
            document.getElementById('ethnic-group').value = b.ethnic_group_id;
            document.getElementById('sisben').value = b.sisben_category || '';
            document.getElementById('data-authorization').checked = !!b.data_authorization;
            document.getElementById('is-overage').checked = !!b.is_overage;
            this.calculateSuggestedAgeGroup(); // Auto-calculate on open if birth_date exists

            // Location
            document.getElementById('school-id').value = b.school_id || '';
            // Load branches for this school
            this.onSchoolChange(b.school_id).then(() => {
                document.getElementById('branch-id').value = b.branch_id;
            });

            document.getElementById('grade').value = b.grade;
            document.getElementById('group').value = b.group_name || '';
            document.getElementById('shift').value = b.shift;
            document.getElementById('enrollment-date').value = b.enrollment_date || '';
            document.getElementById('modality').value = b.modality;
            document.getElementById('ration-type').value = b.ration_type;
            document.getElementById('status').value = b.status;

            document.getElementById('address').value = b.address || '';
            document.getElementById('phone').value = b.phone || '';
            document.getElementById('email').value = b.email || '';
            document.getElementById('guardian-name').value = b.guardian_name || '';
            document.getElementById('guardian-relationship').value = b.guardian_relationship || '';
            document.getElementById('guardian-phone').value = b.guardian_phone || '';

            document.getElementById('disability').value = b.disability_type || 'NINGUNA';
            document.getElementById('is-victim').checked = !!b.is_victim;
            document.getElementById('is-migrant').checked = !!b.is_migrant;
            document.getElementById('medical-restrictions').value = b.medical_restrictions || '';
            document.getElementById('observations').value = b.observations || '';
        }

        new bootstrap.Modal(document.getElementById('modalBeneficiary')).show();
    },

    async save() {
        const id = document.getElementById('beneficiary-id').value;
        const form = document.getElementById('formBeneficiary');

        // Custom validation to handle hidden tabs
        const invalidField = form.querySelector(':invalid');
        if (invalidField) {
            // Find which tab this field belongs to
            const tabPane = invalidField.closest('.tab-pane');
            if (tabPane) {
                const tabId = tabPane.id;
                const tabTrigger = document.querySelector(`button[data-bs-target="#${tabId}"]`);
                if (tabTrigger) {
                    bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                    // Small delay to allow tab transition before reporting validity
                    setTimeout(() => {
                        invalidField.focus();
                        form.reportValidity();
                    }, 150);
                    return;
                }
            }
            form.reportValidity();
            return;
        }

        const data = {
            document_type_id: document.getElementById('doc-type').value,
            document_number: document.getElementById('doc-number').value,
            simat_id: document.getElementById('simat-id').value,
            first_name: document.getElementById('first-name').value,
            second_name: document.getElementById('second-name').value,
            last_name1: document.getElementById('last-name1').value,
            last_name2: document.getElementById('last-name2').value,
            birth_date: document.getElementById('birth-date').value,
            gender: document.getElementById('gender').value,
            ethnic_group_id: document.getElementById('ethnic-group').value,
            sisben_category: document.getElementById('sisben').value,
            data_authorization: document.getElementById('data-authorization').checked,
            is_overage: document.getElementById('is-overage').checked ? 1 : 0,

            branch_id: document.getElementById('branch-id').value,
            grade: document.getElementById('grade').value,
            group_name: document.getElementById('group').value,
            shift: document.getElementById('shift').value,
            enrollment_date: document.getElementById('enrollment-date').value,
            modality: document.getElementById('modality').value,
            ration_type: document.getElementById('ration-type').value,
            status: document.getElementById('status').value,

            address: document.getElementById('address').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            guardian_name: document.getElementById('guardian-name').value,
            guardian_relationship: document.getElementById('guardian-relationship').value,
            guardian_phone: document.getElementById('guardian-phone').value,

            disability_type: document.getElementById('disability').value,
            is_victim: document.getElementById('is-victim').checked,
            is_migrant: document.getElementById('is-migrant').checked,
            medical_restrictions: document.getElementById('medical-restrictions').value,
            observations: document.getElementById('observations').value
        };

        try {
            const endpoint = id ? `/beneficiarios/${id}` : '/beneficiarios';
            const method = id ? 'PUT' : 'POST';

            const res = await Helper.fetchAPI(endpoint, {
                method,
                body: JSON.stringify(data)
            });

            if (res.message) {
                Helper.alert('success', res.message);
                bootstrap.Modal.getInstance(document.getElementById('modalBeneficiary')).hide();
                await this.loadBeneficiaries();
            }
        } catch (err) {
            console.error(err);
            Helper.alert('error', err.message || 'Error al guardar beneficiario');
        }
    },

    async delete(id) {
        if (!await Helper.confirm('¿Seguro que desea eliminar este beneficiario? Esta acción no se puede deshacer.')) return;

        try {
            const res = await Helper.fetchAPI(`/beneficiarios/${id}`, { method: 'DELETE' });
            if (res.message) {
                Helper.alert('success', res.message);
                await this.loadBeneficiaries();
            }
        } catch (err) {
            console.error(err);
        }
    },

    generateCarnet(id) {
        const b = this.beneficiaries.find(x => x.id == id);
        if (!b) return;

        // Token format for QR
        const token = `PAE:${b.id}:${b.document_number}`;
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(token)}`;

        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        if (!printWindow) {
            Helper.alert('error', 'Por favor permita las ventanas emergentes para generar el carnet.');
            return;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Carnet - ${b.first_name} ${b.last_name1}</title>
                <!-- FontAwesome for the avatar icon -->
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
                <style>
                    body { 
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                        -webkit-print-color-adjust: exact; 
                        padding: 40px; 
                        background-color: #f0f0f0;
                        display: flex;
                        justify-content: center;
                        align-items: flex-start;
                        min-height: 100vh;
                        margin: 0;
                    }
                    .carnet-card {
                        width: 350px;
                        height: 560px; /* Increased height */
                        border-radius: 20px;
                        padding: 0;
                        margin: 0;
                        position: relative;
                        background: #fff;
                        overflow: visible; /* Changed from hidden to avoid clipping */
                        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                        display: flex;
                        flex-direction: column;
                    }
                    .header {
                        background: linear-gradient(135deg, #1B4F72 0%, #2980b9 100%);
                        color: white;
                        padding: 15px; /* Reduced padding */
                        text-align: center;
                        border-bottom: 5px solid #F4D03F;
                    }
                    .content { 
                        flex-grow: 1;
                        padding: 15px; /* Reduced padding */
                        text-align: center; 
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }
                    
                    .avatar-container {
                        width: 100px;
                        height: 100px;
                        background-color: #ecf0f1;
                        border-radius: 50%;
                        margin-bottom: 15px;
                        border: 4px solid #F4D03F;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 40px;
                        color: #1B4F72;
                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    }
                    
                    .student-name { 
                        font-size: 22px; 
                        font-weight: 700; 
                        color: #2c3e50; 
                        margin-bottom: 5px; 
                        line-height: 1.2;
                    }
                    
                    .student-doc {
                        font-family: monospace;
                        font-size: 16px;
                        background-color: #f8f9fa;
                        padding: 4px 12px;
                        border-radius: 12px;
                        color: #555;
                        margin-bottom: 10px;
                        display: inline-block;
                        border: 1px solid #ddd;
                        font-weight: bold;
                    }
                    
                    .school-info {
                        font-size: 13px;
                        color: #7f8c8d;
                        margin-bottom: 2px;
                        width: 100%;
                    }
                    
                    .grade-badge {
                        background-color: #1B4F72;
                        color: white;
                        padding: 5px 15px;
                        border-radius: 15px;
                        font-size: 14px;
                        font-weight: bold;
                        margin: 10px 0;
                        display: inline-block;
                    }
                    
                    .qr-container { 
                        margin-top: auto; 
                        padding: 10px;
                        background: white;
                        border: 1px dashed #ccc;
                        border-radius: 10px;
                    }
                    
                    .footer {
                        background-color: #f8f9fa;
                        color: #7f8c8d;
                        padding: 10px;
                        text-align: center;
                        border-top: 1px solid #eee;
                        font-size: 10px;
                        font-weight: 600;
                        text-transform: uppercase;
                    }
                    
                    @media print {
                        body { 
                            background: none; 
                            padding: 0; 
                            display: block;
                        }
                        .carnet-card {
                            box-shadow: none;
                            border: 1px solid #ddd;
                            margin: 10px;
                            page-break-inside: avoid;
                        }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="carnet-card">
                    <div class="header">
                        <div class="logo-text">PAE 2026</div>
                        <div class="sub-header">Identificación del Beneficiario</div>
                    </div>
                    <div class="content">
                        <div class="avatar-container">
                            <span>${b.first_name.charAt(0)}${b.last_name1.charAt(0)}</span>
                        </div>
                        <div class="student-name">${b.first_name} <br> ${b.last_name1}</div>
                        <div class="student-doc">${b.document_type_name}: ${b.document_number}</div>
                        
                        <div class="grade-badge">
                            Grado ${b.grade} - ${b.group_name || 'N/A'}
                        </div>

                        <div class="school-info"><strong>${b.school_name}</strong></div>
                        <div class="school-info">${b.branch_name}</div>
                        
                        <div class="qr-container">
                            <img src="${qrUrl}" width="110" height="110" alt="QR Code">
                        </div>
                    </div>
                    <div class="footer">
                        Este documento es personal e intransferible
                    </div>
                </div>
                <script>
                    window.onload = function() {
                        // Attempt to close after print, but offer manual close too
                        setTimeout(function() {
                            window.print();
                        }, 500); // Give image a moment to render
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    },

    filterTable() {
        const schoolId = document.getElementById('filterSchool').value;
        const grade = document.getElementById('filterGrade').value;
        const searchText = document.getElementById('searchBeneficiary').value.toLowerCase();

        // Custom search logic for DataTable or just re-render with filters
        // For now, let's use DataTable's built-in search for the text and manual for selects
        const table = $('#beneficiariesTable').DataTable();

        // This is a simple implementation, a better one would use table.column().search()
        table.search(searchText);

        if (schoolId) {
            // Find school name to filter
            const school = this.schools.find(s => s.id == schoolId);
            table.column(2).search(school ? school.name : '').draw();
        } else {
            table.column(2).search('').draw();
        }

        if (grade) {
            table.column(3).search(grade + 'Â°').draw();
        } else {
            table.column(3).search('').draw();
        }
    },

    renderFilters() {
        // Optional: populate grade filter
        const grades = ["TRANSICION", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"];
        const select = document.getElementById('filterGrade');
        grades.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g;
            opt.innerText = `Grado ${g}`;
            select.appendChild(opt);
        });

        // Search event
        document.getElementById('searchBeneficiary').addEventListener('keyup', () => this.filterTable());
    }
};

// Auto-render when loaded
BeneficiariesView.render();
