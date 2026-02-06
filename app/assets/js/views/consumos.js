/**
 * Consumos - Registro de Asistencia QR
 */
const ConsumosView = {
    schools: [],
    branches: [],
    dataTable: null,

    init: async () => {
        ConsumosView.render();
        await ConsumosView.loadFilters();
        ConsumosView.attachEvents();
        // Load today's report by default if possible or wait for filter
    },

    render: () => {
        const container = document.getElementById('app-container');
        container.innerHTML = `
            <div class="row mb-4 fade-in">
                <div class="col-md-8">
                    <h2 class="text-primary-custom fw-bold mb-0">Asistencia y Consumo (QR)</h2>
                    <p class="text-muted">Registro detallado de entregas capturadas por escáner móvil.</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="ConsumosView.printCurrent()">
                        <i class="fas fa-print me-2"></i>Imprimir Planilla
                    </button>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body bg-light rounded-3 p-4">
                    <form id="filter-form" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-uppercase">Institución</label>
                            <select id="filter-school" class="form-select border-0 shadow-sm">
                                <option value="">Todas las Instituciones</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-uppercase">Sede Educativa</label>
                            <select id="filter-branch" class="form-select border-0 shadow-sm">
                                <option value="">Todas las Sedes</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-uppercase">Fecha</label>
                            <input type="date" id="filter-date" class="form-control border-0 shadow-sm" value="${new Date().toISOString().split('T')[0]}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-uppercase">Complemento</label>
                            <select id="filter-meal" class="form-select border-0 shadow-sm">
                                <option value="">Todos</option>
                                <option value="AM">AM (Desayuno)</option>
                                <option value="ALMUERZO">Almuerzo</option>
                                <option value="PM">PM (Refrigerio)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100 rounded-3 shadow-sm">
                                <i class="fas fa-search me-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive p-4">
                        <table id="consumosTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="bg-light text-secondary text-uppercase small fw-bold">
                                <tr>
                                    <th class="ps-4">Hora</th>
                                    <th>Estudiante</th>
                                    <th>Documento</th>
                                    <th>Grado/Grupo</th>
                                    <th>Sede</th>
                                    <th>Tipo</th>
                                    <th class="text-center pe-4">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="consumos-table-body">
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                        <p class="text-muted">Use los filtros arriba para ver los datos de consumo.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    },

    loadFilters: async () => {
        try {
            const schools = await Helper.fetchAPI('/schools');
            const schoolSelect = document.getElementById('filter-school');
            schools.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                schoolSelect.appendChild(opt);
            });

            schoolSelect.onchange = async () => {
                const schoolId = schoolSelect.value;
                const branchSelect = document.getElementById('filter-branch');
                branchSelect.innerHTML = '<option value="">Todas las Sedes</option>';

                if (schoolId) {
                    const branches = await Helper.fetchAPI(`/branches?school_id=${schoolId}`);
                    branches.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.id;
                        opt.textContent = b.name;
                        branchSelect.appendChild(opt);
                    });
                }
            };
        } catch (e) {
            console.error("Error loading filters", e);
        }
    },

    attachEvents: () => {
        document.getElementById('filter-form').onsubmit = (e) => {
            e.preventDefault();
            ConsumosView.loadData();
        };
    },

    loadData: async () => {
        const schoolId = document.getElementById('filter-school').value;
        const branchId = document.getElementById('filter-branch').value;
        const date = document.getElementById('filter-date').value;
        const meal = document.getElementById('filter-meal').value;

        if (!date) {
            Helper.alert('warning', 'La fecha es obligatoria');
            return;
        }

        Helper.loading(true);
        try {
            const params = new URLSearchParams({ date });
            if (branchId) params.append('branch_id', branchId);
            if (meal) params.append('meal_type', meal);

            const res = await Helper.fetchAPI(`/consumptions/report?${params.toString()}`);
            Helper.loading(false);

            if (res.success) {
                ConsumosView.renderTable(res.data);
            }
        } catch (e) {
            Helper.loading(false);
            console.error(e);
            Helper.alert('error', 'Error cargando datos');
        }
    },

    renderTable: (data) => {
        const tbody = document.getElementById('consumos-table-body');

        if (ConsumosView.dataTable) {
            ConsumosView.dataTable.destroy();
        }

        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">No se encontraron registros para los filtros seleccionados.</td></tr>';
            return;
        }

        data.forEach(row => {
            const time = new Date(row.time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            tbody.innerHTML += `
                <tr>
                    <td class="ps-4 fw-bold text-primary-custom">${time}</td>
                    <td>
                        <div class="fw-bold text-dark">${row.last_name1} ${row.first_name}</div>
                    </td>
                    <td><code class="text-muted">${row.document_number}</code></td>
                    <td><span class="small">${row.grade} - ${row.group_name || ''}</span></td>
                    <td class="small">${row.branch_name}</td>
                    <td><span class="badge bg-light text-dark border">${row.meal_type}</span></td>
                    <td class="text-center pe-4">
                        <span class="badge rounded-pill bg-success px-3">
                            <i class="fas fa-check-circle me-1"></i>ENTREGADO
                        </span>
                    </td>
                </tr>
            `;
        });

        ConsumosView.dataTable = Helper.initDataTable('#consumosTable', {
            order: [[0, 'desc']],
            pageLength: 25
        });
    },

    printCurrent: () => {
        const date = document.getElementById('filter-date').value;
        const branchSelect = document.getElementById('filter-branch');
        const branchName = branchSelect.options[branchSelect.selectedIndex].text;
        const mealMode = document.getElementById('filter-meal').value || 'GENERAL';

        if (!ConsumosView.dataTable || ConsumosView.dataTable.rows().count() === 0) {
            Helper.alert('warning', 'No hay datos para imprimir. Realice una búsqueda primero.');
            return;
        }

        // Gather data from visible items in table (or current loaded list)
        // For printing, we build a specialized HTML layout
        const data = [];
        const rows = document.querySelectorAll('#consumos-table-body tr');
        rows.forEach(tr => {
            const tds = tr.querySelectorAll('td');
            if (tds.length < 7) return;
            data.push({
                time: tds[0].innerText,
                name: tds[1].innerText,
                doc: tds[2].innerText,
                grade: tds[3].innerText,
                branch: tds[4].innerText,
                type: tds[5].innerText
            });
        });

        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Planilla de Consumo PAE</title>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; margin: 0; padding: 20px; }
                    .header { border-bottom: 2px solid #1B4F72; margin-bottom: 20px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
                    .header-info h2 { margin: 0; color: #1B4F72; font-size: 18px; }
                    .header-info p { margin: 2px 0; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f8f9fa; font-weight: bold; color: #333; text-transform: uppercase; font-size: 9px; }
                    .footer { margin-top: 30px; display: flex; justify-content: space-around; }
                    .sig-box { border-top: 1px solid #000; width: 200px; text-align: center; padding-top: 5px; margin-top: 50px; font-size: 10px; }
                    @media print {
                        @page { size: portrait; margin: 10mm; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="header-info">
                        <h2>PLANILLA DIARIA DE CONSUMO (REGISTRO QR)</h2>
                        <p><strong>Programa:</strong> Control PAE v1.5</p>
                        <p><strong>Sede:</strong> ${branchName !== 'Todas las Sedes' ? branchName : 'VARIAS SEDES'}</p>
                    </div>
                    <div style="text-align: right">
                        <p><strong>Fecha:</strong> ${Helper.formatDate(date)}</p>
                        <p><strong>Filtro:</strong> ${mealMode}</p>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px">No.</th>
                            <th style="width: 80px">Hora</th>
                            <th>Apellidos y Nombres</th>
                            <th>Documento</th>
                            <th>Grado</th>
                            <th>Tipo</th>
                            <th style="width: 100px">Firma / Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map((r, i) => `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${r.time}</td>
                                <td style="font-weight: bold">${r.name}</td>
                                <td>${r.doc}</td>
                                <td>${r.grade}</td>
                                <td>${r.type}</td>
                                <td style="text-align: center; color: #27AE60; font-weight: bold">ENTREGADO (QR)</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div class="footer">
                    <div class="sig-box">Responsable de Entrega</div>
                    <div class="sig-box">Veedor / Delegado</div>
                </div>
            </body>
            </html>
        `;

        Helper.printHTML(html);
    }
};

// Auto-init
ConsumosView.init();
