/**
 * Print Manual Signature List Module
 */
const PrintListView = {
    modalId: 'modalPrintList',

    /**
     * Open the configuration modal
     */
    openModal: async () => {
        // Inject modal if not exists
        if (!document.getElementById(this.modalId)) {
            PrintListView.injectModal();
        }

        // Load branches (assuming BeneficiariesView has them, or fetch anew)
        // Better to fetch anew to be standalone
        await PrintListView.loadBranches();

        new bootstrap.Modal(document.getElementById(PrintListView.modalId)).show();
    },

    /**
     * Inject Modal HTML into DOM
     */
    injectModal: () => {
        const html = `
            <div class="modal fade" id="${PrintListView.modalId}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-print me-2"></i>Imprimir Listas de Firmas</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formPrintList">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sede Educativa *</label>
                                    <select class="form-select" id="print-branch-id" required>
                                        <option value="">Cargando...</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Grado (Opcional)</label>
                                        <select class="form-select" id="print-grade">
                                            <option value="">Diferentes Grados</option>
                                            <option value="TRANSICION">Transición</option>
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
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Grupo (Opcional)</label>
                                        <input type="text" class="form-control" id="print-group" placeholder="Ej: 01">
                                    </div>
                                </div>
                                
                                <label class="form-label fw-bold">Rango de Fechas</label>
                                <div class="btn-group w-100 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="PrintListView.setDateRange('week')">Esta Semana</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="PrintListView.setDateRange('month')">Este Mes</button>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="date" class="form-control" id="print-start-date" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control" id="print-end-date" required>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="PrintListView.generate()">
                                <i class="fas fa-print me-2"></i>Generar Lista
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', html);

        // Set default dates (This week)
        PrintListView.setDateRange('week');
    },

    /**
     * Load branches for the dropdown
     */
    loadBranches: async () => {
        try {
            // We need all branches, not filtered by school ideally, but the API requires school_id or returns all?
            // Checking existing API usage: /branches?school_id=...
            // If we want all branches for the logged user's entity, we might need a different endpoint or fetch all schools then branches.
            // For now, let's reuse the school list if Global 'BeneficiariesView.schools' exists, otherwise fetch.

            let branches = [];

            // Try to find schools from the current view context
            let schools = BeneficiariesView?.schools || [];

            if (schools.length === 0) {
                schools = await Helper.fetchAPI('/schools');
            }

            const select = document.getElementById('print-branch-id');
            select.innerHTML = '<option value="">Seleccione Sede...</option>';

            // We need to fetch branches for EACH school if we want a full list
            // This might be heavy. Let's see if we can get all branches directly.
            // BranchController index takes school_id. If null?
            // Let's try fetching /branches without school_id. 
            // The controller code: $school_id = $action ?: ($_GET['school_id'] ?? null);
            // If school_id is null, does it return all? 
            // Looking at BranchController (I haven't seen it but I can guess or try). 
            // Safe bet: Fetch schools, then allow user to select School THEN Branch? 
            // Or just iterate schools and fetch branches.
            // Optimized approach for now: modifying the modal to select School first, then Branch.

            // Re-inject modal with School selection? No, let's just use what we have.
            // Assuming the user knows the school context.
            // Actually, let's just modify the injectModal to include School selection for better UX.
            // BUT, to save time/complexity, let's assume we can loop schools and fetch their branches.

            for (const school of schools) {
                const schoolBranches = await Helper.fetchAPI(`/branches?school_id=${school.id}`);
                if (Array.isArray(schoolBranches)) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = school.name;
                    schoolBranches.forEach(b => {
                        const option = document.createElement('option');
                        option.value = b.id;
                        option.text = b.name;
                        optgroup.appendChild(option);
                    });
                    select.appendChild(optgroup);
                }
            }

        } catch (e) {
            console.error("Error loading branches for print", e);
            Helper.alert('error', 'Error cargando sedes');
        }
    },

    setDateRange: (type) => {
        const today = new Date();
        const start = new Date(today);
        const end = new Date(today);

        if (type === 'week') {
            const day = start.getDay() || 7; // Get current day number, converting Sun(0) to 7
            if (day !== 1) start.setHours(-24 * (day - 1)); // Set to Monday
            end.setDate(start.getDate() + 4); // Set to Friday (Monday + 4 days)
        } else if (type === 'month') {
            start.setDate(1);
            end.setMonth(start.getMonth() + 1);
            end.setDate(0); // Last day of month
        }

        document.getElementById('print-start-date').value = start.toISOString().split('T')[0];
        document.getElementById('print-end-date').value = end.toISOString().split('T')[0];
    },

    generate: async () => {
        const branchId = document.getElementById('print-branch-id').value;
        const grade = document.getElementById('print-grade').value;
        const group = document.getElementById('print-group').value;
        const startDateStr = document.getElementById('print-start-date').value;
        const endDateStr = document.getElementById('print-end-date').value;

        if (!branchId || !startDateStr || !endDateStr) {
            Helper.alert('warning', 'Por favor complete los campos obligatorios');
            return;
        }

        try {
            // Build query params
            const params = new URLSearchParams({
                branch_id: branchId,
                grade: grade,
                group_name: group
            });

            const res = await Helper.fetchAPI(`/beneficiarios/print-list?${params.toString()}`);

            if (!res.success) {
                Helper.alert('error', res.message || 'No se encontraron datos');
                return;
            }

            const beneficiaries = res.data;
            if (beneficiaries.length === 0) {
                Helper.alert('info', 'No hay beneficiarios que coincidan con los filtros.');
                return;
            }

            // Generate HTML
            const html = PrintListView.buildHTML(beneficiaries, res.branch, startDateStr, endDateStr);
            Helper.printHTML(html);

            bootstrap.Modal.getInstance(document.getElementById(PrintListView.modalId)).hide();

        } catch (e) {
            console.error(e);
            Helper.alert('error', 'Error generando lista');
        }
    },

    buildHTML: (list, branchInfo, startDateStr, endDateStr) => {
        // Calculate days
        const start = new Date(startDateStr);
        const end = new Date(endDateStr);
        const dates = [];

        let loop = new Date(start);
        while (loop <= end) {
            // Skip weekends if needed? Usually PAE is Mon-Fri. Let's include all selected days.
            // user request: "generar la tabla dinámica con los días"
            dates.push(new Date(loop));
            loop.setDate(loop.getDate() + 1);
        }

        const cols = dates.length;
        const colWidth = 40 / (cols || 1); // Distribute 40% of width among days

        let tableHeaderDays = '';
        dates.forEach(d => {
            const dayName = d.toLocaleDateString('es-ES', { weekday: 'short' }).toUpperCase();
            const dayNum = d.getDate();
            tableHeaderDays += `<th style="width: ${colWidth}%; font-size: 10px; text-align: center;">${dayName}<br>${dayNum}</th>`;
        });

        let rows = '';
        list.forEach((b, index) => {
            let cells = '';
            for (let i = 0; i < cols; i++) {
                cells += '<td style="border: 1px solid #000;"></td>';
            }

            rows += `
                <tr>
                    <td style="text-align: center;">${index + 1}</td>
                    <td>
                        <div style="font-weight: bold;">${b.last_name1} ${b.last_name2 || ''}</div>
                        <div>${b.first_name} ${b.second_name || ''}</div>
                    </td>
                    <td style="text-align: center;">${b.document_number}</td>
                    <td style="text-align: center;">${b.grade} - ${b.group_name || ''}</td>
                    ${cells}
                </tr>
            `;
        });

        // HTML Template
        return `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Lista de Asistencia - PAE</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #000; padding: 4px; }
                    th { background-color: #f0f0f0; }
                    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
                    .header img { height: 50px; }
                    .header-info { text-align: center; flex-grow: 1; }
                    .header-info h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
                    .header-info p { margin: 2px 0; font-size: 12px; }
                    @media print {
                        @page { size: landscape; margin: 10mm; }
                        th { background-color: #eee !important; -webkit-print-color-adjust: exact; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div>
                        <!-- Logo Placeholder -->
                    </div>
                    <div class="header-info">
                        <h2>Control de Entrega - Ración Preparada en Sitio</h2>
                        <p><strong>Institución:</strong> ${branchInfo.school_name || ''}</p>
                        <p><strong>Sede:</strong> ${branchInfo.branch_name || ''} - <strong>Rango:</strong> ${Helper.formatDate(startDateStr)} a ${Helper.formatDate(endDateStr)}</p>
                    </div>
                    <div>
                        <!-- Logo Placeholder -->
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 3%;">No.</th>
                            <th style="width: 35%;">Apellidos y Nombres</th>
                            <th style="width: 12%;">Documento</th>
                            <th style="width: 10%;">Grado</th>
                            ${tableHeaderDays}
                        </tr>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>
                
                <div style="margin-top: 20px; text-align: center; font-size: 10px;">
                    <p>Firma del Docente / Responsable de Entrega</p>
                    <div style="border-top: 1px solid #000; width: 300px; margin: 30px auto 0;"></div>
                </div>
            </body>
            </html>
        `;
    }
};
