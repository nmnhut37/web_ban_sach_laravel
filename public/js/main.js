    // Kích hoạt dropdown submenu
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
        dropdownSubmenus.forEach(function (submenu) {
            submenu.addEventListener('mouseenter', function () {
                const dropdown = submenu.querySelector('.dropdown-menu');
                if (dropdown) dropdown.style.display = 'block';
            });

            submenu.addEventListener('mouseleave', function () {
                const dropdown = submenu.querySelector('.dropdown-menu');
                if (dropdown) dropdown.style.display = 'none';
            });
        });
    });
    $(document).ready(function () {
        $('#search-input').typeahead({
            source: function (query, process) {
                $('#search-input').addClass('loading');
                return $.get(searchSuggestionsUrl, { query: query })
                    .done(function (data) {
                        // Đảm bảo data là array
                        if (Array.isArray(data)) {
                            // Map data để thêm label cho typeahead
                            const items = data.map(item => {
                                return {
                                    id: item.id,
                                    label: item.product_name, // Typeahead sẽ dùng label để hiển thị
                                    product_name: item.product_name,
                                    img: item.img,
                                    price_formatted: item.price_formatted
                                };
                            });
                            process(items);
                        }
                    })
                    .always(function () {
                        $('#search-input').removeClass('loading');
                    });
            },
            minLength: 2,
            items: 5,
            autoSelect: false,
            displayText: function (item) {
                return item.product_name;
            },
            highlighter: function (item) {
                if (typeof item === 'string') {
                    return item;
                }
    
                const defaultImage = '/storage/images/product/no-image.jpeg';
                const imageUrl = item.img 
                    ? `/storage/images/product/${item.img}` 
                    : defaultImage;
    
                // CSS cho item suggestion
                const style = `
                    <style>
                        .typeahead-item {
                            display: flex;
                            align-items: center;
                            padding: 5px;
                        }
                        .product-img {
                            margin-right: 10px;
                        }
                        .product-info {
                            flex-grow: 1;
                        }
                        .product-name {
                            font-weight: bold;
                        }
                        .product-price {
                            color: #dc3545;
                        }
                    </style>
                `;
    
                return `
                    ${style}
                    <div class="typeahead-item">
                        <div class="product-img">
                            <img src="${imageUrl}" 
                                 alt="${item.product_name || ''}" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                 onerror="this.onerror=null; this.src='${defaultImage}'">
                        </div>
                        <div class="product-info">
                            <div class="product-name">${item.product_name || 'Không có tên'}</div>
                            <div class="product-price">${item.price_formatted || 'Liên hệ'}</div>
                        </div>
                    </div>
                `;
            },
            afterSelect: function (item) {
                window.location.href = `/product/${item.id}`;
            },
            matcher: function (item) {
                return true;
            }
        });
    });
    