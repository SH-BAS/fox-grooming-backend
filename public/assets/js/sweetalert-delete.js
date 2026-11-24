/**
 * Replace browser confirm() on delete actions with SweetAlert2.
 * Use: <form method="POST" data-confirm="Are you sure?">...</form>
 * Or call: window.confirmDelete('Message').then(ok => { if (ok) ... })
 */
(function (window, document) {
    'use strict';

    function getSwal() {
        return window.Swal || null;
    }

    function confirmDelete(message, options) {
        const SwalLib = getSwal();
        const text = message || 'This action cannot be undone.';
        const opts = options || {};

        if (!SwalLib) {
            return Promise.resolve(window.confirm(text));
        }

        return SwalLib.fire({
            title: opts.title || 'Are you sure?',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: opts.confirmButtonColor || '#d33',
            cancelButtonColor: opts.cancelButtonColor || '#6c757d',
            confirmButtonText: opts.confirmButtonText || 'Yes, delete it!',
            cancelButtonText: opts.cancelButtonText || 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then(function (result) {
            return !!result.isConfirmed;
        });
    }

    window.confirmDelete = confirmDelete;

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        const message = form.getAttribute('data-confirm');
        if (!message) {
            return;
        }

        if (form.dataset.swalConfirmed === '1') {
            delete form.dataset.swalConfirmed;
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        confirmDelete(message).then(function (ok) {
            if (!ok) {
                return;
            }
            form.dataset.swalConfirmed = '1';
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        });
    }, true);

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-confirm-click]');
        if (!trigger) {
            return;
        }

        const message = trigger.getAttribute('data-confirm-click');
        if (!message) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        confirmDelete(message).then(function (ok) {
            if (!ok) {
                return;
            }

            const href = trigger.getAttribute('href');
            if (href && href !== '#' && href !== 'javascript:void(0)') {
                window.location.href = href;
                return;
            }

            const formId = trigger.getAttribute('data-confirm-form');
            if (formId) {
                const form = document.getElementById(formId);
                if (form) {
                    form.dataset.swalConfirmed = '1';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            }
        });
    }, true);
})(window, document);
