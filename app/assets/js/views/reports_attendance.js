/**
 * Reporte: Control de Asistencia y Entrega de Raciones
 */
window.ReportsAttendanceView = {
    attendanceData: [],
    schools: [],
    branches: [],
    rationTypes: [],
    cycles: [],
    programInfo: null,
    dataTable: null,

    async init() {
        Helper.loading(true, 'Cargando datos del reporte...');
        await this.loadMasterData();
        this.render();
        this.onCycleFilterChange();
        this.attachEvents();
        await this.loadData();
        Helper.loading(false);

        // Evitar que el backdrop invisible de SweetAlert2 bloquee clics
        setTimeout(() => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
        }, 150);
    },

    async loadMasterData() {
        try {
            const [schools, branches, rationTypes, cycleRes, activeProgram] = await Promise.all([
                Helper.fetchAPI('/schools'),
                Helper.fetchAPI('/branches'),
                Helper.fetchAPI('/ration-types'),
                Helper.fetchAPI('/menu-cycles'),
                Helper.fetchAPI('/presupuesto/active-program').catch(() => null)
            ]);
            this.schools = Array.isArray(schools) ? schools : [];
            this.branches = Array.isArray(branches) ? branches : [];
            this.rationTypes = rationTypes.success ? rationTypes.data : [];
            this.cycles = cycleRes.success ? cycleRes.data : [];
            this.programInfo = activeProgram;
        } catch (error) {
            console.error('Error loading master data:', error);
        }
    },

    async loadData() {
        try {
            const cycleId = document.getElementById('filter-cycle')?.value || '';
            const schoolId = document.getElementById('filter-school')?.value || '';
            const branchId = document.getElementById('filter-branch')?.value || '';
            const mealType = document.getElementById('filter-meal-type')?.value || '';

            const cycle = this.cycles.find(c => c.id == cycleId);
            const date = cycle ? cycle.start_date : new Date().toISOString().split('T')[0];

            let url = `/consumptions/report?date=${date}`;
            if (branchId) {
                url += `&branch_id=${branchId}`;
            }
            if (mealType) {
                url += `&meal_type=${mealType}`;
            }

            const res = await Helper.fetchAPI(url);
            
            if (res && res.success && Array.isArray(res.data)) {
                let data = res.data;

                // Filtrar por sedes del ciclo si tiene sedes específicas configuradas
                if (cycle && cycle.branch_ids) {
                    const allowedBranchIds = cycle.branch_ids.split(',').map(id => id.trim());
                    data = data.filter(item => {
                        const branch = this.branches.find(b => {
                            const school = this.schools.find(s => s.id == b.school_id);
                            return b.name === item.branch_name && school && school.name === item.school_name;
                        });
                        return branch && allowedBranchIds.includes(branch.id.toString());
                    });
                }

                // Si filtramos por colegio pero no por sede, hacemos el filtrado en el cliente 
                // ya que la API solo filtra por branch_id directamente.
                if (schoolId && !branchId) {
                    // Obtener los ids de las sedes de este colegio
                    const schoolBranchIds = this.branches
                        .filter(b => b.school_id == schoolId)
                        .map(b => b.id);
                    data = data.filter(item => {
                        // Buscar el id de la sede para el beneficiario
                        const branch = this.branches.find(b => {
                            const school = this.schools.find(s => s.id == b.school_id);
                            return b.name === item.branch_name && school && school.name === item.school_name;
                        });
                        return branch && schoolBranchIds.includes(branch.id);
                    });
                }
                this.attendanceData = data;
                this.renderTable(this.attendanceData);
            } else {
                console.error('API did not return an array for attendance report:', res);
                Helper.alert('error', 'No se pudo cargar el reporte de asistencia');
            }
        } catch (error) {
            console.error('Error loading attendance report:', error);
            Helper.alert('error', 'No se pudo cargar el reporte de asistencia');
        }
    },

    render() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('app').innerHTML = `
            <div class="container-fluid fade-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1"><i class="fas fa-user-check me-2 text-primary"></i>Control de Entrega de Raciones</h2>
                        <p class="text-muted mb-0">Reporte de asistencia y entrega de raciones diarias</p>
                    </div>
                    <div class="btn-group shadow-sm">
                        <button class="btn btn-outline-success" onclick="ReportsAttendanceView.exportExcel()">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </button>
                        <button class="btn btn-outline-danger" onclick="ReportsAttendanceView.exportPDF()">
                            <i class="fas fa-file-pdf me-2"></i>PDF / Imprimir
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body bg-light rounded">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-uppercase text-secondary">Ciclo</label>
                                <select id="filter-cycle" class="form-select border-2">
                                    <option value="">-- Seleccione Ciclo --</option>
                                    ${this.cycles.map((c, i) => `<option value="${c.id}" ${i === 0 ? 'selected' : ''}>${c.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-secondary">Institución / Centro Educativo</label>
                                <select id="filter-school" class="form-select border-2">
                                    <option value="">-- Todas las Instituciones --</option>
                                    ${this.schools.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-secondary">Sede / Punto de Atención</label>
                                <select id="filter-branch" class="form-select border-2" disabled>
                                    <option value="">-- Seleccione una institución primero --</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-uppercase text-secondary">Tipo de Ración</label>
                                <select id="filter-meal-type" class="form-select border-2">
                                    <option value="">-- Todos los tipos --</option>
                                    ${this.rationTypes.map(rt => `<option value="${rt.id}">${rt.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" onclick="ReportsAttendanceView.resetFilters()">
                                    <i class="fas fa-eraser me-2"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive p-3">
                            <table id="reports-attendance-table" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="bg-light text-secondary text-uppercase small fw-bold">
                                    <tr>
                                        <th class="text-center" style="width: 5%">Secuencia</th>
                                        <th class="text-center" style="width: 10%">Documento</th>
                                        <th style="width: 25%">Nombres y Apellidos</th>
                                        <th class="text-center" style="width: 7%">Desayuno</th>
                                        <th class="text-center" style="width: 7%">Media Mañana</th>
                                        <th class="text-center" style="width: 7%">Almuerzo</th>
                                        <th class="text-center" style="width: 7%">Media Tarde</th>
                                        <th class="text-center" style="width: 7%">Cena</th>
                                        <th class="text-center" style="width: 25%">Firma / Huella</th>
                                    </tr>
                                </thead>
                                <tbody id="reports-attendance-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    attachEvents() {
        const filterCycle = document.getElementById('filter-cycle');
        const filterSchool = document.getElementById('filter-school');
        const filterBranch = document.getElementById('filter-branch');
        const filterMealType = document.getElementById('filter-meal-type');

        if (filterCycle) {
            filterCycle.addEventListener('change', () => {
                this.onCycleFilterChange();
                this.loadData();
            });
        }

        if (filterSchool) {
            filterSchool.addEventListener('change', () => {
                this.onSchoolFilterChange();
                this.loadData();
            });
        }

        if (filterBranch) {
            filterBranch.addEventListener('change', () => this.loadData());
        }

        if (filterMealType) {
            filterMealType.addEventListener('change', () => this.loadData());
        }
    },

    onCycleFilterChange() {
        const cycleId = document.getElementById('filter-cycle')?.value || '';
        const cycle = this.cycles.find(c => c.id == cycleId);
        
        const filterSchool = document.getElementById('filter-school');
        const filterBranch = document.getElementById('filter-branch');
        if (!filterSchool) return;

        // Resetear selecciones previas de colegio y sede
        filterSchool.value = '';
        if (filterBranch) {
            filterBranch.innerHTML = '<option value="">-- Seleccione una institución primero --</option>';
            filterBranch.disabled = true;
        }

        // Determinar qué colegios están permitidos en este ciclo
        let allowedSchools = this.schools;
        if (cycle && cycle.branch_ids) {
            const allowedBranchIds = cycle.branch_ids.split(',').map(id => parseInt(id.trim()));
            const cycleBranches = this.branches.filter(b => allowedBranchIds.includes(b.id));
            const allowedSchoolIds = [...new Set(cycleBranches.map(b => b.school_id))];
            allowedSchools = this.schools.filter(s => allowedSchoolIds.includes(s.id));
        }

        // Reconstruir opciones del select de colegio
        let html = '<option value="">-- Todas las Instituciones --</option>';
        allowedSchools.forEach(s => {
            html += `<option value="${s.id}">${s.name}</option>`;
        });
        filterSchool.innerHTML = html;
    },

    onSchoolFilterChange() {
        const cycleId = document.getElementById('filter-cycle')?.value || '';
        const cycle = this.cycles.find(c => c.id == cycleId);
        const schoolId = document.getElementById('filter-school')?.value || '';
        const filterBranch = document.getElementById('filter-branch');
        if (!filterBranch) return;

        if (!schoolId) {
            filterBranch.innerHTML = '<option value="">-- Seleccione una institución primero --</option>';
            filterBranch.disabled = true;
            return;
        }

        // Obtener sedes pertenecientes a este colegio
        let schoolBranches = this.branches.filter(b => b.school_id == schoolId);

        // Si el ciclo tiene sedes específicas, filtrar más aún
        if (cycle && cycle.branch_ids) {
            const allowedBranchIds = cycle.branch_ids.split(',').map(id => parseInt(id.trim()));
            schoolBranches = schoolBranches.filter(b => allowedBranchIds.includes(b.id));
        }

        let html = '<option value="">-- Todas las Sedes --</option>';
        schoolBranches.forEach(b => {
            html += `<option value="${b.id}">${b.name}</option>`;
        });
        filterBranch.innerHTML = html;
        filterBranch.disabled = false;
    },

    resetFilters() {
        const filterCycle = document.getElementById('filter-cycle');
        const filterSchool = document.getElementById('filter-school');
        const filterBranch = document.getElementById('filter-branch');
        const filterMealType = document.getElementById('filter-meal-type');

        if (filterCycle) {
            filterCycle.value = this.cycles[0]?.id || '';
            this.onCycleFilterChange();
        }
        if (filterSchool) filterSchool.value = '';
        if (filterBranch) {
            filterBranch.innerHTML = '<option value="">-- Seleccione una institución primero --</option>';
            filterBranch.disabled = true;
        }
        if (filterMealType) filterMealType.value = '';

        this.loadData();
    },

    groupDataByBeneficiary(data) {
        const grouped = [];
        const map = {};
        
        data.forEach(row => {
            const key = row.document_number || `${row.last_name1}_${row.first_name}`;
            if (!map[key]) {
                map[key] = {
                    document_number: row.document_number,
                    fullName: `${row.last_name1 || ''} ${row.last_name2 || ''} ${row.first_name || ''} ${row.second_name || ''}`.replace(/\s+/g, ' ').trim(),
                    school_name: row.school_name,
                    branch_name: row.branch_name,
                    grade: row.grade,
                    group_name: row.group_name,
                    desayuno: false,
                    media_manana: false,
                    almuerzo: false,
                    media_tarde: false,
                    cena: false
                };
                grouped.push(map[key]);
            }
            
            if (row.consumption_id) {
                const mt = (row.meal_type || '').toLowerCase();
                if (mt.includes('desayuno')) {
                    map[key].desayuno = true;
                } else if (mt.includes('mañana') || mt.includes('manana') || mt.includes('am')) {
                    map[key].media_manana = true;
                } else if (mt.includes('almuerzo')) {
                    map[key].almuerzo = true;
                } else if (mt.includes('tarde') || mt.includes('pm')) {
                    map[key].media_tarde = true;
                } else if (mt.includes('cena')) {
                    map[key].cena = true;
                }
            }
        });
        
        return grouped;
    },

    renderTable(data) {
        const tbody = document.getElementById('reports-attendance-body');
        if (!tbody) return;

        if (this.dataTable) {
            this.dataTable.destroy();
            this.dataTable = null;
        }

        const grouped = this.groupDataByBeneficiary(data);

        tbody.innerHTML = grouped.map((row, index) => {
            const check = '<span class="text-success"><i class="fas fa-check-circle fa-lg"></i></span>';
            const none = '<span class="text-muted">-</span>';

            return `
                <tr>
                    <td class="fw-bold text-secondary text-center">${index + 1}</td>
                    <td class="text-center text-muted fw-bold">${row.document_number || '-'}</td>
                    <td class="fw-bold text-dark">${row.fullName}</td>
                    <td class="text-center">${row.desayuno ? check : none}</td>
                    <td class="text-center">${row.media_manana ? check : none}</td>
                    <td class="text-center">${row.almuerzo ? check : none}</td>
                    <td class="text-center">${row.media_tarde ? check : none}</td>
                    <td class="text-center">${row.cena ? check : none}</td>
                    <td class="text-center"><span class="text-muted small" style="font-style: italic; font-size: 8.5pt;">(Firma física)</span></td>
                </tr>
            `;
        }).join('');

        this.dataTable = Helper.initDataTable('#reports-attendance-table');

        // Forzar recalculo de anchos de columnas de DataTables al cargar
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 50);
    },

    exportExcel() {
        const date = document.getElementById('filter-date')?.value || new Date().toISOString().split('T')[0];
        const schoolId = document.getElementById('filter-school')?.value || '';
        const branchId = document.getElementById('filter-branch')?.value || '';

        let schoolNameText = "TODAS LAS INSTITUCIONES";
        if (schoolId) {
            const schoolObj = this.schools.find(s => s.id == schoolId);
            if (schoolObj) schoolNameText = schoolObj.name;
        }

        let branchNameText = "TODAS LAS SEDES";
        if (branchId) {
            const branchObj = this.branches.find(br => br.id == branchId);
            if (branchObj) branchNameText = branchObj.name;
        }

        let html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8"></head>
            <body>
                <table border="1">
                    <tr><th colspan="8" style="font-size:16pt; background:#1B4F72; color:white; font-weight:bold; text-align:center;">REPORTE DE CONTROL DE ENTREGA DE RACIONES</th></tr>
                    <tr><th colspan="8" style="font-size:10pt; background:#f2f2f2; text-align:left;"><b>FECHA:</b> ${date} | <b>INSTITUCIÓN:</b> ${schoolNameText} | <b>SEDE:</b> ${branchNameText}</th></tr>
                    <tr style="background:#f2f2f2; font-weight:bold;">
                        <th style="text-align:center;">SECUENCIA</th>
                        <th>NOMBRES Y APELLIDOS</th>
                        <th style="text-align:center;">DESAYUNO</th>
                        <th style="text-align:center;">MEDIA MAÑANA</th>
                        <th style="text-align:center;">ALMUERZO</th>
                        <th style="text-align:center;">MEDIA TARDE</th>
                        <th style="text-align:center;">CENA</th>
                        <th>FIRMA/HUELLA</th>
                    </tr>
        `;

        const grouped = this.groupDataByBeneficiary(this.attendanceData);

        grouped.forEach((row, index) => {
            html += `
                <tr>
                    <td style="text-align:center;">${index + 1}</td>
                    <td>${row.fullName}</td>
                    <td style="text-align:center;">${row.desayuno ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.media_manana ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.almuerzo ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.media_tarde ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.cena ? '✔️' : ''}</td>
                    <td></td>
                </tr>
            `;
        });

        html += `</table></body></html>`;

        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        const downloadName = cycle ? `Reporte_Entrega_${cycle.name.replace(/\s+/g, '_')}.xls` : `Reporte_Entrega.xls`;
        a.download = downloadName;
        a.click();
    },

    groupDataBySede(data) {
        const sedes = {};
        
        data.forEach(row => {
            const school = row.school_name || 'Sin Institución';
            const branch = row.branch_name || 'Sin Sede';
            const key = `${school} - ${branch}`;
            
            if (!sedes[key]) {
                sedes[key] = {
                    school_name: school,
                    branch_name: branch,
                    beneficiaries: []
                };
            }
            
            const exists = sedes[key].beneficiaries.some(b => b.document_number === row.document_number);
            if (!exists) {
                const fullName = `${row.last_name1 || ''} ${row.last_name2 || ''} ${row.first_name || ''} ${row.second_name || ''}`.replace(/\s+/g, ' ').trim();
                sedes[key].beneficiaries.push({
                    document_number: row.document_number,
                    fullName: fullName,
                    grade: row.grade,
                    group_name: row.group_name
                });
            }
        });
        
        return Object.values(sedes).sort((a, b) => {
            if (a.school_name !== b.school_name) {
                return a.school_name.localeCompare(b.school_name);
            }
            return a.branch_name.localeCompare(b.branch_name);
        });
    },

    exportExcel() {
        const cycleId = document.getElementById('filter-cycle')?.value || '';
        const cycle = this.cycles.find(c => c.id == cycleId);
        const cycleText = cycle ? `${cycle.name} (del ${cycle.start_date} al ${cycle.end_date})` : 'N/A';
        const schoolId = document.getElementById('filter-school')?.value || '';
        const branchId = document.getElementById('filter-branch')?.value || '';

        let schoolNameText = "TODAS LAS INSTITUCIONES";
        if (schoolId) {
            const schoolObj = this.schools.find(s => s.id == schoolId);
            if (schoolObj) schoolNameText = schoolObj.name;
        }

        let branchNameText = "TODAS LAS SEDES";
        if (branchId) {
            const branchObj = this.branches.find(br => br.id == branchId);
            if (branchObj) branchNameText = branchObj.name;
        }

        let html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8"></head>
            <body>
                <table border="1">
                    <tr><th colspan="9" style="font-size:16pt; background:#1B4F72; color:white; font-weight:bold; text-align:center;">REPORTE DE CONTROL DE ENTREGA DE RACIONES</th></tr>
                    <tr><th colspan="9" style="font-size:10pt; background:#f2f2f2; text-align:left;"><b>CICLO:</b> ${cycleText} | <b>INSTITUCIÓN:</b> ${schoolNameText} | <b>SEDE:</b> ${branchNameText}</th></tr>
                    <tr style="background:#f2f2f2; font-weight:bold;">
                        <th style="text-align:center;">SECUENCIA</th>
                        <th style="text-align:center;">DOCUMENTO</th>
                        <th>NOMBRES Y APELLIDOS</th>
                        <th style="text-align:center;">DESAYUNO</th>
                        <th style="text-align:center;">MEDIA MAÑANA</th>
                        <th style="text-align:center;">ALMUERZO</th>
                        <th style="text-align:center;">MEDIA TARDE</th>
                        <th style="text-align:center;">CENA</th>
                        <th>FIRMA/HUELLA</th>
                    </tr>
        `;

        const grouped = this.groupDataByBeneficiary(this.attendanceData);

        grouped.forEach((row, index) => {
            html += `
                <tr>
                    <td style="text-align:center;">${index + 1}</td>
                    <td style="text-align:center;">${row.document_number || ''}</td>
                    <td>${row.fullName}</td>
                    <td style="text-align:center;">${row.desayuno ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.media_manana ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.almuerzo ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.media_tarde ? '✔️' : ''}</td>
                    <td style="text-align:center;">${row.cena ? '✔️' : ''}</td>
                    <td></td>
                </tr>
            `;
        });

        html += `</table></body></html>`;

        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        const downloadName = cycle ? `Reporte_Entrega_${cycle.name.replace(/\s+/g, '_')}.xls` : `Reporte_Entrega.xls`;
        a.download = downloadName;
        a.click();
    },

    exportPDF() {
        const cycleId = document.getElementById('filter-cycle')?.value || '';
        const cycle = this.cycles.find(c => c.id == cycleId);
        const cycleText = cycle ? `${cycle.name} (del ${cycle.start_date} al ${cycle.end_date})` : 'N/A';

        const sedesGrouped = this.groupDataBySede(this.attendanceData);

        if (sedesGrouped.length === 0) {
            Helper.alert('warning', 'No hay datos para imprimir');
            return;
        }

        const defaultEntity = `${Config.BASE_URL}assets/img/logos/default_entity.png`;
        const defaultOperator = `${Config.BASE_URL}assets/img/logos/default_operator.png`;
        const entityLogoUrl = this.programInfo?.entity_logo_path ? `${Config.BASE_URL}${this.programInfo.entity_logo_path}` : defaultEntity;
        const operatorLogoUrl = this.programInfo?.operator_logo_path ? `${Config.BASE_URL}${this.programInfo.operator_logo_path}` : defaultOperator;

        const printWindow = window.open('', '_blank');
        
        let htmlContent = `
            <html>
                <head>
                    <title>Planilla de Entrega de Raciones</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @page { 
                            size: landscape; 
                            margin: 0; 
                        }
                        @media print {
                            .page-break { page-break-after: always; break-after: page; }
                            .no-print { display: none; }
                        }
                        body { 
                            font-family: Arial, sans-serif; 
                            font-size: 6.5pt; 
                            color: #333; 
                            margin: 0;
                            padding: 0;
                        }
                        .print-page {
                            padding: 1.5cm 1.5cm 1.2cm 1.5cm;
                            box-sizing: border-box;
                            width: 100%;
                        }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th { background-color: #1B4F72 !important; color: white !important; font-weight: bold; text-align: center; font-size: 7.5pt; padding: 4px; border: 1px solid #dee2e6; }
                        td { padding: 4px; border: 1px solid #dee2e6; font-size: 6.5pt; vertical-align: middle; }
                        tr:nth-child(even) { background-color: #f8f9fa; }
                        .logo-img { max-height: 50px; max-width: 120px; object-fit: contain; }
                    </style>
                </head>
                <body>
        `;

        const pagesToPrint = [];

        sedesGrouped.forEach(sede => {
            // Ordenar alfabéticamente los beneficiarios
            sede.beneficiaries.sort((a, b) => a.fullName.localeCompare(b.fullName));

            // Fragmentar la lista de beneficiarios en grupos de 15
            const chunkSize = 15;
            for (let i = 0; i < sede.beneficiaries.length; i += chunkSize) {
                const chunk = sede.beneficiaries.slice(i, i + chunkSize);
                pagesToPrint.push({
                    school_name: sede.school_name,
                    branch_name: sede.branch_name,
                    beneficiaries: chunk,
                    startIndex: i
                });
            }
        });

        pagesToPrint.forEach((page, index) => {
            const isLastPage = index === pagesToPrint.length - 1;
            
            htmlContent += `
                <div class="print-page ${isLastPage ? '' : 'page-break'}">
                    <!-- Encabezado Profesional con Logos -->
                    <div class="row align-items-center mb-3 pb-2 w-100 mx-0">
                        <div class="col-3 text-start ps-0">
                            <img src="${entityLogoUrl}" alt="Logo Entidad" class="logo-img" onerror="this.src='${defaultEntity}'">
                        </div>
                        <div class="col-6 text-center">
                            <h5 class="fw-bold text-uppercase mb-0 text-primary" style="font-size: 10pt; color: #1B4F72 !important;">${this.programInfo?.entity_name || 'PROGRAMA DE ALIMENTACIÓN ESCOLAR'}</h5>
                            <h6 class="fw-bold text-muted text-uppercase mb-1" style="font-size: 8pt;">${this.programInfo?.name || 'PAE'} ${this.programInfo?.contract_number ? `- CONTRATO No: ${this.programInfo.contract_number}` : ''}</h6>
                            <div class="fw-bold mt-1 text-dark text-uppercase" style="font-size: 9pt; border-top: 1px dashed #ccc; padding-top: 4px;">
                                Planilla de Control de Entrega de Raciones
                            </div>
                            <div class="text-muted" style="font-size: 7.5pt; font-weight: normal; margin-top: 2px;">
                                <strong>Ciclo:</strong> ${cycleText} | 
                                <strong>Institución:</strong> ${page.school_name} | 
                                <strong>Sede:</strong> ${page.branch_name}
                            </div>
                        </div>
                        <div class="col-3 text-end pe-0">
                            <img src="${operatorLogoUrl}" alt="Logo Operador" class="logo-img" onerror="this.src='${defaultOperator}'">
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">SECUENCIA</th>
                                <th style="width: 10%; text-align: center;">DOCUMENTO</th>
                                <th style="width: 25%;">NOMBRES Y APELLIDOS</th>
                                <th style="width: 7%; text-align: center;">DESAYUNO</th>
                                <th style="width: 7%; text-align: center;">MEDIA MAÑANA</th>
                                <th style="width: 7%; text-align: center;">ALMUERZO</th>
                                <th style="width: 7%; text-align: center;">MEDIA TARDE</th>
                                <th style="width: 7%; text-align: center;">CENA</th>
                                <th style="width: 25%; text-align: center;">FIRMA/HUELLA</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            page.beneficiaries.forEach((beneficiary, bIndex) => {
                const seqNumber = page.startIndex + bIndex + 1;
                htmlContent += `
                    <tr>
                        <td style="text-align: center; font-weight: bold;">${seqNumber}</td>
                        <td style="text-align: center; font-weight: bold; color: #555;">${beneficiary.document_number || ''}</td>
                        <td style="font-weight: bold;">${beneficiary.fullName}</td>
                        <td style="text-align: center; height: 35px;"></td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td></td>
                    </tr>
                `;
            });

            htmlContent += `
                        </tbody>
                    </table>
                </div>
            `;
        });

        htmlContent += `
                </body>
            </html>
        `;

        printWindow.document.write(htmlContent);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
        }, 500);
    }
};

if (typeof ReportsAttendanceView !== 'undefined') {
    ReportsAttendanceView.init();
}
