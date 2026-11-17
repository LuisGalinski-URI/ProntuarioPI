import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const sidebarToggle = document.querySelector('[data-sidebar-toggle]');

sidebarToggle?.addEventListener('click', () => {
    const shell = document.querySelector('.app-shell');
    const isCollapsed = shell?.classList.toggle('app-shell--collapsed') ?? false;

    sidebarToggle.setAttribute('aria-expanded', String(!isCollapsed));
    sidebarToggle.setAttribute('aria-label', isCollapsed ? 'Expandir menu' : 'Recolher menu');
});

document.querySelectorAll('[data-consulta-status-form]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');
        const errorMessage = form.querySelector('[data-status-error]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        button.disabled = true;
        errorMessage.textContent = '';

        try {
            const response = await window.axios.patch(form.action, {}, {
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (response.data.status !== 'Realizado') {
                throw new Error('O status retornado não é válido.');
            }

            button.textContent = response.data.status;
            button.classList.remove('btn-outline-warning');
            button.classList.add('btn-success');
        } catch (error) {
            errorMessage.textContent = error.response?.data?.message
                ?? 'Não foi possível atualizar o status. Tente novamente.';
            button.disabled = false;
        }
    });
});
