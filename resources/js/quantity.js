export function initQuantityForm() {
    const form = document.querySelector('[data-quantity-form]');
    if (!form) {
        return;
    }

    const valueEl = document.querySelector('[data-quantity-value]');
    const feedbackEl = document.querySelector('[data-quantity-feedback]');
    const buttons = form.querySelectorAll('button');

    function showFeedback(message, type) {
        feedbackEl.textContent = message;
        feedbackEl.className = `alert alert-${type}`;
        feedbackEl.hidden = false;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // submitter iekļauj nospiestās pogas name/value (action=increase|decrease)
        const formData = new FormData(form, event.submitter);

        buttons.forEach((button) => (button.disabled = true));

        try {
            // POST + _method=PATCH no formas: PHP nepārsēj multipart datus PATCH pieprasījumā
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await response.json().catch(() => ({}));

            if (response.ok) {
                valueEl.textContent = data.quantity;
                showFeedback(data.message, 'success');
            } else if (response.status === 422) {
                const messages = Object.values(data.errors ?? {}).flat();
                showFeedback(messages.join(' ') || 'Invalid input.', 'error');
            } else {
                showFeedback('Unexpected server error. Please try again.', 'error');
            }
        } catch {
            showFeedback('Network error. Please try again.', 'error');
        } finally {
            buttons.forEach((button) => (button.disabled = false));
        }
    });
}