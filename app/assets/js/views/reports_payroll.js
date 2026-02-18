/**
 * Reportes de Talento Humano (Nómina)
 */
var ReportsPayrollView = {
    periods: [],

    init: async () => {
        Helper.loading(true);
        ReportsPayrollView.render();
        await ReportsPayrollView.loadPeriods();
        Helper.loading(false);
    },

    render: () => {
        const container = document.getElementById('app-container');
        container.innerHTML = `
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="#group/5" class="btn btn-link text-muted ps-0"><i class="fas fa-arrow-left"></i> Volver</a>
                        <h2 class="mb-1"><i class="fas fa-user-tie me-2 text-info"></i>Talento Humano</h2>
                        <p class="text-muted mb-0">Generación de soportes de pago y consolidados de nómina</p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Nómina General -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-info-light mb-3">
                                    <i class="fas fa-file-invoice-dollar text-info fa-2x"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Nómina General</h5>
                                <p class="text-muted small mb-4">Genera un documento con el resumen de todos los pagos realizados en un periodo específico.</p>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">PERIODO DE PAGO</label>
                                    <select id="report-pay-period-gen" class="form-select select-periods-report">
                                        <option value="">-- Seleccione Periodo --</option>
                                    </select>
                                </div>
                                
                                <button class="btn btn-info text-white w-100 fw-bold rounded-pill" onclick="ReportsPayrollView.generate('general')">
                                    <i class="fas fa-print me-2"></i> Generar Nómina General
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Desprendibles Individuales -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-primary-light mb-3">
                                    <i class="fas fa-id-card text-primary fa-2x"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Desprendibles de Pago</h5>
                                <p class="text-muted small mb-4">Genera los soportes individuales de pago para entregar a cada uno de los empleados.</p>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">PERIODO DE PAGO</label>
                                    <select id="report-pay-period-ind" class="form-select select-periods-report">
                                        <option value="">-- Seleccione Periodo --</option>
                                    </select>
                                </div>
                                
                                <button class="btn btn-primary w-100 fw-bold rounded-pill" onclick="ReportsPayrollView.generate('individual')">
                                    <i class="fas fa-print me-2"></i> Generar Desprendibles
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    loadPeriods: async () => {
        const res = await App.api('/hr-payroll/periods');
        if (res.success && res.data) {
            ReportsPayrollView.periods = res.data;
            const selects = document.querySelectorAll('.select-periods-report');
            let options = '<option value="">-- Seleccione Periodo --</option>';
            res.data.forEach(p => {
                options += `<option value="${p.id}">${p.name} (${p.start_date} / ${p.end_date})</option>`;
            });
            selects.forEach(s => s.innerHTML = options);
        }
    },

    generate: async (type) => {
        const periodId = type === 'general'
            ? document.getElementById('report-pay-period-gen').value
            : document.getElementById('report-pay-period-ind').value;

        if (!periodId) {
            Helper.alert('warning', 'Seleccione un periodo');
            return;
        }

        Helper.loading(true);
        const res = await App.api(`/hr-payroll/report/${periodId}`);
        Helper.loading(false);

        if (res.success && res.data.length > 0) {
            const period = ReportsPayrollView.periods.find(p => p.id == periodId);
            if (type === 'general') {
                ReportsPayrollView.printGeneral(res.data, period);
            } else {
                ReportsPayrollView.printIndividual(res.data, period);
            }
        } else {
            Helper.alert('info', 'No hay datos liquidados en este periodo.');
        }
    },

    printGeneral: (data, period) => {
        const printWindow = window.open('', '_blank');
        let rowsHtml = '';
        let grandTotal = 0;

        data.forEach(r => {
            grandTotal += parseFloat(r.total_neto);
            rowsHtml += `
                <tr>
                    <td><b>${r.first_name} ${r.last_name1}</b><br><small>${r.document_number}</small></td>
                    <td>${r.position_name || 'N/A'}</td>
                    <td class="text-end">${Helper.formatCurrency(r.total_devengado)}</td>
                    <td class="text-end">${Helper.formatCurrency(r.total_deduccion)}</td>
                    <td class="text-end fw-bold">${Helper.formatCurrency(r.total_neto)}</td>
                </tr>
            `;
        });

        printWindow.document.write(`
            <html>
                <head>
                    <title>Nomina General - ${period.name}</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 40px; font-family: Arial, sans-serif; font-size: 10pt; }
                        .header-rep { border-bottom: 2px solid #1a1a1a; margin-bottom: 20px; padding-bottom: 10px; }
                        table th { background: #f8f9fa !important; border-top: 2px solid #000 !important; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="text-end no-print mb-4">
                        <button class="btn btn-primary" onclick="window.print()">Imprimir PDF</button>
                    </div>
                    <div class="header-rep d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold">CONSOLIDADO DE NÓMINA</h2>
                            <h5 class="text-muted">${period.name.toUpperCase()} (${period.start_date} al ${period.end_date})</h5>
                        </div>
                    </div>
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Cargo</th>
                                <th class="text-end">Devengados</th>
                                <th class="text-end">Deducciones</th>
                                <th class="text-end">Neto a Pagar</th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold">TOTAL GENERAL:</td>
                                <td class="text-end fw-bold" style="font-size: 12pt">${Helper.formatCurrency(grandTotal)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </body>
            </html>
        `);
        printWindow.document.close();
    },

    printIndividual: (data, period) => {
        const printWindow = window.open('', '_blank');
        let slipsHtml = '';

        data.forEach(r => {
            let detailsHtml = '';
            r.details.forEach(d => {
                const amount = parseFloat(d.amount);
                detailsHtml += `
                    <tr>
                        <td>${d.description}</td>
                        <td class="text-end">${amount > 0 ? Helper.formatCurrency(amount) : '-'}</td>
                        <td class="text-end">${amount < 0 ? Helper.formatCurrency(Math.abs(amount)) : '-'}</td>
                    </tr>
                `;
            });

            slipsHtml += `
                <div class="slip-container">
                    <div class="border p-4" style="height: 14cm; border: 1.5px solid #000 !important; border-radius: 8px;">
                        <div class="row mb-4 border-bottom pb-2">
                            <div class="col-8">
                                <h5 class="fw-bold mb-0">DESPRENDIBLE DE PAGO DE NÓMINA</h5>
                                <small>${period.name.toUpperCase()} | ${period.start_date} al ${period.end_date}</small>
                            </div>
                            <div class="col-4 text-end">
                                <small class="text-muted">PAE CONTROL</small>
                            </div>
                        </div>

                        <div class="row g-2 mb-4 bg-light p-2 rounded">
                            <div class="col-8">
                                <div class="small fw-bold">EMPLEADO:</div>
                                <div class="text-uppercase">${r.first_name} ${r.last_name1}</div>
                                <div class="small mt-1"><span class="fw-bold">ID:</span> ${r.document_number}</div>
                            </div>
                            <div class="col-4">
                                <div class="small fw-bold">CARGO:</div>
                                <div class="text-uppercase">${r.position_name || 'N/A'}</div>
                            </div>
                        </div>

                        <table class="table table-sm table-striped border">
                            <thead>
                                <tr class="small text-uppercase fw-bold">
                                    <th>Concepto</th>
                                    <th class="text-end">Devengados</th>
                                    <th class="text-end">Deducciones</th>
                                </tr>
                            </thead>
                            <tbody class="small">${detailsHtml}</tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>TOTALES:</td>
                                    <td class="text-end">${Helper.formatCurrency(r.total_devengado)}</td>
                                    <td class="text-end">${Helper.formatCurrency(r.total_deduccion)}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="mt-4 row align-items-end">
                            <div class="col-6">
                                <div class="border-top text-center pt-2 small mt-5" style="border-top: 1px solid #000 !important;">
                                    Firma del Empleado
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="bg-dark text-white p-3 rounded d-inline-block">
                                    <div class="small">NETO RECIBIDO:</div>
                                    <div class="h5 mb-0 fw-bold">${Helper.formatCurrency(r.total_neto)}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="page-break-after: always;"></div>
            `;
        });

        printWindow.document.write(`
            <html>
                <head>
                    <title>Desprendibles - ${period.name}</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 40px; font-family: Arial, sans-serif; }
                        .slip-container { padding: 10px; margin-bottom: 20px; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="text-end no-print mb-4">
                        <button class="btn btn-primary" onclick="window.print()">Imprimir Todo</button>
                    </div>
                    ${slipsHtml}
                </body>
            </html>
        `);
        printWindow.document.close();
    }
};

ReportsPayrollView.init();
