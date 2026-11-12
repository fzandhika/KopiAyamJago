/* Kopi Ayam Jago — interaksi ringan tanpa framework. */

const onReady = (callback) => {
    if (document.readyState !== 'loading') {
        callback();
        return;
    }
    document.addEventListener('DOMContentLoaded', callback);
};

const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

onReady(() => {
    /* Navigasi mobile */
    const navToggle = document.querySelector('[data-nav-toggle]');
    const navPanel = document.querySelector('[data-nav-panel]');

    navToggle?.addEventListener('click', () => {
        const isOpen = navPanel?.classList.toggle('hidden') === false;
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });

    /* Konfirmasi aksi hapus */
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    /* Tutup flash message */
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        const close = () => {
            flash.classList.add('opacity-0', 'translate-y-2');
            window.setTimeout(() => flash.remove(), 250);
        };

        flash.querySelector('[data-flash-close]')?.addEventListener('click', close);
        window.setTimeout(close, 6000);
    });

    /* Detail produk: pilihan berat, gilingan, dan jumlah */
    const orderForm = document.querySelector('[data-order-form]');

    if (orderForm) {
        const priceEl = orderForm.querySelector('[data-price]');
        const qtyInput = orderForm.querySelector('[data-qty-input]');
        const qtyLabel = orderForm.querySelector('[data-qty-label]');

        const refreshPrice = () => {
            const selected = orderForm.querySelector('input[name="weight"]:checked');
            if (!selected) {
                return;
            }

            const price = Number(selected.dataset.price);
            const qty = Math.max(1, Number(qtyInput?.value || 1));

            if (priceEl) {
                priceEl.textContent = rupiah(price * qty);
            }
            if (selected.dataset.url) {
                orderForm.action = selected.dataset.url;
            }
            if (qtyLabel) {
                qtyLabel.textContent = String(qty);
            }
        };

        orderForm.addEventListener('change', refreshPrice);
        orderForm.addEventListener('input', refreshPrice);

        orderForm.querySelector('[data-qty-minus]')?.addEventListener('click', () => {
            qtyInput.value = String(Math.max(1, Number(qtyInput.value) - 1));
            refreshPrice();
        });

        orderForm.querySelector('[data-qty-plus]')?.addEventListener('click', () => {
            qtyInput.value = String(Math.min(20, Number(qtyInput.value) + 1));
            refreshPrice();
        });

        refreshPrice();
    }

    /* Navigasi panel admin (mobile) */
    const adminNavToggle = document.querySelector('[data-admin-nav-toggle]');
    const adminNav = document.querySelector('[data-admin-nav]');

    adminNavToggle?.addEventListener('click', () => {
        adminNav?.classList.toggle('-translate-x-full');
    });

    /* Repeater varian di panel admin */
    const repeater = document.querySelector('[data-variant-repeater]');

    if (repeater) {
        const list = repeater.querySelector('[data-variant-rows]');
        const template = repeater.querySelector('template');
        let nextIndex = list ? list.querySelectorAll('[data-variant-row]').length : 0;

        repeater.querySelector('[data-variant-add]')?.addEventListener('click', () => {
            if (!list || !template) {
                return;
            }

            const fragment = template.content.cloneNode(true);
            const index = nextIndex++;

            fragment.querySelectorAll('[name]').forEach((input) => {
                input.name = input.name.replace('__i__', String(index));
            });

            list.appendChild(fragment);
        });

        list?.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-variant-remove]');
            if (!removeButton) {
                return;
            }

            if (list.querySelectorAll('[data-variant-row]').length > 1) {
                removeButton.closest('[data-variant-row]')?.remove();
            }
        });
    }

    /* Pratinjau foto produk di panel admin */
    const imageInput = document.querySelector('[data-image-input]');
    const imagePreview = document.querySelector('[data-image-preview]');
    const imagePreviewWrap = document.querySelector('[data-image-preview-wrap]');
    const imageError = document.querySelector('[data-image-error]');

    imageInput?.addEventListener('change', () => {
        const file = imageInput.files?.[0];

        if (!file || !imagePreview || !imagePreviewWrap) {
            return;
        }

        if (imagePreview.dataset.objectUrl) {
            URL.revokeObjectURL(imagePreview.dataset.objectUrl);
        }

        const objectUrl = URL.createObjectURL(file);
        imagePreview.src = objectUrl;
        imagePreview.dataset.objectUrl = objectUrl;
        imagePreviewWrap.classList.remove('hidden');

        const tooBig = file.size > 2 * 1024 * 1024;
        imageError?.classList.toggle('hidden', !tooBig);

        if (imageError && tooBig) {
            imageError.textContent = 'Ukuran file ' + (file.size / 1024 / 1024).toFixed(1) + ' MB, melebihi batas 2 MB.';
        }
    });
});
