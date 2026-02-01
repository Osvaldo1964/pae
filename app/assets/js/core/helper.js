const Helper = {
    /**
     * Initialize a DataTable with standard Spanish configuration
     * @param {string} selector - CSS selector for the table (e.g. '#myTable')
     * @param {object} options - Custom options to override defaults
     */
    initDataTable: (selector, options = {}) => {
        // Destroy if exists to prevent errors
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        }

        const defaults = {
            language: {
                url: '/pae/app/assets/plugins/datatables/es-ES.json' // Load from local
            },
            responsive: true,
            // Bootstrap 5 Friendly Layout: Search (f) top, Table (t) middle, Info (i) & Pagination (p) bottom
            dom: '<"d-flex justify-content-between align-items-center mb-3"f>t<"d-flex justify-content-between align-items-center mt-3"ip>',
            pageLength: 5,
            lengthChange: false
        };

        // Merge defaults with user options (User options take precedence)
        const settings = { ...defaults, ...options };

        return $(selector).DataTable(settings);
    },

    /**
     * Format a number as Currency (COP/USD style)
     * Usage: Helper.formatCurrency(10000) -> "$ 10.000"
     */
    formatCurrency: (amount) => {
        if (!amount && amount !== 0) return '$ 0';
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    },

    /**
     * Format a number with decimals
     * Usage: Helper.formatNumber(1234.5678, 2) -> "1.234,57"
     */
    formatNumber: (number, decimals = 2) => {
        if (!number && number !== 0) return '0';
        return new Intl.NumberFormat('es-CO', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(number);
    },

    /**
     * Format a date string to DD/MM/YYYY
     */
    formatDate: (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    },

    /**
     * Basic Input Sanitation (Prevent XSS)
     */
    sanitize: (str) => {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    },

    /**
     * Clean string for identifiers (remove accents, spaces to underscores)
     * "Camión de Carga" -> "camion_de_carga"
     */
    cleanString: (str) => {
        if (!str) return '';
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .replace(/[^a-z0-9]/g, "_");
    },

    /**
     * Parse currency string back to number
     * "$ 10.000" -> 10000
     */
    parseMoney: (str) => {
        if (!str) return 0;
        return parseFloat(str.replace(/[^0-9,-]/g, '').replace(',', '.'));
    },

    /**
     * Centralized fetch wrapper
     */
    fetchAPI: async (endpoint, options = {}) => {
        const url = endpoint.startsWith('http') ? endpoint : `${Config.API_URL}${endpoint}`;

        const defaultHeaders = Config.getHeaders();
        const settings = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {})
            }
        };

        // If body is FormData, do NOT set Content-Type (let the browser do it with boundary)
        if (options.body instanceof FormData) {
            delete settings.headers['Content-Type'];
        }

        try {
            const response = await fetch(url, settings);

            // Handle Global Session Expiry (401/403)
            if (response.status === 401 || response.status === 403) {
                const data = await response.json();
                if (data.message && (data.message.includes('Token') || data.message.includes('No autorizado'))) {
                    console.warn("Session expired or unauthorized. Redirecting to login...");
                    if (typeof App !== 'undefined' && App.logout) {
                        App.logout();
                    } else {
                        localStorage.removeItem('pae_token');
                        window.location.hash = '#login';
                    }
                }
                return data;
            }

            return await response.json();
        } catch (error) {
            console.error(`Fetch API Error (${endpoint}):`, error);
            throw error;
        }
    },

    /**
     * Standardized Alert
     */
    alert: (type, message, title = '') => {
        const titles = {
            success: '¡Éxito!',
            error: 'Error',
            warning: 'Atención',
            info: 'Información'
        };

        return Swal.fire({
            title: title || titles[type] || '',
            text: message,
            icon: type,
            confirmButtonColor: '#1B4F72'
        });
    },

    /**
     * Standardized Confirmation
     */
    confirm: async (message, title = '¿Estás seguro?') => {
        const result = await Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        });
        return result.isConfirmed;
    }
};
