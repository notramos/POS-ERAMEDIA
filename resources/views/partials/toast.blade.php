<script>
(function () {
    // Global createToast available to all pages
    function createToast(type, message) {
        if (!message) return;

        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
                pointer-events: none;
            `;
            document.body.appendChild(toastContainer);
        }

        const bgColor = type === 'success' ? '#d4edda' : type === 'info' ? '#d1ecf1' : '#f8d7da';
        const borderColor = type === 'success' ? '#c3e6cb' : type === 'info' ? '#bee5eb' : '#f5c6cb';
        const textColor = type === 'success' ? '#155724' : type === 'info' ? '#0c5460' : '#721c24';

        const toast = document.createElement('div');
        toast.style.cssText = `
            background-color: ${bgColor};
            border: 1px solid ${borderColor};
            color: ${textColor};
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 350px;
            pointer-events: auto;
            transform: translateX(100%);
            opacity: 0;
            transition: transform .25s ease, opacity .25s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        `;

        // Optional icon
        const icon = document.createElement('span');
        icon.innerHTML = type === 'success' ? '✅' : type === 'info' ? 'ℹ️' : '⚠️';
        icon.style.fontSize = '1rem';

        const text = document.createElement('div');
        text.textContent = message;

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = 'background:none;border:none;font-size:1.1rem;margin-left:8px;cursor:pointer;color:inherit;';
        closeBtn.addEventListener('click', () => {
            toast.remove();
            if (toastContainer.children.length === 0) toastContainer.remove();
        });

        toast.appendChild(icon);
        toast.appendChild(text);
        toast.appendChild(closeBtn);

        // Insert and animate
        toastContainer.appendChild(toast);
        // allow DOM insertion then animate
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });

        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
                if (toastContainer.children.length === 0) toastContainer.remove();
            }, 300);
        }, 4000);
    }

    // Expose globally
    window.createToast = createToast;

    // Show flash sessions centrally
    document.addEventListener('DOMContentLoaded', function () {
        @if(session()->has('success'))
            createToast('success', @json(session('success')));
        @elseif(session()->has('error'))
            createToast('error', @json(session('error')));
        @endif
    });
})();
</script>