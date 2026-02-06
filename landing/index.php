<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAE Control | Alimentación Escolar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="landing/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="landing/assets/plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="landing/assets/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1B4F72;
            /* Azul Institucional */
            --secondary-color: #27AE60;
            /* Verde Éxito */
            --accent-color: #F1C40F;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-success {
            background-color: var(--secondary-color);
            border: none;
        }

        .text-primary-custom {
            color: var(--primary-color);
        }

        .hero-section {
            background: linear-gradient(rgba(27, 79, 114, 0.8), rgba(27, 79, 114, 0.6)), url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .stat-card {
            border-left: 5px solid var(--secondary-color);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .event-img {
            height: 200px;
            object-fit: cover;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 40px 0;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-utensils me-2"></i>PAE Control</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#avances">Avances</a></li>
                    <li class="nav-item"><a class="nav-link" href="#eventos">Eventos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#pqrModal">PQR</a></li>
                    <li class="nav-item ms-3">
                        <button class="btn btn-outline-light btn-sm px-3 rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#createPaeModal">
                            <i class="fas fa-plus-circle me-1"></i> Crear PAE
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="app/" class="btn btn-success btn-sm px-4 rounded-pill">Acceso Funcionarios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">Alimentación Escolar Transparente</h1>
            <p class="lead mb-4">Garantizando el bienestar nutricional de nuestros niños, niñas y adolescentes.</p>
            <div class="d-flex justify-content-center gap-3">
                <button class="btn btn-light btn-lg text-primary fw-bold" data-bs-toggle="modal"
                    data-bs-target="#pqrModal">Radicar PQR</button>
                <a href="#avances" class="btn btn-outline-light btn-lg">Ver Avance</a>
            </div>
        </div>
    </section>

    <!-- Avances / Stats -->
    <section id="avances" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center text-primary-custom mb-5">Impacto del Programa</h2>
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="card stat-card h-100 p-4 shadow-sm">
                        <i class="fas fa-school fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">150</h3>
                        <p class="text-muted">Sedes Educativas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card h-100 p-4 shadow-sm">
                        <i class="fas fa-child fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">25,000</h3>
                        <p class="text-muted">Beneficiarios</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card h-100 p-4 shadow-sm">
                        <i class="fas fa-apple-alt fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">4.5M</h3>
                        <p class="text-muted">Raciones Entregadas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card h-100 p-4 shadow-sm">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">98%</h3>
                        <p class="text-muted">Cumplimiento</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Eventos -->
    <section id="eventos" class="py-5">
        <div class="container">
            <h2 class="text-center text-primary-custom mb-5">Próximos Eventos</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="https://placehold.co/800x500/1B4F72/FFFFFF?text=Entrega+de+Raciones"
                            class="card-img-top event-img" alt="Entrega">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">Socialización</span>
                            <h5>Inicio de Entrega R1</h5>
                            <p class="text-muted small">Feb 15, 2026</p>
                            <p class="card-text">Socialización del inicio de operaciones para raciones industrializadas
                                del mes de Febrero.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="https://placehold.co/800x500/F1C40F/333333?text=Comite+de+Seguimiento"
                            class="card-img-top event-img" alt="Comité">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Comité</span>
                            <h5>Comité de Seguimiento</h5>
                            <p class="text-muted small">Mar 01, 2026</p>
                            <p class="card-text">Reunión mensual con rectores y padres de familia para evaluar la
                                calidad del servicio.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="https://placehold.co/800x500/27AE60/FFFFFF?text=Feria+Gastronomica"
                            class="card-img-top event-img" alt="Feria">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Nutrición</span>
                            <h5>Feria Gastronómica</h5>
                            <p class="text-muted small">Mar 20, 2026</p>
                            <p class="card-text">Muestra de alimentos autóctonos integrados al ciclo de menús del PAE.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-2">© 2026 PAE Control - Todos los derechos reservados.</p>
            <small>Transparencia y Eficiencia en la Alimentación Escolar</small>
        </div>
    </footer>

    <!-- PQR Modal -->
    <div class="modal fade" id="pqrModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-envelope-open-text me-2"></i>Registrar PQR</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="pqrForm">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Solicitud</label>
                            <select class="form-select" id="pqrType" required>
                                <option value="">Seleccione...</option>
                                <option value="peticion">Petición</option>
                                <option value="queja">Queja</option>
                                <option value="reclamo">Reclamo</option>
                                <option value="sugerencia">Sugerencia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="pqrEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje</label>
                            <textarea class="form-control" id="pqrMessage" rows="4" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitPQR()">Enviar Solicitud</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create PAE Modal -->
    <div class="modal fade" id="createPaeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-seedling me-2"></i>Crear Nuevo Programa PAE</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="paeForm" enctype="multipart/form-data">
                        <!-- Section 1: Entidad Territorial -->
                        <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-landmark me-2"></i>Datos de
                            la Entidad Territorial</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Nombre del Programa</label>
                                <input type="text" class="form-control" name="name" placeholder="Ej: PAE Magdalena 2026"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Entidad (Gobernación/Alcaldía)</label>
                                <input type="text" class="form-control" name="entity_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">NIT Entidad</label>
                                <input type="text" class="form-control" name="nit" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Departamento</label>
                                <input type="text" class="form-control" name="department" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Ciudad</label>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Logo Entidad (Escudo)</label>
                            <input type="file" class="form-control" name="entity_logo" accept="image/*">
                        </div>

                        <!-- Section 2: Operador -->
                        <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i
                                class="fas fa-briefcase me-2"></i>Datos del Operador</h6>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label small">Razón Social Operador</label>
                                <input type="text" class="form-control" name="operator_name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">NIT Operador</label>
                                <input type="text" class="form-control" name="operator_nit" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Dirección</label>
                                <input type="text" class="form-control" name="operator_address">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Teléfono</label>
                                <input type="text" class="form-control" name="operator_phone">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Email Operador</label>
                                <input type="email" class="form-control" name="operator_email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Logo Operador</label>
                                <input type="file" class="form-control" name="operator_logo" accept="image/*">
                            </div>
                        </div>

                        <!-- Section 3: Admin -->
                        <h6 class="text-primary-custom border-bottom pb-2 mb-3 mt-4"><i
                                class="fas fa-user-shield me-2"></i>Usuario Administrador</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Email Admin</label>
                                <input type="email" class="form-control" name="admin_email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Contraseña</label>
                                <input type="password" class="form-control" name="admin_password" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="createPae()">Crear Programa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Scripts -->
    <script src="landing/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="landing/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script>
        async function submitPQR() {
            // ... (keeping existing PQR logic, just showing context)
            const type = document.getElementById('pqrType').value;
            const email = document.getElementById('pqrEmail').value;
            const message = document.getElementById('pqrMessage').value;

            if (!type || !email || !message) {
                Swal.fire('Error', 'Por favor complete todos los campos', 'warning');
                return;
            }

            try {
                const response = await fetch('api/public/pqr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type,
                        email,
                        message
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire('¡Enviado!', result.message, 'success');
                    document.getElementById('pqrForm').reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('pqrModal'));
                    modal.hide();
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            }
        }

        async function createPae() {
            const form = document.getElementById('paeForm');
            const formData = new FormData(form);

            // Basic Validation
            if (!formData.get('name') || !formData.get('entity_name') || !formData.get('nit') ||
                !formData.get('admin_email') || !formData.get('admin_password') || !formData.get('operator_name')) {
                Swal.fire('Atención', 'Por favor complete todos los campos obligatorios del programa y operador', 'warning');
                return;
            }

            // Optional: Map 'email' to match existing controller expectation if needed, or update form names.
            // Controller expects: email (program contact), admin_email (user).
            // We'll use admin_email for both if program email isn't explicit, or add it to FormData.
            if (!formData.has('email')) formData.append('email', formData.get('admin_email'));

            try {
                const response = await fetch('api/tenant/register', {
                    method: 'POST',
                    body: formData // No Content-Type header needed, browser adds it with boundary
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        title: '¡Programa Creado!',
                        text: 'El programa y el operador han sido registrados. Inicie sesión.',
                        icon: 'success',
                        confirmButtonText: 'Ir al Login'
                    }).then((result) => {
                        window.location.href = 'app/';
                    });

                    const modal = bootstrap.Modal.getInstance(document.getElementById('createPaeModal'));
                    modal.hide();
                } else {
                    Swal.fire('Error', result.message || 'Error desconocido', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            }
        }
    </script>
</body>

</html>