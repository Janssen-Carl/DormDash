import './bootstrap';
import '../css/styles.css';
import '../css/app.css';

// Premium Global Custom Confirm Interceptor
document.addEventListener('DOMContentLoaded', () => {
    
    // Intercept form submissions (onsubmit)
    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (form.dataset.confirmed === 'true') {
            return;
        }

        const onsubmitAttr = form.getAttribute('onsubmit');
        if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
            event.preventDefault();
            event.stopImmediatePropagation();

            // Extract confirmation message
            let message = "Are you sure you want to proceed?";
            const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                message = match[1].replace(/\\'/g, "'").replace(/\\"/g, '"');
            }

            showCustomConfirm(message, function (agreed) {
                if (agreed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        }
    }, true);

    // Intercept generic button/link clicks (onclick)
    document.addEventListener('click', function (event) {
        const target = event.target.closest('[onclick]');
        if (!target) return;

        const onclickAttr = target.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes('confirm(')) {
            if (target.dataset.confirmed === 'true') {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            let message = "Are you sure you want to proceed?";
            const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                message = match[1].replace(/\\'/g, "'").replace(/\\"/g, '"');
            }

            showCustomConfirm(message, function (agreed) {
                if (agreed) {
                    target.dataset.confirmed = 'true';
                    target.click();
                }
            });
        }
    }, true);
});

// Beautiful Premium custom confirmation dialog
function showCustomConfirm(message, callback) {
    const overlay = document.createElement('div');
    overlay.className = 'custom-confirm-overlay';

    overlay.innerHTML = `
        <div class="custom-confirm-modal">
            <div class="custom-confirm-header">
                <div class="custom-confirm-icon">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3>Confirmation Required</h3>
            </div>
            <div class="custom-confirm-body">
                <p id="custom-confirm-message"></p>
            </div>
            <div class="custom-confirm-footer">
                <button id="custom-confirm-cancel">Cancel</button>
                <button id="custom-confirm-ok">Confirm</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);
    
    // Set message safely
    overlay.querySelector('#custom-confirm-message').textContent = message;

    const okBtn = overlay.querySelector('#custom-confirm-ok');
    const cancelBtn = overlay.querySelector('#custom-confirm-cancel');

    // Trigger visual transitions
    requestAnimationFrame(() => {
        overlay.classList.add('show');
        okBtn.focus();
    });

    let finished = false;
    function closeConfirm(agreed) {
        if (finished) return;
        finished = true;
        
        overlay.classList.remove('show');
        document.removeEventListener('keydown', handleKeydown);
        
        setTimeout(() => {
            overlay.remove();
            callback(agreed);
        }, 250);
    }

    okBtn.addEventListener('click', () => closeConfirm(true));
    cancelBtn.addEventListener('click', () => closeConfirm(false));
    
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeConfirm(false);
    });

    const handleKeydown = (e) => {
        if (e.key === 'Escape') {
            e.preventDefault();
            closeConfirm(false);
        }
    };
    document.addEventListener('keydown', handleKeydown);
}
