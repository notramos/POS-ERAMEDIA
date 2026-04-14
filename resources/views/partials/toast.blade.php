<script>
(function () {
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

        const config = {
            success: { bg: '#d1fae5', border: '#10b981', icon: '✓', color: '#065f46' },
            error: { bg: '#fee2e2', border: '#ef4444', icon: '✕', color: '#991b1b' },
            info: { bg: '#dbeafe', border: '#3b82f6', icon: 'ℹ', color: '#1e40af' },
            warning: { bg: '#fef3c7', border: '#f59e0b', color: '#92400e' }
        };
        const c = config[type] || config.info;

        const toast = document.createElement('div');
        toast.style.cssText = `
            background-color: ${c.bg};
            border: 2px solid ${c.border};
            color: ${c.color};
            padding: 14px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            min-width: 280px;
            max-width: 400px;
            pointer-events: auto;
            transform: translateX(120%);
            opacity: 0;
            transition: transform .3s ease, opacity .3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        `;

        const icon = document.createElement('div');
        icon.innerHTML = c.icon;
        icon.style.cssText = `
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: ${c.border};
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        `;

        const content = document.createElement('div');
        content.style.flex = '1';
        
        const title = document.createElement('strong');
        title.textContent = type === 'success' ? 'Berhasil' : type === 'error' ? 'Gagal' : type === 'warning' ? 'Peringatan' : 'Info';
        title.style.display = 'block';
        title.style.marginBottom = '2px';
        
        const text = document.createElement('span');
        text.textContent = message;
        text.style.fontSize = '14px';
        
        content.appendChild(title);
        content.appendChild(text);

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = 'background:none;border:none;font-size:1.5rem;cursor:pointer;color:inherit;padding:0;line-height:1;';
        closeBtn.addEventListener('click', () => {
            removeToast(toast, toastContainer);
        });

        toast.appendChild(icon);
        toast.appendChild(content);
        toast.appendChild(closeBtn);

        toastContainer.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });

        setTimeout(() => {
            removeToast(toast, toastContainer);
        }, 4500);
    }

    function removeToast(toast, container) {
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.remove();
            if (container.children.length === 0) container.remove();
        }, 300);
    }

    window.createToast = createToast;

    document.addEventListener('DOMContentLoaded', function () {
        @if(session()->has('success'))
            createToast('success', @json(session('success')));
        @elseif(session()->has('error'))
            createToast('error', @json(session('error')));
        @endif
    });
})();
</script>
</script>